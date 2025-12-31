<?php
/**
 * Bootstrap del theme hijo homad-child.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

define('HOMAD_CHILD_DIR', get_stylesheet_directory());
define('HOMAD_CHILD_URI', get_stylesheet_directory_uri());

// Define Core Paths (Integrated from Plugin)
define('HOMAD_CORE_PATH', HOMAD_CHILD_DIR . '/inc/core/');
define('HOMAD_CORE_URI', HOMAD_CHILD_URI . '/assets/core/'); // Pointing to assets for URL usage if needed, or we can just use child uri

$homad_includes = array(
    // Theme Includes
    'inc/setup.php',
    'inc/enqueue.php',
    'inc/helpers.php',
    'inc/woocommerce.php',
    'inc/shortcodes.php',
    'inc/content-seeder.php',

    // Integrated Core Includes (Formerly Plugin)
    'inc/core/cpts.php',
    'inc/core/meta-boxes.php',
    'inc/core/admin-columns.php',
    'inc/core/admin.php',
    'inc/core/admin-forms.php',
    // 'inc/core/helpers.php', // Merged into theme helpers.php
    'inc/core/forms.php',
    'inc/core/custom.php',
);

foreach ($homad_includes as $file) {
    $path = trailingslashit(HOMAD_CHILD_DIR) . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

// Elementor Integration
if ( did_action( 'elementor/loaded' ) ) {
    $elementor_path = HOMAD_CORE_PATH . 'elementor/class-homad-elementor.php';
    if ( file_exists( $elementor_path ) ) {
        require_once $elementor_path;
    }
}

// Activation Logic (Moved from Plugin)
// Note: Themes don't have activation hooks like plugins.
// We generally use 'after_switch_theme' or run it on init if not present.
function homad_theme_activation_logic() {
    if ( function_exists('homad_register_cpts') ) {
        homad_register_cpts();
        flush_rewrite_rules();
    }
}
add_action( 'after_switch_theme', 'homad_theme_activation_logic' );
