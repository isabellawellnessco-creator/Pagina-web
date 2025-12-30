<?php
/**
 * Hook into WooCommerce for special states (Empty Cart, Search No Results).
 */

// 11. SPECIAL STATES (UX INVISIBLE)

// Empty Cart Message
add_action('woocommerce_cart_is_empty', function() {
    echo '<div class="homad-empty-cart-msg" style="text-align: center; margin-top: 20px;">';
    echo '<h3>Tu proyecto está vacío.</h3>';
    echo '<p>¿Por qué no empiezas por aquí?</p>';
    echo '</div>';
}, 10);

// Search No Results (Modify the "No products found" text)
add_filter( 'woocommerce_no_products_found', function() {
    echo '<div class="homad-no-results">';
    echo '<h3>No encontramos esa pieza técnica.</h3>';
    echo '<p>Prueba buscando por categoría: Cocinas, Baños, Escritorios...</p>';
    echo '</div>';
}, 10);
