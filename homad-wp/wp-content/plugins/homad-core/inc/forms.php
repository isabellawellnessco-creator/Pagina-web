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
    if (!isset($_POST['homad_form_nonce']) || !wp_verify_nonce($_POST['homad_form_nonce'], 'homad_lead_nonce')) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh.']);
    }

    // 2. Dynamic Input Handling
    // Instead of hardcoded fields, we iterate over POST data and save everything to meta
    $email = '';
    $phone = '';
    $budget = '';
    $service_type = '';

    // Store all submitted fields in an array for Content body
    $submission_data = [];

    foreach ($_POST as $key => $value) {
        if (in_array($key, ['action', 'homad_form_nonce'])) continue;

        $clean_value = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
        $submission_data[$key] = $clean_value;

        // Map known keys to Standard Meta
        if (strpos($key, 'email') !== false) $email = sanitize_email($value);
        if (strpos($key, 'phone') !== false) $phone = sanitize_text_field($value);
        if (strpos($key, 'budget') !== false) $budget = sanitize_text_field($value);
        if (strpos($key, 'service') !== false) $service_type = sanitize_text_field($value);
    }

    if (!$email) {
        wp_send_json_error(['message' => 'Email is required.']);
    }

    // 3. Create Lead Post
    $post_title = "Lead: $email" . ($service_type ? " ($service_type)" : "");

    // Format Content from Data
    $content = "Lead Submission Details:\n\n";
    foreach ($submission_data as $k => $v) {
        $content .= ucfirst(str_replace('_', ' ', $k)) . ": $v\n";
    }

    $lead_id = wp_insert_post([
        'post_type'   => 'lead',
        'post_title'  => $post_title,
        'post_status' => 'publish',
        'post_content'=> $content,
    ]);

    if (is_wp_error($lead_id)) {
        wp_send_json_error(['message' => 'Could not save lead.']);
    }

    // 4. Save Meta Data (Standard CRM fields + Custom JSON blob)
    update_post_meta($lead_id, '_homad_lead_email', $email);
    update_post_meta($lead_id, '_homad_lead_phone', $phone);
    update_post_meta($lead_id, '_homad_lead_budget', $budget);
    update_post_meta($lead_id, '_homad_lead_status', 'new');
    update_post_meta($lead_id, '_homad_lead_full_data', $submission_data);

    // 5. Send Notification (Optional - simplified for MVP)
    // wp_mail(get_option('admin_email'), "New Lead: $post_title", "Details:\n\n$details\n\nContact: $email / $phone");

    wp_send_json_success(['message' => 'Quote request received! We will contact you shortly.']);
}
