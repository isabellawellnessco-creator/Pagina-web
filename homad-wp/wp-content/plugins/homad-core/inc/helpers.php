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
