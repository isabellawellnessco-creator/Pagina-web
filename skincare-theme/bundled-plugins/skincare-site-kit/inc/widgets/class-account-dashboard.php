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
				echo '<div class="sk-empty-state sk-empty-state--compact">';
				echo '<span class="sk-empty-state__icon">👋</span>';
				echo '<div>';
				echo '<h3>' . __( 'Bienvenido', 'skincare' ) . '</h3>';
				echo '<p>' . __( 'Por favor inicia sesión para ver tu cuenta y tu tracking.', 'skincare' ) . '</p>';
				wp_login_form();
				echo '</div>';
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
		$rewards_history = is_array( $rewards_history ) ? $rewards_history : [];
		$tracking_settings = get_option( 'sk_tracking_settings', [] );
		$tracking_steps = isset( $tracking_settings['steps'] ) && is_array( $tracking_settings['steps'] )
			? array_values( $tracking_settings['steps'] )
			: [];
		if ( count( $tracking_steps ) < 4 ) {
			$tracking_steps = self::default_tracking_steps();
		}

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

			<div class="sk-account-tabs-wrapper" data-sk-tabs>
				<div class="sk-account-tabs" role="tablist">
					<button class="sk-tab-btn active" data-target="#tab-orders" role="tab" aria-selected="true"><?php _e( 'Mis Pedidos', 'skincare' ); ?></button>
					<button class="sk-tab-btn" data-target="#tab-address" role="tab" aria-selected="false"><?php _e( 'Direcciones', 'skincare' ); ?></button>
					<button class="sk-tab-btn" data-target="#tab-rewards" role="tab" aria-selected="false"><?php _e( 'Mis Puntos', 'skincare' ); ?></button>
				</div>

				<div id="tab-orders" class="sk-tab-pane active" role="tabpanel">
					<?php if ( empty( $orders ) ) : ?>
						<div class="sk-empty-state">
							<span class="sk-empty-state__icon">🛍️</span>
							<div>
								<h4><?php _e( 'Todavía no tienes pedidos', 'skincare' ); ?></h4>
								<p><?php _e( 'Empieza con un producto y te mostraremos el tracking aquí.', 'skincare' ); ?></p>
								<a href="/shop/" class="btn sk-btn"><?php _e( 'Ir a la Tienda', 'skincare' ); ?></a>
							</div>
						</div>
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
								$order_status = $order->get_status();
									$step_index = self::resolve_tracking_step( $order_status, $packing_status, $tracking_number, $tracking_url, $tracking_steps );
									$steps = self::hydrate_tracking_steps( $tracking_steps, $packing_status, $tracking_number );
									?>
									<article class="sk-card sk-order-card">
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
											<span class="sk-badge sk-badge--accent"><?php echo esc_html( $steps[ $step_index ]['label'] ); ?></span>
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
											<div class="sk-stepper" aria-live="polite">
												<?php foreach ( $steps as $index => $step ) : ?>
													<?php
													$step_class = 'sk-step';
													if ( $index < $step_index ) {
														$step_class .= ' is-complete';
													} elseif ( $index === $step_index ) {
														$step_class .= ' is-active';
													}
													?>
													<div class="<?php echo esc_attr( $step_class ); ?>">
														<span class="sk-step__dot"></span>
														<div>
															<strong><?php echo esc_html( $step['label'] ); ?></strong>
															<p><?php echo esc_html( $step['desc'] ); ?></p>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
											<ul class="sk-order-card__list">
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

				<div id="tab-address" class="sk-tab-pane" role="tabpanel">
					<h4><?php _e( 'Dirección de Facturación', 'skincare' ); ?></h4>
					<address><?php echo $address ? wp_kses_post( $address ) : __( 'No has configurado una dirección.', 'skincare' ); ?></address>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="btn sk-btn-small"><?php _e( 'Editar', 'skincare' ); ?></a>
				</div>

					<div id="tab-rewards" class="sk-tab-pane" role="tabpanel">
						<div class="sk-card sk-rewards-overview">
							<h4><?php _e( 'Balance de Puntos', 'skincare' ); ?></h4>
							<h2 class="sk-points-total" data-target="<?php echo esc_attr( $points ); ?>">0</h2>
							<p><?php _e( 'Gana puntos con cada compra y canjéalos por descuentos exclusivos.', 'skincare' ); ?></p>
							<a href="/rewards/" class="btn sk-btn"><?php _e( 'Ver Catálogo de Premios', 'skincare' ); ?></a>
						</div>

						<h4><?php _e( 'Historial de Puntos', 'skincare' ); ?></h4>
						<?php if ( ! empty( $rewards_history ) ) : ?>
							<table class="sk-orders-table sk-rewards-history">
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
										<td class="<?php echo $entry['points'] > 0 ? 'sk-text-success' : 'sk-text-warning'; ?>">
											<?php echo $entry['points'] > 0 ? '+' . $entry['points'] : $entry['points']; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php else : ?>
							<div class="sk-empty-state sk-empty-state--compact">
								<span class="sk-empty-state__icon">✨</span>
								<div>
									<h4><?php _e( 'Aún no tienes movimientos', 'skincare' ); ?></h4>
									<p><?php _e( 'Compra tus favoritos y verás aquí cada punto ganado.', 'skincare' ); ?></p>
								</div>
							</div>
						<?php endif; ?>
					</div>
			</div>
		</div>
		<?php
	}

	private static function default_tracking_steps() {
		return [
			[
				'label' => __( 'Pedido confirmado', 'skincare' ),
				'desc' => __( 'Estamos preparando tu pedido.', 'skincare' ),
				'statuses' => 'wc-processing,wc-on-hold,wc-pending',
			],
			[
				'label' => __( 'Empaque', 'skincare' ),
				'desc' => __( 'Empaque en progreso.', 'skincare' ),
				'statuses' => 'wc-processing',
			],
			[
				'label' => __( 'En camino', 'skincare' ),
				'desc' => __( 'Tu pedido va en camino.', 'skincare' ),
				'statuses' => 'wc-sk-on-the-way',
			],
			[
				'label' => __( 'Entregado', 'skincare' ),
				'desc' => __( 'Pedido entregado.', 'skincare' ),
				'statuses' => 'wc-completed,wc-sk-delivered',
			],
		];
	}

	private static function resolve_tracking_step( $order_status, $packing_status, $tracking_number, $tracking_url, $steps ) {
		$step_index = 0;
		$normalized_status = 'wc-' . $order_status;
		$statuses = self::parse_step_statuses( $steps );

		if ( in_array( $normalized_status, $statuses[3], true ) || 'completed' === $order_status ) {
			return 3;
		}
		if ( $tracking_number || $tracking_url || in_array( $normalized_status, $statuses[2], true ) ) {
			return 2;
		}
		if ( $packing_status || in_array( $normalized_status, $statuses[1], true ) ) {
			return 1;
		}

		return $step_index;
	}

	private static function hydrate_tracking_steps( $steps, $packing_status, $tracking_number ) {
		$steps[1]['desc'] = $packing_status ? $packing_status : $steps[1]['desc'];
		$steps[2]['desc'] = $tracking_number ? __( 'Tu pedido va en camino.', 'skincare' ) : $steps[2]['desc'];
		return $steps;
	}

	private static function parse_step_statuses( $steps ) {
		$statuses = [];
		foreach ( $steps as $step ) {
			$raw = isset( $step['statuses'] ) ? $step['statuses'] : '';
			$list = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
			$statuses[] = $list;
		}
		return array_pad( $statuses, 4, [] );
	}
}
