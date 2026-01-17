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
			__( 'Dashboard Ops', 'skincare' ), // Renamed for clarity
			'manage_woocommerce',
			'sk-operations-dashboard',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$pending_confirm = self::get_orders_by_op_status( Operations_Core::STATUS_PENDING_CONFIRM );
		$picking = self::get_orders_by_op_status( Operations_Core::STATUS_PICKING );
		$ready_dispatch = self::get_orders_by_op_status( [ Operations_Core::STATUS_PACKED, Operations_Core::STATUS_READY_DISPATCH ] );
		$transit = self::get_orders_by_op_status( Operations_Core::STATUS_IN_TRANSIT );

		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Centro de Operaciones Skin Cupid', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Gestión diaria de pedidos y logística.', 'skincare' ); ?></p>
			<div class="sk-admin-cta">
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center#sk-fulfillment-invoices' ) ); ?>">
					<?php esc_html_e( 'Facturar', 'skincare' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-fulfillment-center#sk-fulfillment-labels' ) ); ?>">
					<?php esc_html_e( 'Crear etiqueta', 'skincare' ); ?>
				</a>
			</div>

			<div class="sk-admin-hero">
				<div class="sk-admin-grid">
					<div class="sk-admin-card metric-warning">
						<strong><?php esc_html_e( 'Por Confirmar', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo count( $pending_confirm ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'En Picking/Empaque', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo count( $picking ) + count( $ready_dispatch ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'En Tránsito', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo count( $transit ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Listos Recojo (Prov)', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo self::count_by_status( Operations_Core::STATUS_READY_PICKUP ); ?></p>
					</div>
				</div>
			</div>

			<div class="sk-admin-panel is-active">
				<div class="sk-admin-panel-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

					<!-- Queue: Confirmations -->
					<div class="sk-admin-card">
						<h3><span class="dashicons dashicons-phone"></span> <?php esc_html_e( 'Por Confirmar (Contraentrega)', 'skincare' ); ?></h3>
						<?php self::render_queue_table( $pending_confirm, 'confirm' ); ?>
					</div>

					<!-- Queue: Picking -->
					<div class="sk-admin-card">
						<h3><span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Para Picking y Empaque', 'skincare' ); ?></h3>
						<?php self::render_queue_table( $picking, 'picking' ); ?>
					</div>

					<!-- Queue: Dispatch -->
					<div class="sk-admin-card">
						<h3><span class="dashicons dashicons-car"></span> <?php esc_html_e( 'Listos para Despacho', 'skincare' ); ?></h3>
						<?php self::render_queue_table( $ready_dispatch, 'dispatch' ); ?>
					</div>

					<!-- Queue: Incidents -->
					<div class="sk-admin-card" style="border-left: 4px solid red;">
						<h3><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Incidencias / No Responde', 'skincare' ); ?></h3>
						<?php
						$incidents = self::get_orders_by_op_status( [ Operations_Core::STATUS_INCIDENT, Operations_Core::STATUS_NO_RESPONSE_1, Operations_Core::STATUS_NO_RESPONSE_2 ] );
						self::render_queue_table( $incidents, 'incident' );
						?>
					</div>

				</div>
			</div>

			<style>
				.sk-admin-cta { margin: 12px 0 24px; display: flex; gap: 12px; flex-wrap: wrap; }
				.metric-warning { border-left: 4px solid #ffba00; }
				.sk-admin-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
				.sk-admin-card { background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
				.sk-admin-metric { font-size: 32px; font-weight: bold; margin: 10px 0 0; color: #0F3062; }
			</style>
		</div>
		<?php
	}

	private static function get_orders_by_op_status( $status ) {
		return wc_get_orders( [
			'limit' => 20,
			'status' => [ 'processing', 'on-hold', 'pending' ], // Base WC statuses
			'meta_query' => [
				[
					'key' => Operations_Core::META_STATUS,
					'value' => $status,
					'compare' => is_array( $status ) ? 'IN' : '=',
				],
			],
			'orderby' => 'date',
			'order' => 'ASC', // Oldest first for operational FIFO
		] );
	}

	private static function count_by_status( $status ) {
		$orders = wc_get_orders( [
			'limit' => -1,
			'status' => [ 'processing', 'on-hold', 'pending' ],
			'meta_query' => [
				[
					'key' => Operations_Core::META_STATUS,
					'value' => $status,
				],
			],
			'return' => 'ids',
		] );
		return count( $orders );
	}

	private static function render_queue_table( $orders, $type ) {
		if ( empty( $orders ) ) {
			echo '<p>' . __( 'No hay pedidos en esta cola. ¡Buen trabajo!', 'skincare' ) . '</p>';
			return;
		}

		echo '<table class="widefat striped">';
		echo '<thead><tr><th>Pedido</th><th>Cliente</th><th>Zona</th><th>Acción</th></tr></thead>';
		echo '<tbody>';
		foreach ( $orders as $order ) {
			$zone = $order->get_meta( Operations_Core::META_ZONE, true );
			$edit_link = get_edit_post_link( $order->get_id() );

			echo '<tr>';
			echo '<td><a href="' . esc_url( $edit_link ) . '">#' . esc_html( $order->get_order_number() ) . '</a></td>';
			echo '<td>' . esc_html( $order->get_billing_first_name() ) . '<br><small>' . esc_html( $order->get_billing_phone() ) . '</small></td>';
			echo '<td>' . esc_html( ucfirst( $zone ) ) . '</td>';
			echo '<td>';

			if ( class_exists( '\Skincare\SiteKit\Admin\Whatsapp_Context' ) ) {
				// Render simple icon button
				$tpl_id = Whatsapp_Context::get_suggested_template_id( $order );
				if ( $tpl_id ) {
					$link = Whatsapp_Context::generate_link( $order, $tpl_id );
					echo '<a href="' . esc_url( $link ) . '" target="_blank" class="button button-small" title="WhatsApp"><span class="dashicons dashicons-whatsapp"></span></a> ';
				}
			}

			$fulfillment_labels = admin_url( 'admin.php?page=sk-fulfillment-center&order_ids=' . $order->get_id() . '#sk-fulfillment-labels' );
			$fulfillment_invoices = admin_url( 'admin.php?page=sk-fulfillment-center&order_ids=' . $order->get_id() . '#sk-fulfillment-invoices' );

			echo '<a href="' . esc_url( $fulfillment_labels ) . '" class="button button-small">Etiqueta</a> ';
			echo '<a href="' . esc_url( $fulfillment_invoices ) . '" class="button button-small">Comprobante SUNAT</a> ';
			echo '<a href="' . esc_url( $edit_link ) . '" class="button button-small">Gestión</a>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}
}
