<?php
/**
 * Bootstrap del theme hijo homad-child.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

define('HOMAD_CHILD_DIR', get_stylesheet_directory());
define('HOMAD_CHILD_URI', get_stylesheet_directory_uri());

$homad_includes = array(
    'inc/setup.php',
    'inc/enqueue.php',
    'inc/helpers.php',
    'inc/woocommerce.php',
    'inc/shortcodes.php',
    'inc/content-seeder.php', // Added Content Seeder
    // 'inc/admin-settings.php', // Moved to homad-core plugin
    // 'inc/footer-injections.php',
);

foreach ($homad_includes as $file) {
    $path = trailingslashit(HOMAD_CHILD_DIR) . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
