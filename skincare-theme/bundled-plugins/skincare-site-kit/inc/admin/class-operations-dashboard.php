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

		// Whatsapp Templates
		$whatsapp_templates = class_exists( '\Skincare\SiteKit\Admin\Whatsapp_Templates' )
			? \Skincare\SiteKit\Admin\Whatsapp_Templates::get_templates()
			: [];
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Operations Dashboard', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Pedidos, almacén y puntos en una sola vista.', 'skincare' ); ?></p>

			<div class="sk-admin-hero">
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

			<!-- WhatsApp Modal -->
			<div id="sk-whatsapp-modal" class="sk-modal" style="display:none;">
				<div class="sk-modal-content">
					<span class="sk-close-modal">&times;</span>
					<h3><?php esc_html_e( 'Enviar WhatsApp al Cliente', 'skincare' ); ?></h3>

					<div class="sk-modal-body">
						<label><strong>1. Selecciona Plantilla</strong></label>
						<select id="sk-wa-template-select" class="widefat" style="margin-bottom: 15px;">
							<option value=""><?php esc_html_e( '-- Seleccionar --', 'skincare' ); ?></option>
							<?php foreach ( $whatsapp_templates as $tpl ) : ?>
								<option value="<?php echo esc_attr( $tpl['id'] ); ?>" data-message="<?php echo esc_attr( $tpl['message'] ); ?>">
									<?php echo esc_html( $tpl['title'] ); ?>
								</option>
							<?php endforeach; ?>
						</select>

						<div id="sk-wa-extra-fields" style="display:none; margin-bottom: 15px; padding: 10px; background: #f0f0f1; border-radius: 4px;">
							<label><strong>Fecha Estimada de Llegada</strong> (Opcional)</label>
							<input type="date" id="sk-wa-delivery-date" class="widefat" value="<?php echo date( 'Y-m-d', strtotime( '+2 days' ) ); ?>">
						</div>

						<label><strong>2. Vista Previa / Editar Mensaje</strong></label>
						<textarea id="sk-wa-message-preview" rows="8" class="widefat" style="margin-bottom: 15px;"></textarea>

						<div style="text-align: right;">
							<a href="#" id="sk-wa-send-btn" target="_blank" class="button button-primary button-hero" style="background-color: #25D366; border-color: #25D366;">
								<span class="dashicons dashicons-whatsapp" style="margin-top: 5px;"></span> Abrir WhatsApp Web
							</a>
						</div>
					</div>
				</div>
			</div>

			<style>
				.sk-modal { position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; }
				.sk-modal-content { background-color: #fff; padding: 20px; border-radius: 8px; width: 500px; max-width: 90%; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
				.sk-close-modal { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #aaa; }
				.sk-close-modal:hover { color: #000; }
			</style>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const modal = document.getElementById('sk-whatsapp-modal');
					const closeBtn = document.querySelector('.sk-close-modal');
					const templateSelect = document.getElementById('sk-wa-template-select');
					const messageArea = document.getElementById('sk-wa-message-preview');
					const dateField = document.getElementById('sk-wa-delivery-date');
					const extraFields = document.getElementById('sk-wa-extra-fields');
					const sendBtn = document.getElementById('sk-wa-send-btn');

					let currentOrder = {};

					// Open Modal
					document.querySelectorAll('.sk-open-whatsapp').forEach(btn => {
						btn.addEventListener('click', function(e) {
							e.preventDefault();
							currentOrder = {
								phone: this.dataset.phone,
								name: this.dataset.name,
								id: this.dataset.id,
								total: this.dataset.total,
								address: this.dataset.address,
								tracking: this.dataset.tracking
							};

							// Reset fields
							templateSelect.value = '';
							messageArea.value = '';
							extraFields.style.display = 'none';

							modal.style.display = 'flex';
						});
					});

					// Close Modal
					closeBtn.onclick = () => modal.style.display = 'none';
					window.onclick = (e) => { if (e.target == modal) modal.style.display = 'none'; }

					// Update Template
					templateSelect.addEventListener('change', function() {
						const option = this.options[this.selectedIndex];
						if (!option.value) return;

						let msg = option.dataset.message;

						// Show date field if needed
						if (msg.includes('{delivery_date}')) {
							extraFields.style.display = 'block';
						} else {
							extraFields.style.display = 'none';
						}

						updatePreview();
					});

					// Dynamic Update
					dateField.addEventListener('change', updatePreview);

					function updatePreview() {
						const option = templateSelect.options[templateSelect.selectedIndex];
						if (!option.value) return;

						let msg = option.dataset.message;

						msg = msg.replace(/{customer_name}/g, currentOrder.name)
								 .replace(/{order_id}/g, currentOrder.id)
								 .replace(/{total}/g, currentOrder.total)
								 .replace(/{address}/g, currentOrder.address)
								 .replace(/{tracking_url}/g, currentOrder.tracking || 'Pendiente')
								 .replace(/{delivery_date}/g, dateField.value);

						messageArea.value = msg;
						updateLink();
					}

					messageArea.addEventListener('input', updateLink);

					function updateLink() {
						const text = encodeURIComponent(messageArea.value);
						const phone = currentOrder.phone.replace(/\D/g, ''); // Clean phone
						sendBtn.href = `https://wa.me/${phone}?text=${text}`;
					}
				});
			</script>
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
							<td>
								<?php echo esc_html( $order->get_formatted_billing_full_name() ); ?>
								<?php if ( $order->get_billing_phone() ) : ?>
									<br><small class="sk-text-muted"><?php echo esc_html( $order->get_billing_phone() ); ?></small>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
							<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
							<td>
								<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $order->get_id() ) ); ?>">
									<?php esc_html_e( 'Ver', 'skincare' ); ?>
								</a>
								<?php if ( $order->get_billing_phone() ) : ?>
									<button type="button" class="button button-small sk-open-whatsapp"
										data-phone="<?php echo esc_attr( $order->get_billing_phone() ); ?>"
										data-name="<?php echo esc_attr( $order->get_billing_first_name() ); ?>"
										data-id="<?php echo esc_attr( $order->get_order_number() ); ?>"
										data-total="<?php echo esc_attr( $order->get_formatted_order_total() ); ?>"
										data-address="<?php echo esc_attr( $order->get_billing_address_1() . ' ' . $order->get_billing_city() ); ?>"
										data-tracking="<?php echo esc_attr( $order->get_meta( '_sk_tracking_url', true ) ); ?>"
										title="Enviar WhatsApp"
										style="color: #25D366; border-color: #25D366;">
										<span class="dashicons dashicons-whatsapp"></span>
									</button>
								<?php endif; ?>
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
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}
}
