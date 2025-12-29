<?php
/**
 * Setup del tema.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

function homad_child_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // WooCommerce support
    add_theme_support('woocommerce');

    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Menús (WoodMart ya trae; igual definimos para coherencia)
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'homad-child'),
        'mobile'  => __('Mobile Menu', 'homad-child'),
    ));
}
add_action('after_setup_theme', 'homad_child_setup');
