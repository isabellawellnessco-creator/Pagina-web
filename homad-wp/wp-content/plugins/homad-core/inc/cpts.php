<?php
/**
 * Custom Post Types and Taxonomies.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Custom Post Types.
 * Services, Packages, Portfolio, Leads.
 */
function homad_register_cpts() {

    // Services (Architecture, Interior, etc.)
    register_post_type('service', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
            'all_items' => 'All Services',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-hammer',
        'rewrite' => array('slug' => 'service'),
        'menu_position' => 20,
    ));

    // Packages (B2C Rooms, B2B Solutions)
    register_post_type('package', array(
        'labels' => array(
            'name' => 'Packages',
            'singular_name' => 'Package',
            'add_new_item' => 'Add New Package',
            'edit_item' => 'Edit Package',
            'all_items' => 'All Packages',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-box',
        'rewrite' => array('slug' => 'package'),
        'menu_position' => 21,
    ));

    // Portfolio (Projects, Case Studies)
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => 'Portfolio',
            'singular_name' => 'Project',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
            'all_items' => 'All Projects',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-images-alt2',
        'rewrite' => array('slug' => 'portfolio'),
        'menu_position' => 22,
    ));

    // Leads (Quotes - Internal use)
    register_post_type('lead', array(
        'labels' => array(
            'name' => 'Leads',
            'singular_name' => 'Lead',
            'all_items' => 'All Leads',
            'edit_item' => 'View Lead',
        ),
        'public' => false,
        'show_ui' => true,
        'exclude_from_search' => true,
        'supports' => array('title', 'editor'), // Editor used for notes
        'menu_icon' => 'dashicons-email',
        'capabilities' => array(
            'create_posts' => 'do_not_allow', // Created via form only
        ),
        'map_meta_cap' => true,
        'menu_position' => 6, // High up near Posts
    ));
}
add_action('init', 'homad_register_cpts');
