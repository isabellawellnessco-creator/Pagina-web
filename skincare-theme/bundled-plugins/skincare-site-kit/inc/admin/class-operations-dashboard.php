<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Operations_Dashboard {
	const UPDATE_NONCE_ACTION = 'sk_operations_update_order';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_sk_operations_update_order', [ __CLASS__, 'handle_update_order' ] );
		add_action( 'wp_ajax_sk_operations_update_order', [ __CLASS__, 'handle_update_order_ajax' ] );
		add_action( 'admin_post_sk_operations_print_barcode', [ __CLASS__, 'handle_print_barcode' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Operations Dashboard', 'skincare' ),
			__( 'Dashboard', 'skincare' ),
			'manage_woocommerce',
			'sk-operations-dashboard',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$filters = self::get_filters();
		$recent_orders = self::get_recent_orders( $filters );
		$warehouse_orders = self::get_warehouse_orders( $filters );
		$rewards_orders = self::get_rewards_orders();
		$purchase_orders = self::get_purchase_orders();
		$reward_totals = self::summarize_points( $rewards_orders );
		$order_counts = self::get_order_counts();
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Operations Dashboard', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Pedidos, almacén y puntos en una sola vista.', 'skincare' ); ?></p>

			<div class="sk-admin-hero">
				<div class="sk-admin-actions">
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center' ) ); ?>">
						<?php esc_html_e( 'Abrir Fulfillment', 'skincare' ); ?>
					</a>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-rewards-control' ) ); ?>">
						<?php esc_html_e( 'Control de Puntos', 'skincare' ); ?>
					</a>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-sla-monitor' ) ); ?>">
						<?php esc_html_e( 'SLA Monitor', 'skincare' ); ?>
					</a>
				</div>
					<div class="sk-admin-grid">
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Pedidos pendientes', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $order_counts['pending'] ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Pedidos en proceso', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $order_counts['processing'] ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Puntos otorgados', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $reward_totals['awarded'] ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Puntos revertidos', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $reward_totals['reversed'] ); ?></p>
					</div>
					</div>
				</div>

				<form class="sk-admin-toolbar" method="get" action="">
					<input type="hidden" name="page" value="sk-operations-dashboard">
					<label>
						<?php esc_html_e( 'Buscar', 'skincare' ); ?>
						<input type="text" name="sk_search" value="<?php echo esc_attr( $filters['search'] ); ?>" placeholder="<?php esc_attr_e( 'Pedido o cliente', 'skincare' ); ?>">
					</label>
					<label>
						<?php esc_html_e( 'Estado', 'skincare' ); ?>
						<select name="status">
							<option value=""><?php esc_html_e( 'Todos', 'skincare' ); ?></option>
							<?php foreach ( wc_get_order_statuses() as $status_key => $status_label ) : ?>
								<?php $status_value = str_replace( 'wc-', '', $status_key ); ?>
								<option value="<?php echo esc_attr( $status_value ); ?>" <?php selected( $filters['status'], $status_value ); ?>>
									<?php echo esc_html( $status_label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
					<label>
						<?php esc_html_e( 'Pago', 'skincare' ); ?>
						<select name="payment_state">
							<option value=""><?php esc_html_e( 'Todos', 'skincare' ); ?></option>
							<option value="unpaid" <?php selected( $filters['payment_state'], 'unpaid' ); ?>><?php esc_html_e( 'No pagado', 'skincare' ); ?></option>
							<option value="paid" <?php selected( $filters['payment_state'], 'paid' ); ?>><?php esc_html_e( 'Pagado', 'skincare' ); ?></option>
						</select>
					</label>
					<button class="button button-primary" type="submit"><?php esc_html_e( 'Filtrar', 'skincare' ); ?></button>
				</form>

				<nav class="sk-admin-tabs" aria-label="<?php esc_attr_e( 'Operations sections', 'skincare' ); ?>">
					<button class="sk-admin-tab is-active" data-target="sk-ops-orders"><?php esc_html_e( 'Pedidos', 'skincare' ); ?></button>
					<button class="sk-admin-tab" data-target="sk-ops-warehouse"><?php esc_html_e( 'Almacén', 'skincare' ); ?></button>
					<button class="sk-admin-tab" data-target="sk-ops-purchase-orders"><?php esc_html_e( 'Órdenes de compra', 'skincare' ); ?></button>
					<button class="sk-admin-tab" data-target="sk-ops-rewards"><?php esc_html_e( 'Puntos', 'skincare' ); ?></button>
				</nav>

				<div id="sk-ops-orders" class="sk-admin-panel is-active">
					<div class="sk-admin-panel-grid">
						<div class="sk-admin-card">
							<h2><?php esc_html_e( 'Pedidos recientes', 'skincare' ); ?></h2>
							<p><?php esc_html_e( 'Seguimiento rápido de pedidos activos.', 'skincare' ); ?></p>
						<?php self::render_orders_table( $recent_orders ); ?>
					</div>
					<div class="sk-admin-card">
						<h2><?php esc_html_e( 'Almacén y despacho', 'skincare' ); ?></h2>
						<p><?php esc_html_e( 'Estado de preparación y ubicación de almacén.', 'skincare' ); ?></p>
						<?php self::render_warehouse_table( $warehouse_orders ); ?>
						</div>
					</div>
				</div>

				<div id="sk-ops-warehouse" class="sk-admin-panel">
					<h2><?php esc_html_e( 'Almacén y despacho', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Estado de preparación y ubicación de almacén.', 'skincare' ); ?></p>
					<?php self::render_warehouse_table( $warehouse_orders ); ?>
				</div>

				<div id="sk-ops-purchase-orders" class="sk-admin-panel">
					<h2><?php esc_html_e( 'Órdenes de compra', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Control de proveedores y fechas estimadas.', 'skincare' ); ?></p>
					<?php self::render_purchase_orders_table( $purchase_orders ); ?>
				</div>

				<div id="sk-ops-rewards" class="sk-admin-panel">
					<h2><?php esc_html_e( 'Puntos recientes', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Resumen de puntos por pedido con acciones rápidas.', 'skincare' ); ?></p>
					<?php self::render_rewards_table( $rewards_orders ); ?>
				</div>
		</div>
		<?php
	}

	private static function get_filters() {
		return [
			'search' => isset( $_GET['sk_search'] ) ? sanitize_text_field( wp_unslash( $_GET['sk_search'] ) ) : '',
			'status' => isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '',
			'payment_state' => isset( $_GET['payment_state'] ) ? sanitize_text_field( wp_unslash( $_GET['payment_state'] ) ) : '',
		];
	}

	private static function get_recent_orders( $filters ) {
		$args = [
			'limit' => 8,
			'status' => [ 'processing', 'on-hold', 'pending' ],
			'orderby' => 'date',
			'order' => 'DESC',
		];

		if ( ! empty( $filters['status'] ) ) {
			$args['status'] = [ $filters['status'] ];
		}

		if ( ! empty( $filters['search'] ) ) {
			if ( is_numeric( $filters['search'] ) ) {
				$args['include'] = [ absint( $filters['search'] ) ];
			} else {
				$args['search'] = '*' . $filters['search'] . '*';
			}
		}

		if ( ! empty( $filters['payment_state'] ) ) {
			$args['meta_query'] = [
				[
					'key' => '_sk_payment_state',
					'value' => $filters['payment_state'],
				],
			];
		}

		return wc_get_orders( $args );
	}

	private static function get_warehouse_orders( $filters ) {
		$args = [
			'limit' => 8,
			'status' => [ 'processing', 'on-hold', 'pending' ],
			'orderby' => 'date',
			'order' => 'DESC',
			'meta_query' => [
				[
					'key' => '_sk_warehouse_location',
					'value' => '',
					'compare' => '!=',
				],
			],
		];

		if ( ! empty( $filters['status'] ) ) {
			$args['status'] = [ $filters['status'] ];
		}

		return wc_get_orders( $args );
	}

	private static function get_rewards_orders() {
		return wc_get_orders( [
			'limit' => 12,
			'orderby' => 'date',
			'order' => 'DESC',
			'meta_query' => [
				'relation' => 'OR',
				[
					'key' => '_sk_rewards_awarded',
					'compare' => 'EXISTS',
				],
				[
					'key' => '_sk_rewards_reversed',
					'compare' => 'EXISTS',
				],
			],
		] );
	}

	private static function get_purchase_orders() {
		return get_posts( [
			'post_type' => 'sk_purchase_order',
			'post_status' => 'publish',
			'numberposts' => 8,
			'orderby' => 'date',
			'order' => 'DESC',
		] );
	}

	private static function summarize_points( $orders ) {
		$awarded = 0;
		$reversed = 0;
		foreach ( $orders as $order ) {
			$awarded += (int) $order->get_meta( '_sk_rewards_awarded', true );
			$reversed += (int) $order->get_meta( '_sk_rewards_reversed', true );
		}

		return [
			'awarded' => $awarded,
			'reversed' => $reversed,
		];
	}

	private static function get_order_counts() {
		$pending = wc_orders_count( 'pending' );
		$processing = wc_orders_count( 'processing' );
		return [
			'pending' => $pending,
			'processing' => $processing,
		];
	}

	private static function render_orders_table( $orders ) {
		?>
		<table class="widefat striped sk-admin-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Pedido', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Cliente', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Estado', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Pago', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Distribuidor', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Tracking', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'ETA', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Total', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Acciones', 'skincare' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $orders ) ) : ?>
					<tr><td colspan="9">—</td></tr>
				<?php else : ?>
					<?php foreach ( $orders as $order ) : ?>
						<?php
							$order_id = $order->get_id();
							$carrier = get_post_meta( $order_id, '_sk_carrier', true );
							$tracking = get_post_meta( $order_id, '_sk_tracking_code', true );
							$eta = get_post_meta( $order_id, '_sk_eta', true );
							$payment_state = get_post_meta( $order_id, '_sk_payment_state', true );
							$payment_method = $order->get_payment_method_title();
							$cod = $order->get_payment_method() === 'cod' ? __( 'Contraentrega', 'skincare' ) : '';
							$current_status = $order->get_status();
						?>
						<tr>
							<td>#<?php echo esc_html( $order->get_order_number() ); ?></td>
							<td><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></td>
							<td>
								<form class="sk-admin-inline-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<?php wp_nonce_field( self::UPDATE_NONCE_ACTION, 'sk_operations_nonce' ); ?>
									<input type="hidden" name="action" value="sk_operations_update_order">
									<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
									<select name="order_status">
										<?php foreach ( wc_get_order_statuses() as $status_key => $status_label ) : ?>
											<?php $status_value = str_replace( 'wc-', '', $status_key ); ?>
											<option value="<?php echo esc_attr( $status_value ); ?>" <?php selected( $current_status, $status_value ); ?>>
												<?php echo esc_html( $status_label ); ?>
											</option>
										<?php endforeach; ?>
									</select>
									<button class="button button-small" type="submit"><?php esc_html_e( 'Guardar', 'skincare' ); ?></button>
								</form>
							</td>
							<td>
								<div class="sk-admin-inline-form">
									<div class="sk-admin-pill"><?php echo esc_html( $payment_method ?: __( 'Manual', 'skincare' ) ); ?></div>
									<?php if ( $cod ) : ?>
										<span class="sk-admin-pill"><?php echo esc_html( $cod ); ?></span>
									<?php endif; ?>
									<form class="sk-admin-inline-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
										<?php wp_nonce_field( self::UPDATE_NONCE_ACTION, 'sk_operations_nonce' ); ?>
										<input type="hidden" name="action" value="sk_operations_update_order">
										<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
										<select name="payment_state">
											<option value="unpaid" <?php selected( $payment_state, 'unpaid' ); ?>><?php esc_html_e( 'No pagado', 'skincare' ); ?></option>
											<option value="paid" <?php selected( $payment_state, 'paid' ); ?>><?php esc_html_e( 'Pagado', 'skincare' ); ?></option>
										</select>
										<button class="button button-small" type="submit"><?php esc_html_e( 'Actualizar', 'skincare' ); ?></button>
									</form>
								</div>
							</td>
							<td>
								<form class="sk-admin-inline-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<?php wp_nonce_field( self::UPDATE_NONCE_ACTION, 'sk_operations_nonce' ); ?>
									<input type="hidden" name="action" value="sk_operations_update_order">
									<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
									<input type="text" name="carrier" value="<?php echo esc_attr( $carrier ); ?>" placeholder="<?php esc_attr_e( 'Distribuidor', 'skincare' ); ?>">
									<button class="button button-small" type="submit"><?php esc_html_e( 'Guardar', 'skincare' ); ?></button>
								</form>
							</td>
							<td>
								<form class="sk-admin-inline-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<?php wp_nonce_field( self::UPDATE_NONCE_ACTION, 'sk_operations_nonce' ); ?>
									<input type="hidden" name="action" value="sk_operations_update_order">
									<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
									<input type="text" name="tracking" value="<?php echo esc_attr( $tracking ); ?>" placeholder="<?php esc_attr_e( 'Código', 'skincare' ); ?>">
									<button class="button button-small" type="submit"><?php esc_html_e( 'Guardar', 'skincare' ); ?></button>
								</form>
							</td>
							<td>
								<form class="sk-admin-inline-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<?php wp_nonce_field( self::UPDATE_NONCE_ACTION, 'sk_operations_nonce' ); ?>
									<input type="hidden" name="action" value="sk_operations_update_order">
									<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ); ?>">
									<input type="date" name="eta" value="<?php echo esc_attr( $eta ); ?>">
									<button class="button button-small" type="submit"><?php esc_html_e( 'Guardar', 'skincare' ); ?></button>
								</form>
							</td>
							<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
							<td>
								<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Ver', 'skincare' ); ?>
								</a>
								<a class="button button-small" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center&order_ids=' . $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Packing', 'skincare' ); ?>
								</a>
								<a class="button button-small" href="<?php echo esc_url( self::barcode_url( $order->get_id() ) ); ?>" target="_blank" rel="noopener">
									<?php esc_html_e( 'Código barra', 'skincare' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function render_warehouse_table( $orders ) {
		?>
		<table class="widefat striped sk-admin-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Pedido', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Almacén', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Estado', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Creado', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Acciones', 'skincare' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $orders ) ) : ?>
					<tr><td colspan="5">—</td></tr>
				<?php else : ?>
					<?php foreach ( $orders as $order ) : ?>
							<tr>
								<td>#<?php echo esc_html( $order->get_order_number() ); ?></td>
								<td><?php echo esc_html( get_post_meta( $order->get_id(), '_sk_warehouse_location', true ) ?: '—' ); ?></td>
								<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
								<td><?php echo esc_html( $order->get_date_created()->date_i18n( 'Y-m-d H:i' ) ); ?></td>
								<td>
									<a class="button button-small" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center&order_ids=' . $order->get_id() ) ); ?>">
										<?php esc_html_e( 'Generar', 'skincare' ); ?>
									</a>
									<a class="button button-small" href="<?php echo esc_url( self::barcode_url( $order->get_id() ) ); ?>" target="_blank" rel="noopener">
										<?php esc_html_e( 'Código barra', 'skincare' ); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function update_order_from_request( $order_id, $data ) {
		$order = $order_id ? wc_get_order( $order_id ) : null;
		if ( ! $order ) {
			return false;
		}

		if ( isset( $data['order_status'] ) ) {
			$status = sanitize_text_field( wp_unslash( $data['order_status'] ) );
			$order->update_status( $status );
		}

		$meta_fields = [
			'carrier' => '_sk_carrier',
			'tracking' => '_sk_tracking_code',
			'eta' => '_sk_eta',
			'payment_state' => '_sk_payment_state',
		];

		foreach ( $meta_fields as $field => $meta_key ) {
			if ( isset( $data[ $field ] ) ) {
				$value = sanitize_text_field( wp_unslash( $data[ $field ] ) );
				update_post_meta( $order_id, $meta_key, $value );
			}
		}

		return true;
	}

	private static function render_rewards_table( $orders ) {
		?>
		<table class="widefat striped sk-admin-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Pedido', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Cliente', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Puntos', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Total', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Acciones', 'skincare' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $orders ) ) : ?>
					<tr><td colspan="5">—</td></tr>
				<?php else : ?>
					<?php foreach ( $orders as $order ) : ?>
						<?php
							$awarded = (int) $order->get_meta( '_sk_rewards_awarded', true );
							$reversed = (int) $order->get_meta( '_sk_rewards_reversed', true );
							$points_label = $awarded ? $awarded : '—';
							if ( $reversed ) {
								$points_label = sprintf( '%s (%s)', $points_label, esc_html__( 'revertido', 'skincare' ) );
							}
						?>
						<tr>
							<td>#<?php echo esc_html( $order->get_order_number() ); ?></td>
							<td><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></td>
							<td><?php echo esc_html( $points_label ); ?></td>
							<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
							<td>
								<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Ver', 'skincare' ); ?>
								</a>
								<a class="button button-small" href="<?php echo esc_url( self::recalc_points_url( $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Recalcular', 'skincare' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function render_purchase_orders_table( $purchase_orders ) {
		?>
		<table class="widefat striped sk-admin-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Orden', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Proveedor', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Estado', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'ETA', 'skincare' ); ?></th>
					<th><?php esc_html_e( 'Acciones', 'skincare' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $purchase_orders ) ) : ?>
					<tr><td colspan="5">—</td></tr>
				<?php else : ?>
					<?php foreach ( $purchase_orders as $po ) : ?>
						<?php
							$po_supplier = get_post_meta( $po->ID, '_sk_po_supplier', true );
							$po_status = get_post_meta( $po->ID, '_sk_po_status', true );
							$po_expected = get_post_meta( $po->ID, '_sk_po_expected_date', true );
						?>
						<tr>
							<td><?php echo esc_html( $po->post_title ); ?></td>
							<td><?php echo esc_html( $po_supplier ?: '—' ); ?></td>
							<td><?php echo esc_html( $po_status ?: '—' ); ?></td>
							<td><?php echo esc_html( $po_expected ?: '—' ); ?></td>
							<td>
								<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $po->ID ) ); ?>">
									<?php esc_html_e( 'Editar', 'skincare' ); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function recalc_points_url( $order_id ) {
		return wp_nonce_url(
			add_query_arg(
				[
					'page' => 'sk-rewards-control',
					'sk_action' => 'recalculate_points',
					'order_id' => $order_id,
				],
				admin_url( 'admin.php' )
			),
			Rewards_Admin::NONCE_ACTION
		);
	}

	private static function barcode_url( $order_id ) {
		return wp_nonce_url(
			add_query_arg(
				[
					'action' => 'sk_operations_print_barcode',
					'order_id' => $order_id,
				],
				admin_url( 'admin-post.php' )
			),
			self::UPDATE_NONCE_ACTION
		);
	}

	public static function handle_update_order() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'Unauthorized' );
		}

		if ( ! isset( $_POST['sk_operations_nonce'] ) || ! wp_verify_nonce( $_POST['sk_operations_nonce'], self::UPDATE_NONCE_ACTION ) ) {
			wp_die( 'Invalid nonce' );
		}

		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		if ( ! self::update_order_from_request( $order_id, $_POST ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=sk-operations-dashboard' ) );
			exit;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=sk-operations-dashboard' ) );
		exit;
	}

	public static function handle_update_order_ajax() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ], 403 );
		}

		check_ajax_referer( self::UPDATE_NONCE_ACTION, 'nonce' );

		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		if ( ! self::update_order_from_request( $order_id, $_POST ) ) {
			wp_send_json_error( [ 'message' => 'Invalid order' ], 400 );
		}

		wp_send_json_success( [ 'message' => __( 'Actualizado', 'skincare' ) ] );
	}

	public static function handle_print_barcode() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'Unauthorized' );
		}

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], self::UPDATE_NONCE_ACTION ) ) {
			wp_die( 'Invalid nonce' );
		}

		$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
		$order = $order_id ? wc_get_order( $order_id ) : null;
		if ( ! $order ) {
			wp_die( 'Order not found' );
		}

		$items = $order->get_items();
		?>
		<!doctype html>
		<html>
		<head>
			<meta charset="utf-8">
			<title><?php echo esc_html( sprintf( __( 'Order #%s', 'skincare' ), $order->get_order_number() ) ); ?></title>
			<style>
				body { font-family: Arial, sans-serif; padding: 24px; }
				.sk-barcode-card { border: 1px solid #ccc; padding: 16px; margin-bottom: 16px; }
				.sk-barcode-code { font-size: 20px; letter-spacing: 2px; font-weight: bold; }
			</style>
		</head>
		<body>
			<h1><?php echo esc_html( sprintf( __( 'Order #%s', 'skincare' ), $order->get_order_number() ) ); ?></h1>
			<?php foreach ( $items as $item ) : ?>
				<?php $product = $item->get_product(); ?>
				<div class="sk-barcode-card">
					<p><strong><?php echo esc_html( $item->get_name() ); ?></strong></p>
					<p><?php esc_html_e( 'SKU', 'skincare' ); ?>: <?php echo esc_html( $product ? $product->get_sku() : '—' ); ?></p>
					<div class="sk-barcode-code"><?php echo esc_html( $product ? $product->get_sku() : $order->get_order_number() ); ?></div>
				</div>
			<?php endforeach; ?>
			<script>window.print();</script>
		</body>
		</html>
		<?php
		exit;
	}
}
