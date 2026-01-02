<?php
/**
 * Plugin Name: Skin Cupid Site Kit
 * Description: Modular functionality plugin for Skin Cupid Theme (v2.0).
 * Version: 2.0.0
 * Author: Skin Cupid Dev
 * Text Domain: skincare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Constants
define( 'SKINCARE_KIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SKINCARE_KIT_URL', plugin_dir_url( __FILE__ ) );

// Autoloader
require_once SKINCARE_KIT_PATH . 'inc/core/class-autoloader.php';
\Skincare\SiteKit\Core\Autoloader::init();

// Initialize Plugin
function skincare_kit_init() {
	$loader = new \Skincare\SiteKit\Core\Loader();
	$loader->run();
}
add_action( 'plugins_loaded', 'skincare_kit_init' );
