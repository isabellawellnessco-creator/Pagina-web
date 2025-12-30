<?php
/**
 * Form Handlers (AJAX).
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

add_action('wp_ajax_homad_submit_lead', 'homad_handle_lead_submission');
add_action('wp_ajax_nopriv_homad_submit_lead', 'homad_handle_lead_submission');

function homad_handle_lead_submission() {
    // 1. Verify Nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'homad_lead_action')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh.']);
    }

    // 2. Sanitize Input
    $service_type = sanitize_text_field($_POST['service_type'] ?? '');
    $email        = sanitize_email($_POST['email'] ?? '');
    $phone        = sanitize_text_field($_POST['phone'] ?? '');
    $budget       = sanitize_text_field($_POST['budget'] ?? '');
    $details      = sanitize_textarea_field($_POST['details'] ?? '');
    $interest_type = sanitize_text_field($_POST['interest_type'] ?? ''); // service/package/b2b
    $interest_name = sanitize_text_field($_POST['interest_name'] ?? ''); // "Kitchen Package", etc.

    if (!$email) {
        wp_send_json_error(['message' => 'Email is required.']);
    }

    // 3. Create Lead Post
    $post_title = "Lead: $email ($service_type)";
    if($interest_name) $post_title .= " - $interest_name";

    $lead_id = wp_insert_post([
        'post_type'   => 'lead',
        'post_title'  => $post_title,
        'post_status' => 'publish', // Internal use only
        'post_content'=> $details,  // Store notes in content
    ]);

    if (is_wp_error($lead_id)) {
        wp_send_json_error(['message' => 'Could not save lead.']);
    }

    // 4. Save Meta Data
    update_post_meta($lead_id, '_homad_lead_email', $email);
    update_post_meta($lead_id, '_homad_lead_phone', $phone);
    update_post_meta($lead_id, '_homad_lead_budget', $budget);
    update_post_meta($lead_id, '_homad_lead_status', 'new');

    // 5. Send Notification (Optional - simplified for MVP)
    // wp_mail(get_option('admin_email'), "New Lead: $post_title", "Details:\n\n$details\n\nContact: $email / $phone");

    wp_send_json_success(['message' => 'Quote request received! We will contact you shortly.']);
}
