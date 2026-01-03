<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Fulfillment {
	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Fulfillment Center', 'skincare' ),
			__( 'Fulfillment', 'skincare' ),
			'manage_woocommerce',
			'sk-fulfillment-center',
			[ __CLASS__, 'render_dashboard' ]
		);

		add_submenu_page(
			'skincare-site-kit',
			__( 'SLA Monitor', 'skincare' ),
			__( 'SLA Monitor', 'skincare' ),
			'manage_woocommerce',
			'sk-sla-monitor',
			[ __CLASS__, 'render_sla_monitor' ]
		);

		add_submenu_page(
			'skincare-site-kit',
			__( 'Batch Picking', 'skincare' ),
			__( 'Batch Picking', 'skincare' ),
			'manage_woocommerce',
			'sk-batch-picking',
			[ __CLASS__, 'render_batch_picking' ]
		);

		add_submenu_page(
			'skincare-site-kit',
			__( 'Packing Slips', 'skincare' ),
			__( 'Packing Slips', 'skincare' ),
			'manage_woocommerce',
			'sk-packing-slips',
			[ __CLASS__, 'render_packing_slips' ]
		);

		add_submenu_page(
			'skincare-site-kit',
			__( 'Shipping Labels', 'skincare' ),
			__( 'Shipping Labels', 'skincare' ),
			'manage_woocommerce',
			'sk-shipping-labels',
			[ __CLASS__, 'render_shipping_labels' ]
		);

		add_submenu_page(
			'skincare-site-kit',
			__( 'Invoices & Boletas', 'skincare' ),
			__( 'Invoices', 'skincare' ),
			'manage_woocommerce',
			'sk-invoices',
			[ __CLASS__, 'render_invoices' ]
		);
	}

	public static function render_sla_monitor() {
		$threshold_hours = isset( $_GET['threshold'] ) ? absint( $_GET['threshold'] ) : 24;
		$cutoff = gmdate( 'Y-m-d H:i:s', strtotime( '-' . $threshold_hours . ' hours' ) );
		$orders = wc_get_orders( [
			'limit' => 50,
			'status' => [ 'pending', 'processing', 'sk-on-the-way' ],
			'date_created' => '<' . $cutoff,
			'orderby' => 'date_created',
			'order' => 'ASC',
		] );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'SLA Monitor', 'skincare' ); ?></h1>
			<form method="get">
				<input type="hidden" name="page" value="sk-sla-monitor">
				<label for="threshold"><?php esc_html_e( 'Hours threshold', 'skincare' ); ?></label>
				<input type="number" name="threshold" id="threshold" value="<?php echo esc_attr( $threshold_hours ); ?>" min="1" step="1">
				<?php submit_button( __( 'Filter', 'skincare' ), 'secondary', 'submit', false ); ?>
			</form>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Order', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Created', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Status', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Warehouse', 'skincare' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $orders ) : ?>
						<?php foreach ( $orders as $order ) : ?>
							<tr>
								<td><a href="<?php echo esc_url( get_edit_post_link( $order->get_id() ) ); ?>">#<?php echo esc_html( $order->get_order_number() ); ?></a></td>
								<td><?php echo esc_html( $order->get_date_created()->date_i18n( 'Y-m-d H:i' ) ); ?></td>
								<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
								<td><?php echo esc_html( get_post_meta( $order->get_id(), '_sk_warehouse_location', true ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="4">—</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function render_batch_picking() {
		$batch_id = isset( $_POST['batch_id'] ) ? sanitize_text_field( wp_unslash( $_POST['batch_id'] ) ) : '';
		$order_ids = isset( $_POST['order_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['order_ids'] ) ) : '';
		$updated = false;

		if ( $batch_id && $order_ids ) {
			$ids = array_filter( array_map( 'absint', explode( ',', $order_ids ) ) );
			foreach ( $ids as $order_id ) {
				update_post_meta( $order_id, '_sk_picking_batch_id', $batch_id );
			}
			$updated = true;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Batch Picking', 'skincare' ); ?></h1>
			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Batch updated.', 'skincare' ); ?></p></div>
			<?php endif; ?>
			<form method="post">
				<table class="form-table">
					<tr>
						<th><label for="batch_id"><?php esc_html_e( 'Batch ID', 'skincare' ); ?></label></th>
						<td><input type="text" name="batch_id" id="batch_id" class="regular-text" placeholder="BATCH-2025-001"></td>
					</tr>
					<tr>
						<th><label for="order_ids"><?php esc_html_e( 'Order IDs', 'skincare' ); ?></label></th>
						<td><input type="text" name="order_ids" id="order_ids" class="large-text" placeholder="1234,1235,1236"></td>
					</tr>
				</table>
				<?php submit_button( __( 'Assign batch', 'skincare' ) ); ?>
			</form>
		</div>
		<?php
	}

	public static function render_dashboard() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Fulfillment Center', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Use the tools below to manage packing, shipping labels, and invoices without opening each order.', 'skincare' ); ?></p>
			<ul>
				<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-packing-slips' ) ); ?>"><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-shipping-labels' ) ); ?>"><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-invoices' ) ); ?>"><?php esc_html_e( 'Invoices & Boletas', 'skincare' ); ?></a></li>
			</ul>
		</div>
		<?php
	}

	private static function parse_order_ids() {
		if ( empty( $_GET['order_ids'] ) ) {
			return [];
		}

		$raw_ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['order_ids'] ) ) );
		$order_ids = [];
		foreach ( $raw_ids as $raw_id ) {
			$id = absint( trim( $raw_id ) );
			if ( $id ) {
				$order_ids[] = $id;
			}
		}

		return array_unique( $order_ids );
	}

	private static function get_orders_from_ids( $order_ids ) {
		$orders = [];
		foreach ( $order_ids as $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$orders[] = $order;
			}
		}
		return $orders;
	}

	private static function render_order_form( $title ) {
		?>
		<h2><?php echo esc_html( $title ); ?></h2>
		<form method="get" action="">
			<input type="hidden" name="page" value="<?php echo esc_attr( $_GET['page'] ?? '' ); ?>">
			<p>
				<label for="order-ids"><strong><?php esc_html_e( 'Order IDs (comma separated)', 'skincare' ); ?></strong></label>
				<input type="text" class="large-text" name="order_ids" id="order-ids" placeholder="1234, 1235, 1236">
			</p>
			<?php submit_button( __( 'Generate', 'skincare' ), 'primary', 'submit', false ); ?>
		</form>
		<?php
	}

	public static function render_packing_slips() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Packing slips generator', 'skincare' ) ); ?>
			<?php if ( $orders ) : ?>
				<hr>
				<div class="sk-print-area">
					<?php foreach ( $orders as $order ) : ?>
						<div class="sk-print-card">
							<h3><?php echo esc_html( sprintf( __( 'Order #%s', 'skincare' ), $order->get_order_number() ) ); ?></h3>
							<p><strong><?php esc_html_e( 'Customer:', 'skincare' ); ?></strong> <?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></p>
							<p><strong><?php esc_html_e( 'Shipping:', 'skincare' ); ?></strong> <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></p>
							<ul>
								<?php foreach ( $order->get_items() as $item ) : ?>
									<li><?php echo esc_html( $item->get_name() . ' × ' . $item->get_quantity() ); ?></li>
								<?php endforeach; ?>
							</ul>
							<p><strong><?php esc_html_e( 'Internal notes:', 'skincare' ); ?></strong> <?php echo esc_html( get_post_meta( $order->get_id(), '_sk_internal_notes', true ) ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function render_shipping_labels() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Shipping labels generator', 'skincare' ) ); ?>
			<?php if ( $orders ) : ?>
				<hr>
				<div class="sk-print-area">
					<?php foreach ( $orders as $order ) : ?>
						<div class="sk-print-card">
							<h3><?php echo esc_html( sprintf( __( 'Order #%s', 'skincare' ), $order->get_order_number() ) ); ?></h3>
							<p><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></p>
							<p><strong><?php esc_html_e( 'Phone:', 'skincare' ); ?></strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
							<p><strong><?php esc_html_e( 'Carrier:', 'skincare' ); ?></strong> <?php echo esc_html( get_post_meta( $order->get_id(), '_sk_carrier', true ) ); ?></p>
							<p><strong><?php esc_html_e( 'Tracking:', 'skincare' ); ?></strong> <?php echo esc_html( get_post_meta( $order->get_id(), '_sk_tracking_number', true ) ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function render_invoices() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Invoices & Boletas', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Invoice generator', 'skincare' ) ); ?>
			<?php if ( $orders ) : ?>
				<hr>
				<div class="sk-print-area">
					<?php foreach ( $orders as $order ) : ?>
						<div class="sk-print-card">
							<h3><?php echo esc_html( sprintf( __( 'Invoice #%s', 'skincare' ), $order->get_order_number() ) ); ?></h3>
							<p><strong><?php esc_html_e( 'Invoice number:', 'skincare' ); ?></strong> <?php echo esc_html( get_post_meta( $order->get_id(), '_sk_invoice_number', true ) ); ?></p>
							<p><strong><?php esc_html_e( 'Customer:', 'skincare' ); ?></strong> <?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></p>
							<p><strong><?php esc_html_e( 'Total:', 'skincare' ); ?></strong> <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
							<ul>
								<?php foreach ( $order->get_items() as $item ) : ?>
									<li><?php echo esc_html( $item->get_name() . ' × ' . $item->get_quantity() ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
