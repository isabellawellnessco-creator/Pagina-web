<?php
/**
 * WooCommerce hooks and tweaks.
 */

function homad_child_woocommerce_placeholder_notice() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    // TODO: Add WooCommerce-specific tweaks when ready.
}
add_action('wp', 'homad_child_woocommerce_placeholder_notice');
