<?php
/**
 * Plugin Name: Homad Core
 * Description: Core functionality, CPTs, and API logic for Homad.
 * Version: 1.0.0
 * Author: Homad
 * Text Domain: homad-core
 */

defined( 'ABSPATH' ) || exit;

define( 'HOMAD_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'HOMAD_CORE_URL', plugin_dir_url( __FILE__ ) );

// Include Core Files
require_once HOMAD_CORE_PATH . 'inc/cpts.php';
require_once HOMAD_CORE_PATH . 'inc/forms.php';
require_once HOMAD_CORE_PATH . 'inc/admin.php';
