<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Custom Cart Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'sk_custom_cart_button_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'sk_custom_cart_button_text' ); // Loop button

function sk_custom_cart_button_text() {
    return __( 'Agregar al carrito', 'skincare' );
}

// Ensure Spanish strings for common Woo elements
add_filter( 'gettext', 'sk_translate_woo_strings', 20, 3 );
function sk_translate_woo_strings( $translated_text, $text, $domain ) {

    // Broad translation map for common English terms in Woo/Theme
    $translations = [
        'Cart' => 'Carrito',
        'Checkout' => 'Finalizar Compra',
        'Apply coupon' => 'Aplicar cupón',
        'Coupon code' => 'Código de cupón',
        'Update cart' => 'Actualizar carrito',
        'Proceed to checkout' => 'Ir a Pagar',
        'Billing details' => 'Detalles de Facturación',
        'Your order' => 'Tu Pedido',
        'Place order' => 'Realizar Pedido',
        'Total' => 'Total',
        'Subtotal' => 'Subtotal',
        'Shipping' => 'Envío',
        'Flat rate' => 'Tarifa plana',
        'Local pickup' => 'Recojo en tienda',
        'Description' => 'Descripción',
        'Reviews' => 'Reseñas',
        'Related products' => 'Productos Relacionados',
        'Add to wishlist' => 'Añadir a favoritos',
        'Read more' => 'Leer más',
        'Select options' => 'Seleccionar opciones',
        'Sale!' => '¡Oferta!',
        'Out of stock' => 'Agotado',
        'View cart' => 'Ver carrito',
        'Order notes' => 'Notas del pedido',
        'Have a coupon?' => '¿Tienes un cupón?',
        'Click here to enter your code' => 'Haz clic aquí para introducir tu código',
    ];

    if ( isset( $translations[$text] ) ) {
        return $translations[$text];
    }

    return $translated_text;
}

// Remove sidebar from all Woo pages (Full width consistency)
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Customize Billing Fields (Optional - simplify for 'Skin Cupid' feel)
add_filter( 'woocommerce_checkout_fields' , 'sk_custom_checkout_fields' );
function sk_custom_checkout_fields( $fields ) {
    // Modify labels if needed
    $fields['billing']['billing_first_name']['label'] = 'Nombre';
    $fields['billing']['billing_last_name']['label'] = 'Apellidos';
    return $fields;
}
