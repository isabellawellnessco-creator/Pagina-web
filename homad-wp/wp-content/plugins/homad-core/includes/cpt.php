<?php
/**
 * Custom post types.
 */

function homad_core_register_cpts() {
    register_post_type('homad_project', array(
        'labels' => array(
            'name' => __('Projects', 'homad'),
            'singular_name' => __('Project', 'homad'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    register_post_type('homad_service', array(
        'labels' => array(
            'name' => __('Services', 'homad'),
            'singular_name' => __('Service', 'homad'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));

    register_post_type('homad_package', array(
        'labels' => array(
            'name' => __('Packages', 'homad'),
            'singular_name' => __('Package', 'homad'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-index-card',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'homad_core_register_cpts');
