<?php
/**
 * Form Handlers (AJAX).
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

add_action('wp_ajax_homad_submit_lead', 'homad_handle_lead_submission');
add_action('wp_ajax_nopriv_homad_submit_lead', 'homad_handle_lead_submission');

function homad_verify_lead_nonce($nonce) {
    if (!$nonce) {
        return false;
    }

    return wp_verify_nonce($nonce, 'homad_lead_action');
}

function homad_process_lead_submission($raw_data) {
    $email = '';
    $phone = '';
    $budget = '';
    $service_type = '';
    $submission_data = [];

    foreach ($raw_data as $key => $value) {
        if (in_array($key, ['action', 'homad_form_nonce', 'security', 'nonce'], true)) {
            continue;
        }

        $clean_value = is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
        $submission_data[$key] = $clean_value;

        if (strpos($key, 'email') !== false) {
            $email = sanitize_email($value);
        }
        if (strpos($key, 'phone') !== false) {
            $phone = sanitize_text_field($value);
        }
        if (strpos($key, 'budget') !== false) {
            $budget = sanitize_text_field($value);
        }
        if (strpos($key, 'service') !== false) {
            $service_type = sanitize_text_field($value);
        }
    }

    if (!$email) {
        return new WP_Error('homad_lead_email', 'Email is required.');
    }

    $post_title = "Lead: $email" . ($service_type ? " ($service_type)" : "");
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
        return new WP_Error('homad_lead_save', 'Could not save lead.');
    }

    update_post_meta($lead_id, '_homad_lead_email', $email);
    update_post_meta($lead_id, '_homad_lead_phone', $phone);
    update_post_meta($lead_id, '_homad_lead_budget', $budget);
    update_post_meta($lead_id, '_homad_lead_status', 'new');
    update_post_meta($lead_id, '_homad_lead_full_data', $submission_data);

    return [
        'message' => 'Quote request received! We will contact you shortly.',
        'lead_id' => $lead_id,
    ];
}

function homad_handle_lead_submission() {
    $nonce = '';
    if (isset($_POST['homad_form_nonce'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['homad_form_nonce']));
    } elseif (isset($_POST['security'])) {
        $nonce = sanitize_text_field(wp_unslash($_POST['security']));
    }

    if (!homad_verify_lead_nonce($nonce)) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh.']);
    }

    $result = homad_process_lead_submission($_POST);
    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    }

    wp_send_json_success($result);
}
