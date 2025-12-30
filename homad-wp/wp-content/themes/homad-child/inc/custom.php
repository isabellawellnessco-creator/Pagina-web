<?php
/**
 * Custom CPTs and Form Handlers.
 *
 * @package homad-child
 */
defined('ABSPATH') || exit;

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

/**
 * AJAX Handler for Quote Wizard.
 * Receives JSON data from the frontend wizard.
 */
add_action('wp_ajax_homad_submit_quote_wizard', 'homad_ajax_submit_quote_wizard');
add_action('wp_ajax_nopriv_homad_submit_quote_wizard', 'homad_ajax_submit_quote_wizard');

function homad_ajax_submit_quote_wizard() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'homad_quote_nonce')) {
        wp_send_json_error(array('message' => 'Invalid security token.'));
    }

    // Parse fields
    $fields = isset($_POST['fields']) ? json_decode(stripslashes($_POST['fields']), true) : array();

    if (empty($fields)) {
        wp_send_json_error(array('message' => 'No data received.'));
    }

    // Extract basic info
    $contact_name = isset($fields['name']) ? sanitize_text_field($fields['name']) : 'Unknown';
    $contact_email = isset($fields['email']) ? sanitize_email($fields['email']) : '';
    $lead_type = isset($fields['quote_type']) ? sanitize_text_field($fields['quote_type']) : 'General'; // B2B, Package, Service

    // Create Lead Post
    $post_id = wp_insert_post(array(
        'post_type'   => 'lead',
        'post_title'  => sprintf('%s Quote: %s', ucfirst($lead_type), $contact_name),
        'post_status' => 'publish',
        'post_content' => 'Lead generated via Quote Wizard.',
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Could not save lead.'));
    }

    // Save Meta Fields
    foreach ($fields as $key => $value) {
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
    }

    // Send Email Notification
    $admin_email = get_option('homad_contact_email', get_option('admin_email'));
    $subject = "New Quote Request ($lead_type) from $contact_name";
    $message = "New lead received:\n\n";
    foreach ($fields as $key => $value) {
        $message .= ucfirst($key) . ": " . (is_array($value) ? implode(', ', $value) : $value) . "\n";
    }

    wp_mail($admin_email, $subject, $message);

    wp_send_json_success(array('message' => 'Quote received successfully!', 'lead_id' => $post_id));
}
