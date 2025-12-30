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

    // Services
    register_post_type('service', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true, // Required for Block Editor / Elementor
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-hammer',
        'rewrite' => array('slug' => 'service'),
    ));

    // Packages
    register_post_type('package', array(
        'labels' => array(
            'name' => 'Packages',
            'singular_name' => 'Package',
            'add_new_item' => 'Add New Package',
            'edit_item' => 'Edit Package',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-box',
        'rewrite' => array('slug' => 'package'),
    ));

    // Portfolio
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => 'Portfolio',
            'singular_name' => 'Project',
            'add_new_item' => 'Add New Project',
            'edit_item' => 'Edit Project',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-images-alt2',
        'rewrite' => array('slug' => 'portfolio'),
    ));

    // Leads (Internal use)
    register_post_type('lead', array(
        'labels' => array(
            'name' => 'Leads',
            'singular_name' => 'Lead',
        ),
        'public' => false,  // Not accessible on frontend
        'show_ui' => true,  // Show in Admin
        'exclude_from_search' => true,
        'supports' => array('title', 'custom-fields', 'editor'), // Editor to view details if needed
        'menu_icon' => 'dashicons-email',
        'capabilities' => array(
            'create_posts' => false, // Only created programmatically
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'homad_register_cpts');
