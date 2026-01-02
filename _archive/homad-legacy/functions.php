<?php
/**
 * Bootstrap del theme homad-child (Unified with Core).
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

// Constants
define('HOMAD_CHILD_DIR', get_stylesheet_directory());
define('HOMAD_CHILD_URI', get_stylesheet_directory_uri());
define('HOMAD_CORE_PATH', HOMAD_CHILD_DIR . '/inc/core/'); // Adjusted to point to theme's core

// 1. Theme Setup
require_once HOMAD_CHILD_DIR . '/inc/setup.php';
require_once HOMAD_CHILD_DIR . '/inc/enqueue.php';

// 2. Helpers (Merged Utilities)
require_once HOMAD_CHILD_DIR . '/inc/helpers.php';

// 3. Core Logic (Migrated from Plugin)
require_once HOMAD_CHILD_DIR . '/inc/core/skins.php';
require_once HOMAD_CHILD_DIR . '/inc/core/admin-skin-settings.php';
require_once HOMAD_CHILD_DIR . '/inc/core/cpts.php';
require_once HOMAD_CHILD_DIR . '/inc/core/meta-boxes.php';
require_once HOMAD_CHILD_DIR . '/inc/core/admin-columns.php';
require_once HOMAD_CHILD_DIR . '/inc/core/admin.php';
require_once HOMAD_CHILD_DIR . '/inc/core/forms.php';
require_once HOMAD_CHILD_DIR . '/inc/core/admin-forms.php';
require_once HOMAD_CHILD_DIR . '/inc/core/custom.php';

// 4. Elementor Integration
if (did_action('elementor/loaded') || defined('ELEMENTOR_VERSION')) {
    if (file_exists(HOMAD_CHILD_DIR . '/inc/core/elementor/class-homad-elementor.php')) {
        require_once HOMAD_CHILD_DIR . '/inc/core/elementor/class-homad-elementor.php';
    }
}

// 5. Theme Features
require_once HOMAD_CHILD_DIR . '/inc/woocommerce.php';
require_once HOMAD_CHILD_DIR . '/inc/shortcodes.php';
require_once HOMAD_CHILD_DIR . '/inc/content-seeder.php';
require_once HOMAD_CHILD_DIR . '/inc/hooks-special-states.php';

/**
 * Register CPTs and Flush Rules on Theme Switch.
 * Replaces plugin activation hook.
 */
function homad_theme_activation_logic() {
    if (function_exists('homad_register_cpts')) {
        homad_register_cpts();
    }
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'homad_theme_activation_logic');
