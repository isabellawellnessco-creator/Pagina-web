<?php
/**
 * Form Handling and AJAX Logic.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

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
