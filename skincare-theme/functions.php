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
	$theme_dir = get_stylesheet_directory();
	$theme_uri = get_stylesheet_directory_uri();

	// Helper for versioning
	$get_ver = function( $path ) use ( $theme_dir ) {
		$file = $theme_dir . $path;
		return file_exists( $file ) ? filemtime( $file ) : '1.0.0';
	};

	// CSS
	// Load tokens first (contains fonts and variables)
	wp_enqueue_style( 'skincare-tokens', $theme_uri . '/assets/css/tokens.css', [], $get_ver('/assets/css/tokens.css') );

	// Load Base (Resets)
	wp_enqueue_style( 'skincare-base', $theme_uri . '/assets/css/base.css', ['skincare-tokens'], $get_ver('/assets/css/base.css') );

	// Load Components
	wp_enqueue_style( 'skincare-components', $theme_uri . '/assets/css/components.css', ['skincare-base'], $get_ver('/assets/css/components.css') );

	// Load Main Style (Theme root)
	wp_enqueue_style( 'skincare-style', get_stylesheet_uri(), ['skincare-components'], $get_ver('/style.css') );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'skincare-woo', $theme_uri . '/assets/css/woocommerce.css', ['skincare-components'], $get_ver('/assets/css/woocommerce.css') );
	}

	// JS
	wp_enqueue_script( 'skincare-theme', $theme_uri . '/assets/js/theme.js', ['jquery'], $get_ver('/assets/js/theme.js'), true );
}
add_action( 'wp_enqueue_scripts', 'skincare_enqueue_scripts' );

// Theme Support
function skincare_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'] );
	add_theme_support( 'menus' );

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
