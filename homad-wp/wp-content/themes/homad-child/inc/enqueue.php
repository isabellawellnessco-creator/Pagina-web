<?php
/**
 * Enqueue assets.
 */

function homad_child_enqueue_assets() {
    $parent_style = get_template_directory() . '/style.css';
    $child_css = HOMAD_CHILD_DIR . '/assets/css/main.css';
    $child_js = HOMAD_CHILD_DIR . '/assets/js/main.js';

    wp_enqueue_style(
        'woodmart-style',
        get_template_directory_uri() . '/style.css',
        array(),
        file_exists($parent_style) ? filemtime($parent_style) : null
    );

    wp_enqueue_style(
        'homad-child-style',
        HOMAD_CHILD_URI . '/assets/css/main.css',
        array('woodmart-style'),
        file_exists($child_css) ? filemtime($child_css) : null
    );

    wp_enqueue_script(
        'homad-child-script',
        HOMAD_CHILD_URI . '/assets/js/main.js',
        array('jquery'),
        file_exists($child_js) ? filemtime($child_js) : null,
        true
    );
}
add_action('wp_enqueue_scripts', 'homad_child_enqueue_assets');
