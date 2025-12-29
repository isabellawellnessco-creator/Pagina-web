<?php
/**
 * Plugin Name: Homad Core
 * Description: Core functionality for Homad (CPTs, shortcodes, templates).
 * Version: 0.1.0
 * Author: Homad
 * Text Domain: homad
 */

if (!defined('ABSPATH')) {
    exit;
}

define('HOMAD_CORE_DIR', plugin_dir_path(__FILE__));
define('HOMAD_CORE_URL', plugin_dir_url(__FILE__));

$homad_core_includes = array(
    'includes/cpt.php',
    'includes/taxonomies.php',
    'includes/shortcodes.php',
    'includes/admin.php',
    'includes/rest.php',
);

foreach ($homad_core_includes as $file) {
    $path = HOMAD_CORE_DIR . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
