<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Operations_Dashboard {
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
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
		$recent_orders = self::get_recent_orders();
		$warehouse_orders = self::get_warehouse_orders();
		$rewards_orders = self::get_rewards_orders();
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

			<div class="sk-admin-panel is-active">
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

			<div class="sk-admin-panel is-active" style="margin-top: 20px;">
				<h2><?php esc_html_e( 'Puntos recientes', 'skincare' ); ?></h2>
				<p><?php esc_html_e( 'Resumen de puntos por pedido con acciones rápidas.', 'skincare' ); ?></p>
				<?php self::render_rewards_table( $rewards_orders ); ?>
			</div>
		</div>
		<?php
	}

	private static function get_recent_orders() {
		return wc_get_orders( [
			'limit' => 8,
			'status' => [ 'processing', 'on-hold', 'pending' ],
			'orderby' => 'date',
			'order' => 'DESC',
		] );
	}

	private static function get_warehouse_orders() {
		return wc_get_orders( [
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
		] );
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
					<th><?php esc_html_e( 'Total', 'skincare' ); ?></th>
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
							<td><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></td>
							<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
							<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
							<td>
								<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Ver', 'skincare' ); ?>
								</a>
								<a class="button button-small" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center&order_ids=' . $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Packing', 'skincare' ); ?>
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
					<tr><td colspan="4">—</td></tr>
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
								</td>
							</tr>
						<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
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
}
