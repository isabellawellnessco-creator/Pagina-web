<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Custom Cart Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'sk_custom_cart_button_text' );
function sk_custom_cart_button_text() {
    return __( 'Agregar al carrito', 'skincare' );
}

// Ensure Spanish strings for common Woo elements
add_filter( 'gettext', 'sk_translate_woo_strings', 20, 3 );
function sk_translate_woo_strings( $translated_text, $text, $domain ) {
    if ( $domain == 'woocommerce' ) {
        switch ( $translated_text ) {
            case 'Cart':
                $translated_text = 'Carrito';
                break;
            case 'Checkout':
                $translated_text = 'Finalizar Compra';
                break;
            case 'Apply coupon':
                $translated_text = 'Aplicar cupón';
                break;
        }
    }
    return $translated_text;
}

// Remove sidebar from all Woo pages (Full width consistency)
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
