<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards {

	public static function init() {
		// Award points on purchase
		add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'award_points' ] );

		// AJAX Redeem
		add_action( 'wp_ajax_sk_redeem_points', [ __CLASS__, 'ajax_redeem_points' ] );

		// Shortcode
		add_shortcode( 'sk_user_points', [ __CLASS__, 'shortcode_user_points' ] );

		// Admin Menu
		add_action( 'admin_menu', [ __CLASS__, 'register_admin_page' ] );

		// Admin AJAX
		add_action( 'wp_ajax_sk_admin_adjust_points', [ __CLASS__, 'ajax_admin_adjust_points' ] );
	}

	public static function register_admin_page() {
		add_menu_page(
			__( 'Rewards System', 'skincare' ),
			__( 'Rewards', 'skincare' ),
			'manage_options',
			'sk-rewards-system',
			[ __CLASS__, 'render_admin_page' ],
			'dashicons-awards',
			56
		);
	}

	public static function render_admin_page() {
		$users = get_users( [ 'meta_key' => '_sk_rewards_points', 'orderby' => 'meta_value_num', 'order' => 'DESC' ] );
		?>
		<div class="wrap">
			<h1><?php _e( 'Skin Cupid Rewards Management', 'skincare' ); ?></h1>
			<p><?php _e( 'Gestiona los puntos de los usuarios y verifica el historial de transacciones.', 'skincare' ); ?></p>

			<div style="background: #fff; padding: 20px; margin-top: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
				<h2><?php _e( 'Usuarios con Puntos', 'skincare' ); ?></h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php _e( 'Usuario', 'skincare' ); ?></th>
							<th><?php _e( 'Email', 'skincare' ); ?></th>
							<th><?php _e( 'Puntos Actuales', 'skincare' ); ?></th>
							<th><?php _e( 'Acciones', 'skincare' ); ?></th>
							<th><?php _e( 'Historial Reciente', 'skincare' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $users ) ) : ?>
							<tr><td colspan="5"><?php _e( 'No se encontraron usuarios con puntos.', 'skincare' ); ?></td></tr>
						<?php else : ?>
							<?php foreach ( $users as $user ) :
								$points = get_user_meta( $user->ID, '_sk_rewards_points', true );
								$history = get_user_meta( $user->ID, '_sk_rewards_history', true );
								$last_txn = ! empty( $history ) ? end( $history ) : null;
								?>
								<tr>
									<td>
										<strong><?php echo esc_html( $user->display_name ); ?></strong><br>
										<span class="description">ID: <?php echo $user->ID; ?></span>
									</td>
									<td><?php echo esc_html( $user->user_email ); ?></td>
									<td>
										<span id="points-display-<?php echo $user->ID; ?>" style="font-size: 16px; font-weight: bold; color: #0F3062;">
											<?php echo intval( $points ); ?>
										</span>
									</td>
									<td>
										<button class="button sk-adjust-points-btn" data-user-id="<?php echo $user->ID; ?>" data-current="<?php echo intval( $points ); ?>">
											<?php _e( 'Ajustar Puntos', 'skincare' ); ?>
										</button>
									</td>
									<td>
										<?php if ( $last_txn ) : ?>
											<?php echo esc_html( $last_txn['date'] ); ?>:
											<strong><?php echo $last_txn['points'] > 0 ? '+' : ''; ?><?php echo esc_html( $last_txn['points'] ); ?></strong>
											(<?php echo esc_html( $last_txn['reason'] ); ?>)
										<?php else : ?>
											-
										<?php endif; ?>
										<br>
										<a href="#" class="sk-view-history" data-user-id="<?php echo $user->ID; ?>"><?php _e( 'Ver todo', 'skincare' ); ?></a>
										<div id="history-modal-<?php echo $user->ID; ?>" style="display:none;">
											<pre><?php print_r( $history ); ?></pre>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Adjust Points Modal (Simple Prompt for MVP) -->
		<script>
		jQuery(document).ready(function($) {
			$('.sk-adjust-points-btn').click(function() {
				var uid = $(this).data('user-id');
				var current = $(this).data('current');
				var change = prompt("Ingresa la cantidad de puntos a añadir (ej. 100) o restar (ej. -50):");

				if (change != null && parseInt(change) != 0) {
					var reason = prompt("Razón del ajuste (ej. Corrección manual, Regalo):", "Ajuste Manual Admin");

					$.post(ajaxurl, {
						action: 'sk_admin_adjust_points',
						user_id: uid,
						points: change,
						reason: reason
					}, function(res) {
						if(res.success) {
							alert('Puntos actualizados. Nuevo saldo: ' + res.data.new_balance);
							$('#points-display-' + uid).text(res.data.new_balance);
							location.reload();
						} else {
							alert('Error: ' + res.data.message);
						}
					});
				}
			});

			$('.sk-view-history').click(function(e) {
				e.preventDefault();
				var uid = $(this).data('user-id');
				var content = $('#history-modal-' + uid).html();
				// Very basic modal for admin
				var w = window.open("", "History", "width=600,height=400");
				w.document.write("<h3>Historial de Transacciones</h3>" + content);
			});
		});
		</script>
		<?php
	}

	public static function ajax_admin_adjust_points() {
		if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( [ 'message' => 'Unauthorized' ] );

		$user_id = intval( $_POST['user_id'] );
		$points_change = intval( $_POST['points'] );
		$reason = sanitize_text_field( $_POST['reason'] );

		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );
		$new_points = $current_points + $points_change;

		update_user_meta( $user_id, '_sk_rewards_points', $new_points );

		// Log
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];
		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => $points_change,
			'reason' => $reason . ' (Admin)'
		];
		update_user_meta( $user_id, '_sk_rewards_history', $history );

		wp_send_json_success( [ 'new_balance' => $new_points ] );
	}

	public static function award_points( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) return;

		$user_id = $order->get_user_id();
		if ( ! $user_id ) return;

		// Check if already awarded to avoid duplicates
		$already_awarded = get_post_meta( $order_id, '_sk_points_awarded', true );
		if ( $already_awarded ) return;

		$total = $order->get_total();
		$points = floor( $total ) * 5; // 5 points per £1

		$current_points = get_user_meta( $user_id, '_sk_rewards_points', true );
		$current_points = $current_points ? intval( $current_points ) : 0;

		$new_points = $current_points + $points;

		update_user_meta( $user_id, '_sk_rewards_points', $new_points );
		update_post_meta( $order_id, '_sk_points_awarded', $points );

		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => $points,
			'reason' => 'Compra Pedido #' . $order_id
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
	}

	public static function ajax_redeem_points() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) wp_send_json_error( [ 'message' => 'Debes iniciar sesión para canjear puntos.' ] );

		$points_to_redeem = isset( $_POST['points_cost'] ) ? intval( $_POST['points_cost'] ) : 500;
		$discount_amount = isset( $_POST['discount_amount'] ) ? intval( $_POST['discount_amount'] ) : 5; // Fallback or dynamic based on catalog logic

		// Security: In a real app, map points to amounts on server side to avoid tampering.
		// For this template, we map points to amount simply: 100 points = £1.
		$calculated_discount = floor( $points_to_redeem / 100 );

		// Override with widget data if passed securely, otherwise strict map
		// Let's stick to the 100pts = £1 rule for consistency

		$user_id = get_current_user_id();
		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );

		if ( $current_points < $points_to_redeem ) {
			wp_send_json_error( [ 'message' => 'No tienes suficientes puntos para este canje.' ] );
		}

		// Deduct
		update_user_meta( $user_id, '_sk_rewards_points', $current_points - $points_to_redeem );

		// Create Coupon
		$coupon_code = 'SK-' . strtoupper( wp_generate_password( 6, false ) );
		$coupon = new \WC_Coupon();
		$coupon->set_code( $coupon_code );
		$coupon->set_amount( $calculated_discount );
		$coupon->set_discount_type( 'fixed_cart' );
		$coupon->set_description( 'Canjeado por ' . $points_to_redeem . ' puntos.' );
		$coupon->set_usage_limit( 1 );
		$coupon->set_usage_limit_per_user( 1 );
		$coupon->save();

		// Log History
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];
		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => -$points_to_redeem,
			'reason' => 'Canje: Cupón £' . $calculated_discount
		];
		update_user_meta( $user_id, '_sk_rewards_history', $history );

		wp_send_json_success( [
			'message' => '¡Canje exitoso!',
			'coupon_code' => $coupon_code,
			'discount_amount' => $calculated_discount,
			'new_balance' => $current_points - $points_to_redeem
		] );
	}

	public static function shortcode_user_points() {
		if ( ! is_user_logged_in() ) return '0';
		$points = get_user_meta( get_current_user_id(), '_sk_rewards_points', true );
		return intval( $points );
	}
}
