<?php
/**
 * Homad Child Theme functions.
 */

define('HOMAD_CHILD_DIR', get_stylesheet_directory());
define('HOMAD_CHILD_URI', get_stylesheet_directory_uri());

$homad_child_includes = array(
    'inc/setup.php',
    'inc/enqueue.php',
    'inc/woocommerce.php',
    'inc/helpers.php',
);

foreach ($homad_child_includes as $file) {
    $path = trailingslashit(HOMAD_CHILD_DIR) . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
