<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Stock_Notifier {

    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt' ] );
        add_action( 'woocommerce_single_product_summary', [ $this, 'render_notification_form' ], 35 );
        add_action( 'wp_ajax_skincare_stock_notify', [ $this, 'handle_ajax_submission' ] );
        add_action( 'wp_ajax_nopriv_skincare_stock_notify', [ $this, 'handle_ajax_submission' ] );
    }

    public function register_cpt() {
        register_post_type( 'sk_stock_request', [
            'labels' => [
                'name' => 'Stock Requests',
                'singular_name' => 'Stock Request',
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => [ 'title', 'custom-fields' ],
            'capability_type' => 'post',
        ]);
    }

    public function render_notification_form() {
        global $product;

        if ( ! $product || $product->is_in_stock() ) {
            return;
        }

        ?>
        <div class="sk-stock-notify-wrapper">
            <h4 class="sk-notify-title"><?php _e('Notifícame cuando esté disponible', 'skincare-site-kit'); ?></h4>
            <form id="sk-stock-notify-form">
                <input type="email" name="email" placeholder="<?php _e('Introduce tu correo electrónico', 'skincare-site-kit'); ?>" required class="sk-notify-input">
                <input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <button type="submit" class="btn btn-secondary sk-notify-btn"><?php _e('Notifícame', 'skincare-site-kit'); ?></button>
                <div class="sk-notify-message"></div>
            </form>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('#sk-stock-notify-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $msg = $form.find('.sk-notify-message');

                $.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    type: 'POST',
                    data: {
                        action: 'skincare_stock_notify',
                        email: $form.find('input[name="email"]').val(),
                        product_id: $form.find('input[name="product_id"]').val()
                    },
                    success: function(response) {
                        if(response.success) {
                            $msg.css('color', 'green').text(response.data);
                            $form.find('input, button').prop('disabled', true);
                        } else {
                            $msg.css('color', 'red').text(response.data);
                        }
                    }
                });
            });
        });
        </script>
        <style>
            .sk-stock-notify-wrapper {
                margin-top: 20px;
                padding: 20px;
                background-color: var(--c-bg-light);
                border: 1px solid var(--c-border);
                border-radius: var(--radius-md);
            }
            .sk-notify-title { margin-top: 0; font-family: var(--font-family-heading); }
            .sk-notify-input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: var(--radius-sm); }
            .sk-notify-btn { width: 100%; }
            .sk-notify-message { margin-top: 10px; font-size: 0.9rem; }
        </style>
        <?php
    }

    public function handle_ajax_submission() {
        $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

        if ( ! is_email( $email ) || ! $product_id ) {
            wp_send_json_error( __('Datos inválidos.', 'skincare-site-kit') );
        }

        // Save Request
        $post_id = wp_insert_post([
            'post_title' => $email . ' - Product ID: ' . $product_id,
            'post_type' => 'sk_stock_request',
            'post_status' => 'private',
        ]);

        if ( $post_id ) {
            update_post_meta( $post_id, '_sk_request_email', $email );
            update_post_meta( $post_id, '_sk_request_product_id', $product_id );
            wp_send_json_success( __('¡Gracias! Te avisaremos cuando vuelva a estar disponible.', 'skincare-site-kit') );
        } else {
            wp_send_json_error( __('Error al guardar la solicitud.', 'skincare-site-kit') );
        }
    }
}

new Skincare_Stock_Notifier();
