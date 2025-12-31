<?php
/**
 * Add Print Button to View Order Page.
 */
add_action( 'woocommerce_view_order', 'homad_add_print_order_button', 5 );

function homad_add_print_order_button( $order_id ) {
    ?>
    <div class="homad-print-actions" style="margin-bottom: 20px; text-align: right;">
        <button class="button" onclick="window.print();">
            <span class="dashicons dashicons-printer"></span> Print Order / Invoice
        </button>
    </div>
    <?php
}
