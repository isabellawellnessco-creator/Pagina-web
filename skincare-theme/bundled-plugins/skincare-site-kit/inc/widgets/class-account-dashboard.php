<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;

class Account_Dashboard extends Widget_Base {
	public function get_name() { return 'sk_account_dashboard'; }
	public function get_title() { return __( 'Panel de cuenta', 'skincare' ); }
	public function get_icon() { return 'eicon-person'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<div class="sk-login-prompt">';
			echo '<h3>' . __( 'Bienvenido', 'skincare' ) . '</h3>';
			echo '<p>' . __( 'Por favor inicia sesión para ver tu cuenta.', 'skincare' ) . '</p>';
			wp_login_form();
			echo '</div>';
			return;
		}

		$current_user = wp_get_current_user();

		// Get Orders
		$orders = wc_get_orders( [ 'customer' => $current_user->ID, 'limit' => 5 ] );

		// Get Address
		$address = wc_get_account_formatted_address( 'billing' );

		// Get Rewards Data
		$points = get_user_meta( $current_user->ID, '_sk_rewards_points', true );
		$points = $points ? intval( $points ) : 0;
		$rewards_history = get_user_meta( $current_user->ID, '_sk_rewards_history', true );

		?>
		<div class="sk-account-dashboard">
			<div class="sk-account-header">
				<div class="sk-user-info">
					<h2><?php printf( __( 'Hola, %s', 'skincare' ), esc_html( $current_user->display_name ) ); ?></h2>
					<div class="sk-user-points-badge">
						<?php echo esc_html( $points ); ?> <?php _e( 'Puntos', 'skincare' ); ?>
					</div>
				</div>
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="sk-logout-link"><?php _e( 'Cerrar Sesión', 'skincare' ); ?></a>
			</div>

			<div class="sk-account-tabs-wrapper">
				<div class="sk-account-tabs">
					<button class="sk-tab-btn active" data-target="#tab-orders"><?php _e( 'Mis Pedidos', 'skincare' ); ?></button>
					<button class="sk-tab-btn" data-target="#tab-address"><?php _e( 'Direcciones', 'skincare' ); ?></button>
					<button class="sk-tab-btn" data-target="#tab-rewards"><?php _e( 'Mis Puntos', 'skincare' ); ?></button>
				</div>

				<div id="tab-orders" class="sk-tab-pane active">
					<?php if ( empty( $orders ) ) : ?>
						<p><?php _e( 'No has realizado pedidos aún.', 'skincare' ); ?></p>
						<a href="/shop/" class="btn sk-btn"><?php _e( 'Ir a la Tienda', 'skincare' ); ?></a>
					<?php else : ?>
						<div class="sk-orders-grid">
							<?php foreach ( $orders as $order ) : ?>
								<?php
								$order_id = $order->get_id();
								$warehouse = get_post_meta( $order_id, '_sk_warehouse_location', true );
								$packing_status = get_post_meta( $order_id, '_sk_packing_status', true );
								$carrier = get_post_meta( $order_id, '_sk_carrier', true );
								$tracking_number = get_post_meta( $order_id, '_sk_tracking_number', true );
								$tracking_url = get_post_meta( $order_id, '_sk_tracking_url', true );
								$province_shipping = get_post_meta( $order_id, '_sk_province_shipping', true );
								$deposit_paid = get_post_meta( $order_id, '_sk_deposit_paid', true );
								$deposit_amount = get_post_meta( $order_id, '_sk_deposit_amount', true );
								$delivery_agent = get_post_meta( $order_id, '_sk_delivery_agent', true );
								$delivery_phone = get_post_meta( $order_id, '_sk_delivery_phone', true );
								$awarded_points = get_post_meta( $order_id, '_sk_rewards_awarded', true );
								$shipping_method = $order->get_shipping_method();
								$shipping_address = $order->get_formatted_shipping_address();
								$payment_method = $order->get_payment_method_title();
								$is_paid = $order->is_paid();
								?>
								<article class="sk-order-card">
									<header class="sk-order-card__header">
										<div>
											<h4><?php printf( __( 'Pedido #%s', 'skincare' ), esc_html( $order->get_order_number() ) ); ?></h4>
											<p class="sk-order-card__meta">
												<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
												<span class="sk-divider">•</span>
												<?php echo esc_html( $order->get_formatted_order_total() ); ?>
											</p>
										</div>
										<div class="sk-order-card__status">
											<span class="sk-badge sk-badge--status"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
											<?php if ( $packing_status ) : ?>
												<span class="sk-badge sk-badge--neutral"><?php echo esc_html( $packing_status ); ?></span>
											<?php endif; ?>
										</div>
									</header>

									<div class="sk-order-card__sections">
										<section>
											<h5><?php _e( 'Pago', 'skincare' ); ?></h5>
											<ul>
												<li>
													<strong><?php _e( 'Estado:', 'skincare' ); ?></strong>
													<span class="<?php echo $is_paid ? 'sk-text-success' : 'sk-text-warning'; ?>">
														<?php echo $is_paid ? esc_html__( 'Pagado', 'skincare' ) : esc_html__( 'Pendiente', 'skincare' ); ?>
													</span>
												</li>
												<li><strong><?php _e( 'Método:', 'skincare' ); ?></strong> <?php echo esc_html( $payment_method ? $payment_method : __( 'No definido', 'skincare' ) ); ?></li>
												<li>
													<strong><?php _e( 'Depósito adelanto:', 'skincare' ); ?></strong>
													<?php if ( $province_shipping ) : ?>
														<?php echo $deposit_paid ? esc_html__( 'Recibido', 'skincare' ) : esc_html__( 'Pendiente', 'skincare' ); ?>
														<?php if ( $deposit_amount ) : ?>
															(<?php echo esc_html( $deposit_amount ); ?>)
														<?php endif; ?>
													<?php else : ?>
														<?php esc_html_e( 'No aplica', 'skincare' ); ?>
													<?php endif; ?>
												</li>
											</ul>
										</section>

										<section>
											<h5><?php _e( 'Envío', 'skincare' ); ?></h5>
											<ul>
												<li><strong><?php _e( 'Método:', 'skincare' ); ?></strong> <?php echo esc_html( $shipping_method ? $shipping_method : __( 'Sin método', 'skincare' ) ); ?></li>
												<li><strong><?php _e( 'Provincia:', 'skincare' ); ?></strong> <?php echo $province_shipping ? esc_html__( 'Sí', 'skincare' ) : esc_html__( 'No', 'skincare' ); ?></li>
												<li><strong><?php _e( 'Lugar:', 'skincare' ); ?></strong> <?php echo $shipping_address ? wp_kses_post( $shipping_address ) : esc_html__( 'Sin dirección', 'skincare' ); ?></li>
												<li><strong><?php _e( 'Enviado desde:', 'skincare' ); ?></strong> <?php echo $warehouse ? esc_html( $warehouse ) : esc_html__( 'Por confirmar', 'skincare' ); ?></li>
												<li><strong><?php _e( 'Envío personal:', 'skincare' ); ?></strong> <?php echo $delivery_agent ? esc_html( $delivery_agent ) : esc_html__( 'No asignado', 'skincare' ); ?></li>
												<?php if ( $delivery_phone ) : ?>
													<li><strong><?php _e( 'Contacto:', 'skincare' ); ?></strong> <?php echo esc_html( $delivery_phone ); ?></li>
												<?php endif; ?>
											</ul>
										</section>

										<section>
											<h5><?php _e( 'Tracking', 'skincare' ); ?></h5>
											<ul>
												<li><strong><?php _e( 'Transportista:', 'skincare' ); ?></strong> <?php echo $carrier ? esc_html( $carrier ) : esc_html__( 'Por asignar', 'skincare' ); ?></li>
												<li><strong><?php _e( 'Código de envío:', 'skincare' ); ?></strong> <?php echo $tracking_number ? esc_html( $tracking_number ) : esc_html__( 'Sin código', 'skincare' ); ?></li>
												<li>
													<strong><?php _e( 'Seguimiento:', 'skincare' ); ?></strong>
													<?php if ( $tracking_url ) : ?>
														<a href="<?php echo esc_url( $tracking_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ver tracking', 'skincare' ); ?></a>
													<?php else : ?>
														<?php esc_html_e( 'No disponible', 'skincare' ); ?>
													<?php endif; ?>
												</li>
											</ul>
										</section>

										<section>
											<h5><?php _e( 'Puntos', 'skincare' ); ?></h5>
											<ul>
												<li><strong><?php _e( 'Ganados en este pedido:', 'skincare' ); ?></strong> <?php echo $awarded_points ? esc_html( $awarded_points ) : '—'; ?></li>
												<li><strong><?php _e( 'Saldo actual:', 'skincare' ); ?></strong> <?php echo esc_html( $points ); ?></li>
											</ul>
										</section>
									</div>

									<footer class="sk-order-card__footer">
										<a class="btn sk-btn-small" href="<?php echo esc_url( $order->get_view_order_url() ); ?>"><?php _e( 'Ver detalle', 'skincare' ); ?></a>
									</footer>
								</article>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div id="tab-address" class="sk-tab-pane">
					<h4><?php _e( 'Dirección de Facturación', 'skincare' ); ?></h4>
					<address><?php echo $address ? wp_kses_post( $address ) : __( 'No has configurado una dirección.', 'skincare' ); ?></address>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="btn sk-btn-small"><?php _e( 'Editar', 'skincare' ); ?></a>
				</div>

				<div id="tab-rewards" class="sk-tab-pane">
					<div class="sk-rewards-overview" style="background: #F8F5F1; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
						<h4><?php _e( 'Balance de Puntos', 'skincare' ); ?></h4>
						<h2 style="color: #E5757E; font-size: 36px; margin: 10px 0;"><?php echo esc_html( $points ); ?></h2>
						<p><?php _e( 'Gana puntos con cada compra y canjéalos por descuentos exclusivos.', 'skincare' ); ?></p>
						<a href="/rewards/" class="btn sk-btn"><?php _e( 'Ver Catálogo de Premios', 'skincare' ); ?></a>
					</div>

					<h4><?php _e( 'Historial de Puntos', 'skincare' ); ?></h4>
					<?php if ( ! empty( $rewards_history ) ) : ?>
						<table class="sk-orders-table">
							<thead>
								<tr>
									<th><?php _e( 'Fecha', 'skincare' ); ?></th>
									<th><?php _e( 'Detalle', 'skincare' ); ?></th>
									<th><?php _e( 'Puntos', 'skincare' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( array_reverse( $rewards_history ) as $entry ) : ?>
									<tr>
										<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $entry['date'] ) ) ); ?></td>
										<td><?php echo esc_html( $entry['reason'] ); ?></td>
										<td style="color: <?php echo $entry['points'] > 0 ? 'green' : 'red'; ?>;">
											<?php echo $entry['points'] > 0 ? '+' . $entry['points'] : $entry['points']; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<p><?php _e( 'No hay historial de puntos aún.', 'skincare' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<style>
			.sk-account-dashboard { max-width: 980px; margin: 0 auto; }
			.sk-account-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
			.sk-user-points-badge { background: #E5757E; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; display: inline-block; margin-top: 5px; }
			.sk-account-tabs { display: flex; gap: 15px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
			.sk-tab-btn { background: none; border: none; padding: 10px 0; font-weight: bold; color: #aaa; cursor: pointer; border-bottom: 2px solid transparent; font-size: 16px; }
			.sk-tab-btn.active { color: #0F3062; border-color: #0F3062; }
			.sk-tab-pane { display: none; }
			.sk-tab-pane.active { display: block; animation: fadeIn 0.3s; }
			.sk-orders-grid { display: grid; gap: 20px; }
			.sk-order-card { border: 1px solid #eee; border-radius: 14px; padding: 20px; background: #fff; box-shadow: 0 10px 30px rgba(15, 48, 98, 0.08); }
			.sk-order-card__header { display: flex; justify-content: space-between; gap: 20px; align-items: flex-start; margin-bottom: 16px; }
			.sk-order-card__meta { color: #7f8a9b; font-size: 14px; margin-top: 4px; }
			.sk-order-card__status { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
			.sk-order-card__sections { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px; }
			.sk-order-card__sections h5 { margin: 0 0 8px; font-size: 15px; color: #0F3062; }
			.sk-order-card__sections ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 6px; color: #35455f; font-size: 14px; }
			.sk-order-card__sections li strong { color: #0F3062; }
			.sk-order-card__footer { display: flex; justify-content: flex-end; }
			.sk-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
			.sk-badge--status { background: rgba(15, 48, 98, 0.12); color: #0F3062; }
			.sk-badge--neutral { background: rgba(229, 117, 126, 0.15); color: #E5757E; }
			.sk-divider { padding: 0 8px; color: #c2c7d0; }
			.sk-text-success { color: #1f8f4a; font-weight: 600; }
			.sk-text-warning { color: #cc7a00; font-weight: 600; }
		</style>

		<script>
		jQuery(document).ready(function($){
			$('.sk-tab-btn').click(function(){
				$('.sk-tab-btn').removeClass('active');
				$('.sk-tab-pane').removeClass('active');
				$(this).addClass('active');
				$($(this).data('target')).addClass('active');
			});
		});
		</script>
		<?php
	}
}
