<?php
/**
 * Helper Functions.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active.
 */
function homad_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Get asset URL (for future use).
 */
function homad_get_asset_url($path) {
    return plugins_url('assets/' . $path, dirname(__DIR__));
}

/**
 * Handle Custom Price for Configurator.
 * Listens for 'homad_config_price' in cart data.
 */
add_action('woocommerce_before_calculate_totals', 'homad_add_custom_price_to_cart');
function homad_add_custom_price_to_cart($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        // If we have custom configuration data
        if (isset($cart_item['homad_config_total'])) {
            $base_price = $cart_item['data']->get_price();
            $extra_cost = floatval($cart_item['homad_config_total']);

            // Set new price
            $cart_item['data']->set_price($base_price + $extra_cost);
        }
    }
}

/**
 * Add Custom Data to Cart Item.
 * This filters the data added to the cart when "Add to Cart" is clicked.
 */
add_filter('woocommerce_add_cart_item_data', 'homad_add_cart_item_data', 10, 3);
function homad_add_cart_item_data($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['homad_config_total_input'])) {
        $cart_item_data['homad_config_total'] = floatval($_POST['homad_config_total_input']);

        // Save detail of selected layers for display
        if (isset($_POST['homad_selected_layers'])) {
             // sanitize array? json string?
             $cart_item_data['homad_selected_layers'] = sanitize_text_field($_POST['homad_selected_layers']);
        }
    }
    return $cart_item_data;
}

/**
 * Display Custom Data in Cart & Checkout.
 */
add_filter('woocommerce_get_item_data', 'homad_display_cart_item_data', 10, 2);
function homad_display_cart_item_data($item_data, $cart_item) {
    if (isset($cart_item['homad_selected_layers'])) {
        // Parse the layers (stored as "Group: Name, Group: Name")
        $layers = explode(',', $cart_item['homad_selected_layers']);
        foreach ($layers as $layer) {
             $item_data[] = [
                 'key'   => 'Configuration',
                 'value' => trim($layer),
             ];
        }
    }
    return $item_data;
}
