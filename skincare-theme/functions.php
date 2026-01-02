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
