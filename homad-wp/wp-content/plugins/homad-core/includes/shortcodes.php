<?php
/**
 * Shortcodes.
 */

function homad_core_render_template($template) {
    $path = HOMAD_CORE_DIR . 'templates/' . $template;
    if (!file_exists($path)) {
        return '';
    }

    ob_start();
    include $path;
    return ob_get_clean();
}

function homad_core_quote_form_shortcode() {
    return homad_core_render_template('quote-form.php');
}
add_shortcode('homad_quote_form', 'homad_core_quote_form_shortcode');

function homad_core_services_snapshot_shortcode() {
    return '<section class="homad-services-shortcode">' . esc_html__('TODO: Services snapshot shortcode placeholder.', 'homad') . '</section>';
}
add_shortcode('homad_services_snapshot', 'homad_core_services_snapshot_shortcode');

function homad_core_packages_snapshot_shortcode() {
    return '<section class="homad-packages-shortcode">' . esc_html__('TODO: Packages snapshot shortcode placeholder.', 'homad') . '</section>';
}
add_shortcode('homad_packages_snapshot', 'homad_core_packages_snapshot_shortcode');

function homad_core_proof_mini_shortcode() {
    return '<section class="homad-proof-shortcode">' . esc_html__('TODO: Proof mini shortcode placeholder.', 'homad') . '</section>';
}
add_shortcode('homad_proof_mini', 'homad_core_proof_mini_shortcode');
