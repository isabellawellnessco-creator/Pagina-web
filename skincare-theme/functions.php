<?php
/**
 * Theme Functions
 *
 * @package SkincareTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enqueue Assets
function skincare_enqueue_scripts() {
	$ver = '1.0.0';

	// CSS
	wp_enqueue_style( 'skincare-tokens', get_stylesheet_directory_uri() . '/assets/css/tokens.css', [], $ver );
	wp_enqueue_style( 'skincare-base', get_stylesheet_directory_uri() . '/assets/css/base.css', ['skincare-tokens'], $ver );
	wp_enqueue_style( 'skincare-components', get_stylesheet_directory_uri() . '/assets/css/components.css', ['skincare-base'], $ver );
	wp_enqueue_style( 'skincare-style', get_stylesheet_uri(), ['skincare-components'], $ver );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'skincare-woo', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css', ['skincare-components'], $ver );
	}

	// JS
	wp_enqueue_script( 'skincare-theme', get_stylesheet_directory_uri() . '/assets/js/theme.js', [], $ver, true );
}
add_action( 'wp_enqueue_scripts', 'skincare_enqueue_scripts' );

// Theme Support
function skincare_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'] );

	// Elementor Locations
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'skincare_setup' );

// Register Menus
register_nav_menus( [
	'primary' => __( 'Primary Menu', 'skincare' ),
	'mobile'  => __( 'Mobile Menu', 'skincare' ),
	'footer'  => __( 'Footer Menu', 'skincare' ),
] );

// Shortcodes
function skincare_shortcode_button( $atts, $content = null ) {
	$atts = shortcode_atts(
		[
			'url'   => '#',
			'class' => 'sk-button',
			'text'  => '',
		],
		$atts,
		'sk_button'
	);

	$label = $content ? $content : $atts['text'];
	if ( '' === $label ) {
		return '';
	}

	return sprintf(
		'<a class="%s" href="%s">%s</a>',
		esc_attr( $atts['class'] ),
		esc_url( $atts['url'] ),
		esc_html( $label )
	);
}
add_shortcode( 'sk_button', 'skincare_shortcode_button' );

// Helper to check if Woo is active
function skincare_is_woo() {
	return class_exists( 'WooCommerce' );
}

/**
 * Load Bundled Plugin if it exists (Simulation of a plugin in a theme repo)
 * In a real scenario, this would be a separate plugin.
 */
if ( file_exists( get_stylesheet_directory() . '/bundled-plugins/skincare-site-kit/skincare-site-kit.php' ) ) {
    require_once get_stylesheet_directory() . '/bundled-plugins/skincare-site-kit/skincare-site-kit.php';
}

/**
 * Auto-trigger Seeder for "Skin Cupid" setup
 * Removed manual GET injection, relying on version check in Seeder::run_seeder
 */

/**
 * Create core pages that mirror the F8 reference HTML set.
 */
function skincare_seed_core_pages() {
	if ( ! function_exists( 'wp_insert_post' ) ) {
		return;
	}

	$pages = [
		[
			'title'    => 'Home',
			'slug'     => 'home',
			'template' => 'template-landing.php',
		],
		[
			'title'    => 'Account',
			'slug'     => 'account',
			'template' => 'page-account.php',
		],
		[
			'title'    => 'Care',
			'slug'     => 'care',
			'template' => 'page-care.php',
		],
		[
			'title'    => 'Contact',
			'slug'     => 'contact',
			'template' => 'page-contact.php',
		],
		[
			'title'    => 'FAQs',
			'slug'     => 'faqs',
			'template' => 'page-faqs.php',
		],
		[
			'title'    => 'Korean',
			'slug'     => 'korean',
			'template' => 'page-korean.php',
		],
		[
			'title'    => 'Learn',
			'slug'     => 'learn',
			'template' => 'page-learn.php',
		],
		[
			'title'    => 'Login',
			'slug'     => 'login',
			'template' => 'page-login.php',
		],
		[
			'title'    => 'Makeup',
			'slug'     => 'makeup',
			'template' => 'page-makeup.php',
		],
		[
			'title'    => 'Privacy Policy',
			'slug'     => 'privacy-policy',
			'template' => 'page-privacy.php',
		],
		[
			'title'    => 'Rewards',
			'slug'     => 'rewards',
			'template' => 'page-rewards.php',
		],
		[
			'title'    => 'Shipping',
			'slug'     => 'shipping',
			'template' => 'page-shipping.php',
		],
		[
			'title'    => 'Skin',
			'slug'     => 'skin',
			'template' => 'page-skin.php',
		],
		[
			'title'    => 'Store Locator',
			'slug'     => 'store-locator',
			'template' => 'page-store-locator.php',
		],
		[
			'title'    => 'Terms & Conditions',
			'slug'     => 'terms',
			'template' => 'page-terms.php',
		],
		[
			'title'    => 'Vegan',
			'slug'     => 'vegan',
			'template' => 'page-vegan.php',
		],
		[
			'title'    => 'Wishlist',
			'slug'     => 'wishlist',
			'template' => 'page-wishlist.php',
		],
	];

	foreach ( $pages as $page ) {
		$existing = get_page_by_path( $page['slug'] );
		if ( $existing ) {
			continue;
		}

		$page_id = wp_insert_post(
			[
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			]
		);

		if ( ! is_wp_error( $page_id ) && ! empty( $page['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', $page['template'] );
		}
	}

	$home_page = get_page_by_path( 'home' );
	if ( $home_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page->ID );
	}
}
add_action( 'after_switch_theme', 'skincare_seed_core_pages' );
