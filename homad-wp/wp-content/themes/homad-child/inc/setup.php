<?php
/**
 * Theme setup.
 */

function homad_child_setup() {
    add_theme_support('woocommerce');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'homad_child_setup');
