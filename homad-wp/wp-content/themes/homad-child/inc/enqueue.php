<?php
/**
 * Enqueue scripts and styles.
 */

function homad_child_scripts() {
    $version = '1.0.0';

    // Enqueue CSS Architecture
    wp_enqueue_style('homad-tokens', HOMAD_CHILD_URI . '/assets/css/tokens.css', [], $version);
    wp_enqueue_style('homad-layout', HOMAD_CHILD_URI . '/assets/css/layout.css', ['homad-tokens'], $version);
    wp_enqueue_style('homad-components', HOMAD_CHILD_URI . '/assets/css/components.css', ['homad-layout'], $version);
    wp_enqueue_style('homad-mobile', HOMAD_CHILD_URI . '/assets/css/mobile.css', ['homad-layout'], $version);

    // Enqueue Child Style (Main) - overrides everything
    wp_enqueue_style('homad-child-style', get_stylesheet_uri(), ['homad-mobile'], $version);

    // JS would go here (e.g., motion.js, app.js)
}
add_action('wp_enqueue_scripts', 'homad_child_scripts');
