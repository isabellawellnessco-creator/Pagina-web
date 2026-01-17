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
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'SLA Monitor', 'skincare' ); ?></h1>
			<div class="sk-admin-hero">
				<form method="get" class="sk-admin-filter-bar">
					<input type="hidden" name="page" value="sk-sla-monitor">
					<label for="threshold">
						<?php esc_html_e( 'Hours threshold', 'skincare' ); ?>
						<input type="number" name="threshold" id="threshold" value="<?php echo esc_attr( $threshold_hours ); ?>" min="1" step="1">
					</label>
					<?php submit_button( __( 'Filter', 'skincare' ), 'secondary', 'submit', false ); ?>
				</form>
			</div>
			<table class="widefat striped sk-admin-table">
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
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Batch Picking', 'skincare' ); ?></h1>
			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Batch updated.', 'skincare' ); ?></p></div>
			<?php endif; ?>
			<div class="sk-admin-hero">
				<form method="post" class="sk-admin-filter-bar">
					<label for="batch_id">
						<?php esc_html_e( 'Batch ID', 'skincare' ); ?>
						<input type="text" name="batch_id" id="batch_id" class="regular-text" placeholder="BATCH-2025-001">
					</label>
					<label for="order_ids">
						<?php esc_html_e( 'Order IDs', 'skincare' ); ?>
						<input type="text" name="order_ids" id="order_ids" class="large-text" placeholder="1234,1235,1236">
					</label>
					<?php submit_button( __( 'Assign batch', 'skincare' ) ); ?>
				</form>
			</div>
		</div>
		<?php
	}

	public static function render_dashboard() {
		$templates_updated = false;
		if ( isset( $_POST['sk_fulfillment_templates_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sk_fulfillment_templates_nonce'] ) ), 'sk_fulfillment_templates' ) ) {
			$label_template = isset( $_POST['sk_label_template'] ) ? wp_kses_post( wp_unslash( $_POST['sk_label_template'] ) ) : '';
			$invoice_template = isset( $_POST['sk_invoice_template'] ) ? wp_kses_post( wp_unslash( $_POST['sk_invoice_template'] ) ) : '';
			update_option( 'sk_fulfillment_label_template', $label_template );
			update_option( 'sk_fulfillment_invoice_template', $invoice_template );
			$templates_updated = true;
		}

		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		$preview_order = self::get_preview_order( $orders );
		$placeholders = self::get_template_placeholders( $preview_order );
		$label_template_value = get_option( 'sk_fulfillment_label_template', '' );
		$invoice_template_value = get_option( 'sk_fulfillment_invoice_template', '' );
		$label_template_display = $label_template_value !== '' ? $label_template_value : self::get_default_template( 'label' );
		$invoice_template_display = $invoice_template_value !== '' ? $invoice_template_value : self::get_default_template( 'invoice' );
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Fulfillment Center', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Gestiona packing, etiquetas y facturación en una sola vista.', 'skincare' ); ?></p>

			<nav class="sk-admin-tabs" aria-label="<?php esc_attr_e( 'Fulfillment sections', 'skincare' ); ?>">
				<button class="sk-admin-tab is-active" data-target="sk-fulfillment-overview"><?php esc_html_e( 'Overview', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-fulfillment-packing"><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-fulfillment-labels"><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-fulfillment-invoices"><?php esc_html_e( 'Invoices', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-fulfillment-templates"><?php esc_html_e( 'Plantillas', 'skincare' ); ?></button>
			</nav>

			<section id="sk-fulfillment-overview" class="sk-admin-panel is-active">
				<div class="sk-admin-panel-grid">
					<div class="sk-admin-card">
						<h2><?php esc_html_e( 'Pedidos recientes', 'skincare' ); ?></h2>
						<p><?php esc_html_e( 'Accede rápido a los pedidos que necesitan despacho.', 'skincare' ); ?></p>
						<?php self::render_recent_orders_table(); ?>
					</div>
					<div class="sk-admin-card">
						<h2><?php esc_html_e( 'Atajos', 'skincare' ); ?></h2>
						<p><?php esc_html_e( 'Genera documentos sin salir de esta pantalla.', 'skincare' ); ?></p>
						<ul class="sk-admin-quick-links">
							<li><a href="#sk-fulfillment-packing"><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></a></li>
							<li><a href="#sk-fulfillment-labels"><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></a></li>
							<li><a href="#sk-fulfillment-invoices"><?php esc_html_e( 'Invoices', 'skincare' ); ?></a></li>
						</ul>
					</div>
				</div>
			</section>

			<section id="sk-fulfillment-packing" class="sk-admin-panel">
				<h2><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></h2>
				<?php self::render_order_form( __( 'Packing slips generator', 'skincare' ), 'sk-fulfillment-center' ); ?>
				<?php self::render_packing_cards( $orders ); ?>
			</section>

			<section id="sk-fulfillment-labels" class="sk-admin-panel">
				<h2><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></h2>
				<?php self::render_order_form( __( 'Shipping labels generator', 'skincare' ), 'sk-fulfillment-center' ); ?>
				<?php self::render_shipping_cards( $orders ); ?>
			</section>

			<section id="sk-fulfillment-invoices" class="sk-admin-panel">
				<h2><?php esc_html_e( 'Invoices & Boletas', 'skincare' ); ?></h2>
				<?php self::render_order_form( __( 'Invoice generator', 'skincare' ), 'sk-fulfillment-center' ); ?>
				<?php self::render_invoice_cards( $orders ); ?>
			</section>

			<section id="sk-fulfillment-templates" class="sk-admin-panel">
				<h2><?php esc_html_e( 'Plantillas de documentos', 'skincare' ); ?></h2>
				<p><?php esc_html_e( 'Edita el diseño base de etiquetas de envío y comprobantes. Usa los placeholders para imprimir datos de pedidos.', 'skincare' ); ?></p>
				<?php if ( $templates_updated ) : ?>
					<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Plantillas actualizadas.', 'skincare' ); ?></p></div>
				<?php endif; ?>
				<div class="sk-admin-panel-grid">
					<div class="sk-admin-card">
						<h3><?php esc_html_e( 'Placeholders disponibles', 'skincare' ); ?></h3>
						<table class="widefat striped">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Placeholder', 'skincare' ); ?></th>
									<th><?php esc_html_e( 'Descripción', 'skincare' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( self::get_placeholder_catalog() as $placeholder => $label ) : ?>
									<tr>
										<td><code>{{<?php echo esc_html( $placeholder ); ?>}}</code></td>
										<td><?php echo esc_html( $label ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php if ( ! $preview_order ) : ?>
							<p class="description"><?php esc_html_e( 'No se encontró un pedido para mostrar previsualización. Crea un pedido y vuelve a cargar.', 'skincare' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<form method="post" class="sk-admin-filter-bar">
					<?php wp_nonce_field( 'sk_fulfillment_templates', 'sk_fulfillment_templates_nonce' ); ?>
					<div class="sk-admin-panel-grid">
						<div class="sk-admin-card">
							<label for="sk-label-template"><strong><?php esc_html_e( 'Etiqueta de envío (HTML)', 'skincare' ); ?></strong></label>
							<textarea id="sk-label-template" name="sk_label_template" rows="12" class="large-text code"><?php echo esc_textarea( $label_template_display ); ?></textarea>
							<h4><?php esc_html_e( 'Vista previa', 'skincare' ); ?></h4>
							<div class="sk-template-preview">
								<?php echo wp_kses_post( self::render_template_preview( $label_template_display, $placeholders ) ); ?>
							</div>
						</div>
						<div class="sk-admin-card">
							<label for="sk-invoice-template"><strong><?php esc_html_e( 'Comprobante SUNAT / boleta (HTML)', 'skincare' ); ?></strong></label>
							<textarea id="sk-invoice-template" name="sk_invoice_template" rows="12" class="large-text code"><?php echo esc_textarea( $invoice_template_display ); ?></textarea>
							<h4><?php esc_html_e( 'Vista previa', 'skincare' ); ?></h4>
							<div class="sk-template-preview">
								<?php echo wp_kses_post( self::render_template_preview( $invoice_template_display, $placeholders ) ); ?>
							</div>
						</div>
					</div>
					<?php submit_button( __( 'Guardar cambios', 'skincare' ) ); ?>
				</form>
				<style>
					.sk-template-preview { border: 1px dashed #ccd0d4; padding: 12px; background: #fcfcfc; }
				</style>
			</section>
		</div>
		<?php
	}

	private static function get_default_template( $type ) {
		$defaults = [
			'label' => '<h3>Etiqueta de envío</h3><p><strong>Pedido:</strong> {{order_number}}</p><p><strong>Cliente:</strong> {{customer_name}}</p><p><strong>Dirección:</strong><br>{{shipping_address}}</p><p><strong>Teléfono:</strong> {{phone}}</p>',
			'invoice' => '<h3>Comprobante</h3><p><strong>Pedido:</strong> {{order_number}}</p><p><strong>Cliente:</strong> {{customer_name}}</p><p><strong>Email:</strong> {{email}}</p><p><strong>Total:</strong> {{order_total}}</p><h4>Items</h4>{{items_list}}',
		];

		return $defaults[ $type ] ?? '';
	}

	private static function get_preview_order( $orders ) {
		if ( ! empty( $orders ) ) {
			return $orders[0];
		}

		$latest = wc_get_orders( [
			'limit' => 1,
			'status' => [ 'processing', 'on-hold', 'pending', 'completed' ],
			'orderby' => 'date',
			'order' => 'DESC',
		] );

		return $latest ? $latest[0] : null;
	}

	private static function get_placeholder_catalog() {
		return [
			'order_number' => __( 'Número de pedido', 'skincare' ),
			'customer_name' => __( 'Nombre completo del cliente', 'skincare' ),
			'shipping_address' => __( 'Dirección de envío formateada', 'skincare' ),
			'billing_address' => __( 'Dirección de facturación formateada', 'skincare' ),
			'order_total' => __( 'Total del pedido', 'skincare' ),
			'phone' => __( 'Teléfono del cliente', 'skincare' ),
			'email' => __( 'Email del cliente', 'skincare' ),
			'order_date' => __( 'Fecha del pedido', 'skincare' ),
			'items_list' => __( 'Listado de productos (HTML)', 'skincare' ),
		];
	}

	private static function get_template_placeholders( $order ) {
		if ( ! $order ) {
			return [
				'order_number' => '—',
				'customer_name' => '—',
				'shipping_address' => '—',
				'billing_address' => '—',
				'order_total' => '—',
				'phone' => '—',
				'email' => '—',
				'order_date' => '—',
				'items_list' => '<ul><li>—</li></ul>',
			];
		}

		$items = '';
		foreach ( $order->get_items() as $item ) {
			$items .= '<li>' . esc_html( $item->get_name() . ' × ' . $item->get_quantity() ) . '</li>';
		}
		$items_list = $items ? '<ul>' . $items . '</ul>' : '<ul><li>—</li></ul>';

		return [
			'order_number' => $order->get_order_number(),
			'customer_name' => $order->get_formatted_billing_full_name(),
			'shipping_address' => $order->get_formatted_shipping_address() ?: '—',
			'billing_address' => $order->get_formatted_billing_address() ?: '—',
			'order_total' => wp_kses_post( $order->get_formatted_order_total() ),
			'phone' => $order->get_billing_phone() ?: '—',
			'email' => $order->get_billing_email() ?: '—',
			'order_date' => $order->get_date_created() ? $order->get_date_created()->date_i18n( 'Y-m-d H:i' ) : '—',
			'items_list' => $items_list,
		];
	}

	private static function render_template_preview( $template, $placeholders ) {
		$replacement = [];
		foreach ( $placeholders as $key => $value ) {
			$replacement[ '{{' . $key . '}}' ] = $value;
		}

		return strtr( $template, $replacement );
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

	private static function render_order_form( $title, $page_slug ) {
		?>
		<h2><?php echo esc_html( $title ); ?></h2>
		<form method="get" action="" class="sk-admin-filter-bar">
			<input type="hidden" name="page" value="<?php echo esc_attr( $page_slug ); ?>">
			<p>
				<label for="order-ids"><strong><?php esc_html_e( 'Order IDs (comma separated)', 'skincare' ); ?></strong></label>
				<input type="text" class="large-text" name="order_ids" id="order-ids" placeholder="1234, 1235, 1236">
			</p>
			<?php submit_button( __( 'Generate', 'skincare' ), 'primary', 'submit', false ); ?>
		</form>
		<?php
	}

	private static function render_recent_orders_table() {
		$orders = wc_get_orders( [
			'limit' => 8,
			'status' => [ 'processing', 'on-hold', 'pending' ],
			'orderby' => 'date',
			'order' => 'DESC',
		] );
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
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	private static function render_packing_cards( $orders ) {
		if ( ! $orders ) {
			return;
		}
		?>
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
		<?php
	}

	private static function render_shipping_cards( $orders ) {
		if ( ! $orders ) {
			return;
		}
		?>
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
		<?php
	}

	private static function render_invoice_cards( $orders ) {
		if ( ! $orders ) {
			return;
		}
		?>
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
		<?php
	}

	public static function render_packing_slips() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Packing Slips', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Packing slips generator', 'skincare' ), 'sk-packing-slips' ); ?>
			<?php self::render_packing_cards( $orders ); ?>
		</div>
		<?php
	}

	public static function render_shipping_labels() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Shipping Labels', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Shipping labels generator', 'skincare' ), 'sk-shipping-labels' ); ?>
			<?php self::render_shipping_cards( $orders ); ?>
		</div>
		<?php
	}

	public static function render_invoices() {
		$order_ids = self::parse_order_ids();
		$orders = self::get_orders_from_ids( $order_ids );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Invoices & Boletas', 'skincare' ); ?></h1>
			<?php self::render_order_form( __( 'Invoice generator', 'skincare' ), 'sk-invoices' ); ?>
			<?php self::render_invoice_cards( $orders ); ?>
		</div>
		<?php
	}
}
