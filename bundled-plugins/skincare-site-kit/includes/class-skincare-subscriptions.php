<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Subscriptions {

    public function __construct() {
        // Product Data Tab
        add_filter( 'product_type_options', [ $this, 'add_subscription_option' ] );
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_subscription_option' ] );

        // Frontend Display
        add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'render_subscription_toggle' ] );

        // Cart/Checkout handling
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_subscription_data' ], 10, 2 );
        add_filter( 'woocommerce_get_item_data', [ $this, 'display_subscription_data' ], 10, 2 );
    }

    public function add_subscription_option( $options ) {
        $options['sk_subscription'] = [
            'id'            => '_sk_subscription',
            'wrapper_class' => 'show_if_simple',
            'label'         => __( 'Producto de Suscripción', 'skincare-site-kit' ),
            'description'   => __( 'Habilitar compra recurrente para este producto.', 'skincare-site-kit' ),
            'default'       => 'no',
        ];
        return $options;
    }

    public function save_subscription_option( $post_id ) {
        $is_sub = isset( $_POST['_sk_subscription'] ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_sk_subscription', $is_sub );
    }

    public function render_subscription_toggle() {
        global $product;
        if ( 'yes' !== get_post_meta( $product->get_id(), '_sk_subscription', true ) ) return;

        ?>
        <div class="sk-subscription-option" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
            <label style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px;">
                <input type="radio" name="sk_purchase_type" value="one-time" checked>
                <span style="margin-left: 10px; font-weight: 600;">Compra única</span>
            </label>
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="radio" name="sk_purchase_type" value="subscription">
                <span style="margin-left: 10px; font-weight: 600;">Suscríbete y Ahorra (10%)</span>
            </label>
            <p style="margin: 5px 0 0 25px; font-size: 0.85rem; color: #666;">Entrega cada 30 días. Cancela cuando quieras.</p>
        </div>
        <?php
    }

    public function add_subscription_data( $cart_item_data, $product_id ) {
        if ( isset( $_POST['sk_purchase_type'] ) && 'subscription' === $_POST['sk_purchase_type'] ) {
            $cart_item_data['sk_is_subscription'] = true;
            // Apply 10% discount logic would go here via 'woocommerce_cart_calculate_fees' or modifying price
        }
        return $cart_item_data;
    }

    public function display_subscription_data( $item_data, $cart_item ) {
        if ( isset( $cart_item['sk_is_subscription'] ) ) {
            $item_data[] = [
                'key'     => 'Plan',
                'value'   => 'Suscripción Mensual',
                'display' => 'Suscripción Mensual',
            ];
        }
        return $item_data;
    }
}
// Init in main file
