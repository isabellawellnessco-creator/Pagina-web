<?php
/**
 * REST API routes for Homad frontend flows.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
    register_rest_route('homad/v1', '/lead', [
        'methods'             => 'POST',
        'callback'            => 'homad_rest_submit_lead',
        'permission_callback' => 'homad_rest_verify_lead_nonce',
    ]);
});

function homad_rest_verify_lead_nonce(WP_REST_Request $request) {
    $nonce = $request->get_header('X-Homad-Nonce');
    if (!$nonce) {
        $nonce = $request->get_param('nonce');
    }

    return homad_verify_lead_nonce($nonce);
}

function homad_rest_submit_lead(WP_REST_Request $request) {
    $payload = $request->get_json_params();
    if (empty($payload)) {
        $payload = $request->get_body_params();
    }

    $result = homad_process_lead_submission($payload);
    if (is_wp_error($result)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => $result->get_error_message(),
        ], 400);
    }

    return new WP_REST_Response([
        'success' => true,
        'message' => $result['message'],
        'lead_id' => $result['lead_id'],
    ]);
}
