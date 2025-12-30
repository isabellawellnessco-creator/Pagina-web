<?php
/**
 * Admin Columns customization.
 * Adds "Shopify-like" tracking columns to admin lists.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Leads Columns
 */
add_filter('manage_lead_posts_columns', 'homad_lead_columns');
function homad_lead_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => 'Lead Name',
        'lead_status' => 'Status',
        'lead_budget' => 'Budget',
        'lead_contact' => 'Contact',
        'date' => 'Date Submitted'
    );
    return $columns;
}

add_action('manage_lead_posts_custom_column', 'homad_lead_custom_column', 10, 2);
function homad_lead_custom_column($column, $post_id) {
    switch ($column) {
        case 'lead_status':
            $status = get_post_meta($post_id, '_homad_lead_status', true);
            $colors = [
                'new' => '#d63638',
                'contacted' => '#e5e500',
                'qualified' => '#00a32a',
                'won' => '#0073aa',
                'lost' => '#666'
            ];
            $color = isset($colors[$status]) ? $colors[$status] : '#ccc';
            echo '<span style="background:'.esc_attr($color).'; color:#fff; padding:4px 8px; border-radius:4px; font-weight:bold; font-size:11px; text-transform:uppercase;">' . esc_html($status ? $status : 'new') . '</span>';
            break;
        case 'lead_budget':
            echo esc_html(get_post_meta($post_id, '_homad_lead_budget', true));
            break;
        case 'lead_contact':
            echo '<a href="mailto:'.esc_attr(get_post_meta($post_id, '_homad_lead_email', true)).'">' . esc_html(get_post_meta($post_id, '_homad_lead_email', true)) . '</a><br>';
            echo esc_html(get_post_meta($post_id, '_homad_lead_phone', true));
            break;
    }
}

/**
 * 2. Product Columns (ETA & Installation)
 */
add_filter('manage_product_posts_columns', 'homad_product_columns');
function homad_product_columns($columns) {
    // Insert after price
    $new_columns = [];
    foreach($columns as $key => $val) {
        $new_columns[$key] = $val;
        if($key === 'price') {
            $new_columns['homad_eta'] = 'ETA (Days)';
            $new_columns['homad_install'] = 'Install?';
        }
    }
    return $new_columns;
}

add_action('manage_product_posts_custom_column', 'homad_product_custom_column', 10, 2);
function homad_product_custom_column($column, $post_id) {
    if ($column === 'homad_eta') {
        $min = get_post_meta($post_id, '_homad_eta_min', true);
        $max = get_post_meta($post_id, '_homad_eta_max', true);
        if ($min || $max) echo esc_html("$min - $max");
        else echo '<span style="color:#ccc;">—</span>';
    }
    if ($column === 'homad_install') {
        $inst = get_post_meta($post_id, '_homad_install_avail', true);
        echo ($inst === 'yes') ? '<span class="dashicons dashicons-yes" style="color:green"></span>' : '';
    }
}

/**
 * 3. Make Columns Sortable (Optional enhancement)
 */
add_filter('manage_edit-lead_sortable_columns', 'homad_lead_sortable_columns');
function homad_lead_sortable_columns($columns) {
    $columns['lead_status'] = 'lead_status';
    return $columns;
}
