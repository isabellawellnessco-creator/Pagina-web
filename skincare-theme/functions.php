<?php
/**
 * Theme Functions
 *
 * @package SkincareTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/plugin-setup.php';
// Demo seeding is handled by the bundled Skincare Site Kit plugin.

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

	if ( skincare_is_f8_page() ) {
		$f8_base = get_stylesheet_directory_uri() . '/assets/f8';
		$f8_css  = $f8_base . '/css';
		$f8_js   = $f8_base . '/js';

		wp_enqueue_style( 'skincare-f8-fonts', $f8_css . '/fonts.css', [], $ver );
		wp_enqueue_style( 'skincare-f8-core', $f8_css . '/core.css', ['skincare-f8-fonts'], $ver );
		wp_enqueue_style( 'skincare-f8-app-embed', $f8_css . '/app-embed-block.css', ['skincare-f8-core'], $ver );
		wp_enqueue_style( 'skincare-f8-app-base', $f8_css . '/app-base.css', ['skincare-f8-app-embed'], $ver );
		wp_enqueue_style( 'skincare-f8-accelerated-checkout', $f8_css . '/accelerated-checkout-backwards-compat.css', ['skincare-f8-app-base'], $ver );
		wp_enqueue_style( 'skincare-f8-1700', $f8_css . '/1700.7ed44b4acefaba122d0d.css', ['skincare-f8-accelerated-checkout'], $ver );
		wp_enqueue_style( 'skincare-f8-532', $f8_css . '/532.f5cf641d94bc70223e6f.css', ['skincare-f8-1700'], $ver );
		wp_enqueue_style( 'skincare-f8-base', $f8_css . '/base.css', ['skincare-f8-532'], $ver );
		wp_enqueue_style( 'skincare-f8-custom', $f8_css . '/custom.css', ['skincare-f8-base'], $ver );
		wp_enqueue_style( 'skincare-f8-wishlist-collection', $f8_css . '/component-wishlist-button-collection.css', ['skincare-f8-custom'], $ver );
		wp_enqueue_style( 'skincare-f8-wishlist-product', $f8_css . '/component-wishlist-button-product.css', ['skincare-f8-wishlist-collection'], $ver );
		wp_enqueue_style( 'skincare-f8-wishlist-page', $f8_css . '/component-wishlist-page-bundle.css', ['skincare-f8-wishlist-product'], $ver );
		wp_enqueue_style( 'skincare-f8-glider', $f8_css . '/glider.min.css', ['skincare-f8-wishlist-page'], $ver );
		wp_enqueue_style( 'skincare-f8-bogos', $f8_css . '/bogos.bundle.min.css', ['skincare-f8-glider'], $ver );
		wp_enqueue_style( 'skincare-f8-freegifts', $f8_css . '/freegifts-main.min.css', ['skincare-f8-bogos'], $ver );
		wp_enqueue_style( 'skincare-f8-collection-tile', $f8_css . '/collection-tile-controller.af177681.css', ['skincare-f8-freegifts'], $ver );
		wp_enqueue_style( 'skincare-f8-vegan-form', $f8_css . '/vegan-form.css', ['skincare-f8-collection-tile'], $ver );
		wp_enqueue_style( 'skincare-f8-vegan-main', $f8_css . '/vegan-main.css', ['skincare-f8-vegan-form'], $ver );
		wp_enqueue_style( 'skincare-f8-vegan-klarna-fonts', $f8_css . '/vegan-klarna-fonts.css', ['skincare-f8-vegan-main'], $ver );
		wp_enqueue_style( 'skincare-f8-locator-fonts', $f8_css . '/locator-fonts.css', ['skincare-f8-vegan-klarna-fonts'], $ver );
		wp_enqueue_style( 'skincare-f8-locator-fonts-2', $f8_css . '/locator-fonts-2.css', ['skincare-f8-locator-fonts'], $ver );

		wp_enqueue_script( 'skincare-f8-glider', $f8_js . '/glider.min.js', [], $ver, true );
		wp_enqueue_script( 'skincare-f8-bundle', $f8_js . '/bundle.min.js', ['skincare-f8-glider'], $ver, true );

		if ( is_page( ['contact', 'learn', 'skin'] ) || is_page_template( 'template-landing.php' ) || is_page( 'care' ) ) {
			wp_enqueue_script( 'skincare-f8-showcase-gallery', $f8_js . '/showcase-gallery.js', [], $ver, true );
		}

		if ( is_page( 'rewards' ) ) {
			wp_enqueue_script( 'skincare-f8-anime', $f8_js . '/anime.min.js', [], $ver, true );
			wp_enqueue_script( 'skincare-f8-loyalty-hero', $f8_js . '/loyalty-hero.js', ['skincare-f8-anime'], $ver, true );
		}

		if ( is_page( 'care' ) ) {
			wp_enqueue_script( 'skincare-f8-jobly', $f8_js . '/jobly.js', [], $ver, true );
		}

		if ( is_page( 'store-locator' ) ) {
			wp_enqueue_script( 'skincare-f8-store-locator', $f8_js . '/store-locator-widget.js', [], $ver, true );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'skincare_enqueue_scripts' );

function skincare_is_f8_page() {
	if ( is_page_template( 'template-landing.php' ) ) {
		return true;
	}

	$f8_slugs = [
		'account',
		'login',
		'vegan',
		'terms',
		'korean',
		'makeup',
		'store-locator',
		'shipping',
		'wishlist',
		'faqs',
		'privacy',
		'rewards',
		'care',
		'skin',
		'learn',
		'contact',
	];

	return is_page( $f8_slugs );
}

// Theme Support
function skincare_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'custom-logo', [
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	] );
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

if ( ! function_exists( 'skincare_safe_shortcode' ) ) {
	function skincare_safe_shortcode( $tag, $atts_string = '' ) {
		if ( ! shortcode_exists( $tag ) ) {
			return '';
		}

		$atts_string = trim( $atts_string );
		$shortcode   = $atts_string ? sprintf( '[%s %s]', $tag, $atts_string ) : sprintf( '[%s]', $tag );

		return do_shortcode( $shortcode );
	}
}

function skincare_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'skincare_branding', [
		'title'       => __( 'Skincare Branding', 'skincare' ),
		'description' => __( 'Control global branding, colors, and typography.', 'skincare' ),
		'priority'    => 30,
	] );

	$wp_customize->add_setting( 'skincare_logo_max_width', [
		'default'           => 180,
		'sanitize_callback' => 'absint',
	] );

	$wp_customize->add_control( 'skincare_logo_max_width', [
		'label'       => __( 'Logo max width (px)', 'skincare' ),
		'section'     => 'skincare_branding',
		'type'        => 'number',
		'input_attrs' => [
			'min'  => 60,
			'max'  => 480,
			'step' => 10,
		],
	] );

	$wp_customize->add_setting( 'skincare_color_accent', [
		'default'           => '#E5757E',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_setting( 'skincare_color_accent_hover', [
		'default'           => '#D9656E',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_setting( 'skincare_color_background', [
		'default'           => '#FFFFFF',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_setting( 'skincare_color_text', [
		'default'           => '#0F3062',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_setting( 'skincare_color_text_light', [
		'default'           => '#8798B0',
		'sanitize_callback' => 'sanitize_hex_color',
	] );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'skincare_color_accent', [
		'label'   => __( 'Accent color', 'skincare' ),
		'section' => 'skincare_branding',
	] ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'skincare_color_accent_hover', [
		'label'   => __( 'Accent hover color', 'skincare' ),
		'section' => 'skincare_branding',
	] ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'skincare_color_background', [
		'label'   => __( 'Background color', 'skincare' ),
		'section' => 'skincare_branding',
	] ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'skincare_color_text', [
		'label'   => __( 'Primary text color', 'skincare' ),
		'section' => 'skincare_branding',
	] ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'skincare_color_text_light', [
		'label'   => __( 'Secondary text color', 'skincare' ),
		'section' => 'skincare_branding',
	] ) );

	$wp_customize->add_setting( 'skincare_font_heading', [
		'default'           => 'Playfair Display, serif',
		'sanitize_callback' => 'sanitize_text_field',
	] );

	$wp_customize->add_setting( 'skincare_font_body', [
		'default'           => 'Inter, sans-serif',
		'sanitize_callback' => 'sanitize_text_field',
	] );

	$wp_customize->add_control( 'skincare_font_heading', [
		'label'       => __( 'Heading font stack', 'skincare' ),
		'section'     => 'skincare_branding',
		'type'        => 'text',
		'description' => __( 'Example: "Playfair Display, serif"', 'skincare' ),
	] );

	$wp_customize->add_control( 'skincare_font_body', [
		'label'       => __( 'Body font stack', 'skincare' ),
		'section'     => 'skincare_branding',
		'type'        => 'text',
		'description' => __( 'Example: "Inter, sans-serif"', 'skincare' ),
	] );
}
add_action( 'customize_register', 'skincare_customize_register' );

function skincare_output_customizer_css() {
	$branding = get_option( 'sk_theme_branding_settings', [] );
	$accent = isset( $branding['accent_color'] ) && $branding['accent_color'] ? $branding['accent_color'] : get_theme_mod( 'skincare_color_accent', '#E5757E' );
	$accent_hover = isset( $branding['accent_hover_color'] ) && $branding['accent_hover_color'] ? $branding['accent_hover_color'] : get_theme_mod( 'skincare_color_accent_hover', '#D9656E' );
	$background = isset( $branding['background_color'] ) && $branding['background_color'] ? $branding['background_color'] : get_theme_mod( 'skincare_color_background', '#FFFFFF' );
	$text = isset( $branding['text_color'] ) && $branding['text_color'] ? $branding['text_color'] : get_theme_mod( 'skincare_color_text', '#0F3062' );
	$text_light = isset( $branding['text_light_color'] ) && $branding['text_light_color'] ? $branding['text_light_color'] : get_theme_mod( 'skincare_color_text_light', '#8798B0' );
	$heading_font = isset( $branding['heading_font'] ) && $branding['heading_font'] ? $branding['heading_font'] : get_theme_mod( 'skincare_font_heading', 'Playfair Display, serif' );
	$body_font = isset( $branding['body_font'] ) && $branding['body_font'] ? $branding['body_font'] : get_theme_mod( 'skincare_font_body', 'Inter, sans-serif' );
	$logo_width = isset( $branding['logo_width'] ) && $branding['logo_width'] ? absint( $branding['logo_width'] ) : absint( get_theme_mod( 'skincare_logo_max_width', 180 ) );
	?>
	<style>
		:root {
			--c-accent: <?php echo esc_html( $accent ); ?>;
			--c-accent-hover: <?php echo esc_html( $accent_hover ); ?>;
			--c-background-main: <?php echo esc_html( $background ); ?>;
			--c-text-main: <?php echo esc_html( $text ); ?>;
			--c-text-light: <?php echo esc_html( $text_light ); ?>;
			--font-heading: <?php echo esc_html( $heading_font ); ?>;
			--font-body: <?php echo esc_html( $body_font ); ?>;
		}
		.site-branding .custom-logo-link img {
			max-width: <?php echo esc_html( $logo_width ); ?>px;
			height: auto;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'skincare_output_customizer_css', 20 );

// Helper to check if Woo is active
function skincare_is_woo() {
	return class_exists( 'WooCommerce' );
}

function skincare_get_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/' . trim( $slug, '/' ) . '/' );
}
