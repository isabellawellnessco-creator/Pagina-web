<?php
/**
 * Plugin Name: Homad Core
 * Description: Core functionality for the Homad theme (CPTs, Admin Settings, Meta Boxes).
 * Version: 1.1.0
 * Author: Codex
 * Text Domain: homad-core
 */

defined( 'ABSPATH' ) || exit;

// Define paths
define( 'HOMAD_CORE_PATH', plugin_dir_path( __FILE__ ) );

// Include core files
require_once HOMAD_CORE_PATH . 'inc/cpts.php';
require_once HOMAD_CORE_PATH . 'inc/meta-boxes.php'; // New Native Meta Boxes
require_once HOMAD_CORE_PATH . 'inc/admin-columns.php'; // New Admin Tracking
require_once HOMAD_CORE_PATH . 'inc/admin.php';
require_once HOMAD_CORE_PATH . 'inc/helpers.php';
require_once HOMAD_CORE_PATH . 'inc/forms.php';
require_once HOMAD_CORE_PATH . 'inc/custom.php'; // Shortcodes & Logic

// Activation Hook
register_activation_hook( __FILE__, 'homad_core_activate' );
function homad_core_activate() {
    homad_register_cpts();
    flush_rewrite_rules();
}

// Deactivation Hook
register_deactivation_hook( __FILE__, 'homad_core_deactivate' );
function homad_core_deactivate() {
    flush_rewrite_rules();
}
