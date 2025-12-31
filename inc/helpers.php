<?php
/**
 * Helpers del theme y Core (Merged).
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

/**
 * Check if WooCommerce is active.
 */
function homad_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Safe wrapper for is_cart().
 */
function homad_is_cart() {
    return function_exists('is_cart') && is_cart();
}

/**
 * Safe wrapper for is_account_page().
 */
function homad_is_account_page() {
    return function_exists('is_account_page') && is_account_page();
}

/**
 * Safe wrapper for wc_get_cart_url().
 */
function homad_get_cart_url() {
    return function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart');
}

/**
 * Check if Elementor is active.
 */
function homad_is_elementor_active() {
    return did_action('elementor/loaded') || defined('ELEMENTOR_VERSION');
}

/**
 * Get core asset URL.
 * Replaces the old plugin's asset URL logic.
 */
function homad_get_asset_url($path) {
    return get_stylesheet_directory_uri() . '/assets/core/' . $path;
}

/**
 * Devuelve el slug actual si es una página singular.
 */
function homad_get_current_slug() {
    if (is_singular()) {
        global $post;
        if ($post && !empty($post->post_name)) {
            return sanitize_title($post->post_name);
        }
    }
    return '';
}

/**
 * Inject Custom CSS Variables from Admin Settings.
 * Hooked to wp_head.
 */
function homad_inject_custom_vars() {
    $primary = get_option('homad_primary_color', '#333333');
    $accent  = get_option('homad_accent_color', '#65d5a3');
    ?>
    <style id="homad-custom-vars">
        :root {
            --homad-primary: <?php echo esc_attr($primary); ?>;
            --homad-accent: <?php echo esc_attr($accent); ?>;
            /* Derived colors can be added here */
        }
    </style>
    <?php
}
add_action('wp_head', 'homad_inject_custom_vars', 5);

/**
 * Helper para clases estándar de sección (para Elementor).
 */
function homad_section_class($extra = '') {
    $base = 'homad-section';
    $extra = trim((string) $extra);
    return $extra ? $base . ' ' . $extra : $base;
}

/* -------------------------------------------------------------------------- */
/*                                CORE LOGIC                                  */
/* -------------------------------------------------------------------------- */

/**
 * Safe wrapper for is_shop().
 */
function homad_is_shop() {
    return function_exists('is_shop') && is_shop();
}

/**
 * Safe wrapper for is_product_category().
 */
function homad_is_product_category() {
    return function_exists('is_product_category') && is_product_category();
}

/**
 * Safe wrapper for is_product().
 */
function homad_is_product() {
    return function_exists('is_product') && is_product();
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
