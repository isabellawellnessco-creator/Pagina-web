<?php
/**
 * Homad Skin Manager
 * Handles logic for switching between different visual "Skins" (Sub-themes).
 *
 * @package Homad
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define current skin based on database option.
// Defaults to 'default' if not set, or 'skincare' if forced for dev.
if ( ! defined( 'HOMAD_SKIN' ) ) {
    $active_skin = get_option( 'homad_skin_active_skin' );
    if ( empty( $active_skin ) ) {
        // Fallback or default logic.
        // For this task, we want 'skincare' to be available but maybe not forced globally
        // unless selected. However, user instruction implies they want THIS to be the theme.
        // Let's default to 'skincare' if option is missing to match immediate request,
        // OR better, trust the option.
        // But since I just created the option, it's empty. I will set default to 'skincare' for now
        // so the user sees the change immediately.
        $active_skin = 'skincare';
    }
	define( 'HOMAD_SKIN', $active_skin );
}

class Homad_Skins {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_skin_assets' ), 20 ); // Priority 20 to load after main theme assets
		add_filter( 'body_class', array( $this, 'add_skin_body_class' ) );
		add_action( 'admin_init', array( $this, 'register_skin_settings' ) );
	}

	/**
	 * Enqueue Skin-specific assets.
	 */
	public function enqueue_skin_assets() {
		$skin = HOMAD_SKIN;
		$uri  = get_stylesheet_directory_uri() . '/assets/skins/' . $skin;
		$dir  = get_stylesheet_directory() . '/assets/skins/' . $skin;

		// Load Tokens (Variables)
		if ( file_exists( $dir . '/css/variables.css' ) ) {
			wp_enqueue_style( 'homad-skin-tokens', $uri . '/css/variables.css', array(), '1.0.0' );
		}

		// Load Layout Overrides
		if ( file_exists( $dir . '/css/layout.css' ) ) {
			wp_enqueue_style( 'homad-skin-layout', $uri . '/css/layout.css', array( 'homad-main-style' ), '1.0.0' );
		}

		// Load Component Overrides
		if ( file_exists( $dir . '/css/components.css' ) ) {
			wp_enqueue_style( 'homad-skin-components', $uri . '/css/components.css', array( 'homad-main-style' ), '1.0.0' );
		}

		// Load WooCommerce Overrides
		if ( file_exists( $dir . '/css/woocommerce.css' ) ) {
			wp_enqueue_style( 'homad-skin-woocommerce', $uri . '/css/woocommerce.css', array( 'homad-woocommerce' ), '1.0.0' );
		}

		// Load JS
		if ( file_exists( $dir . '/js/app.js' ) ) {
			wp_enqueue_script( 'homad-skin-js', $uri . '/js/app.js', array( 'jquery' ), '1.0.0', true );
		}

        // Load Cursor if defined
        $cursor_url = get_option( 'homad_skin_cursor_image' );
        if ( ! empty( $cursor_url ) ) {
            wp_enqueue_script( 'homad-cursor', get_stylesheet_directory_uri() . '/assets/js/homad-cursor.js', array( 'jquery' ), '1.0.0', true );
            wp_localize_script( 'homad-cursor', 'homadCursorSettings', array(
                'imageUrl' => $cursor_url
            ));
        }
	}

	/**
	 * Add skin class to body for CSS scoping.
	 */
	public function add_skin_body_class( $classes ) {
		$classes[] = 'skin-' . HOMAD_SKIN;
		return $classes;
	}

	/**
	 * Register settings if needed (placeholder).
	 */
	public function register_skin_settings() {
		// Future: Add Skin Switcher in Admin
	}
}

Homad_Skins::get_instance();
