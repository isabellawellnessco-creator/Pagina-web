<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Stock_Notifier {

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'wp_ajax_sk_stock_notify', [ __CLASS__, 'handle_request' ] );
		add_action( 'wp_ajax_nopriv_sk_stock_notify', [ __CLASS__, 'handle_request' ] );

		// Frontend UI Hook
		add_action( 'woocommerce_single_product_summary', [ __CLASS__, 'render_notification_form' ], 30 );
	}

	public static function register_cpt() {
		register_post_type( 'sk_stock_request', [
			'public' => false,
			'show_ui' => true,
			'label' => 'Stock Requests',
			'supports' => [ 'title', 'custom-fields' ],
			'capabilities' => [
				'create_posts' => 'do_not_allow', // Only programmatic creation
			],
			'map_meta_cap' => true,
		] );
	}

	public static function render_notification_form() {
		global $product;
		if ( ! $product || $product->is_in_stock() ) {
			return;
		}

		?>
		<div class="sk-stock-notifier-wrapper">
			<h4 class="sk-notifier-title"><?php _e( 'Avísame cuando esté disponible', 'skincare' ); ?></h4>
			<form id="sk-stock-notifier-form" class="sk-notifier-form">
				<input type="email" name="email" placeholder="<?php _e( 'Tu correo electrónico', 'skincare' ); ?>" required>
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
				<button type="submit" class="btn sk-btn-notify"><?php _e( 'Notificarme', 'skincare' ); ?></button>
				<div class="message"></div>
			</form>
		</div>
		<?php
	}

	public static function handle_request() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

		if ( ! is_email( $email ) || ! $product_id ) {
			wp_send_json_error( [ 'message' => __( 'Datos inválidos.', 'skincare' ) ] );
		}

		// Check for duplicate
		$existing = get_posts( [
			'post_type' => 'sk_stock_request',
			'meta_query' => [
				'relation' => 'AND',
				[ 'key' => '_sk_stock_email', 'value' => $email ],
				[ 'key' => '_sk_stock_product_id', 'value' => $product_id ],
			]
		] );

		if ( ! empty( $existing ) ) {
			wp_send_json_error( [ 'message' => __( 'Ya estás suscrito a este producto.', 'skincare' ) ] );
		}

		$post_id = wp_insert_post( [
			'post_type' => 'sk_stock_request',
			'post_title' => $email . ' - ' . get_the_title( $product_id ),
			'post_status' => 'pending',
		] );

		if ( $post_id ) {
			update_post_meta( $post_id, '_sk_stock_email', $email );
			update_post_meta( $post_id, '_sk_stock_product_id', $product_id );
			wp_send_json_success( [ 'message' => __( '¡Te avisaremos cuando vuelva a estar disponible!', 'skincare' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Error al guardar la solicitud.', 'skincare' ) ] );
		}
	}
}
