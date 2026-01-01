<?php
/**
 * Plugin Name: Skincare Site Kit
 * Description: Core logic, Elementor widgets, and content seeder for the Skincare theme.
 * Version: 1.0.0
 * Author: Homad
 * Text Domain: skincare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Constants
define( 'SKINCARE_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SKINCARE_KIT_URL', plugin_dir_url( __FILE__ ) );

// Autoloader
require_once SKINCARE_KIT_PATH . 'includes/class-skincare-loader.php';

// Initialize
function skincare_kit_init() {
    // Hooks
    require_once SKINCARE_KIT_PATH . 'includes/woocommerce-hooks.php';

    // Seeder (run on init to check for activation flag or manual trigger)
    if ( is_admin() ) {
        require_once SKINCARE_KIT_PATH . 'includes/seed-content.php';
    }
}
add_action( 'plugins_loaded', 'skincare_kit_init' );

// Elementor Widgets
function skincare_register_elementor_widgets( $widgets_manager ) {
    require_once SKINCARE_KIT_PATH . 'includes/elementor/widgets/class-sk-hero.php';
    require_once SKINCARE_KIT_PATH . 'includes/elementor/widgets/class-sk-product-loop.php';
    require_once SKINCARE_KIT_PATH . 'includes/elementor/widgets/class-sk-category-tiles.php';
    require_once SKINCARE_KIT_PATH . 'includes/elementor/widgets/class-sk-usp-bar.php';

    $widgets_manager->register( new \Skincare_Hero_Widget() );
    $widgets_manager->register( new \Skincare_Product_Loop_Widget() );
    $widgets_manager->register( new \Skincare_Category_Tiles_Widget() );
    $widgets_manager->register( new \Skincare_USP_Bar_Widget() );
}
add_action( 'elementor/widgets/register', 'skincare_register_elementor_widgets' );

// Enqueue Widget Styles (Frontend)
function skincare_kit_scripts() {
    wp_enqueue_style( 'skincare-kit-widgets', SKINCARE_KIT_URL . 'assets/css/widgets.css', [], '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'skincare_kit_scripts' );
