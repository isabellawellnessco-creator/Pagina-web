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
}
add_action( 'wp_enqueue_scripts', 'skincare_enqueue_scripts' );

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
