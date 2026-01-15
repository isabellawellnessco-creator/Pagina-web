<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Stock_Manager {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'wp_ajax_sk_add_stock', [ __CLASS__, 'ajax_add_stock' ] );
		add_action( 'wp_ajax_sk_search_products', [ __CLASS__, 'ajax_search_products' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Gestión de Stock', 'skincare' ),
			__( 'Ingreso Stock', 'skincare' ),
			'manage_woocommerce',
			'sk-stock-manager',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Ingreso Rápido de Stock', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Busca un producto y añade stock inmediatamente.', 'skincare' ); ?></p>

			<div class="sk-admin-card" style="max-width: 600px;">
				<form id="sk-stock-form">
					<div style="margin-bottom: 20px;">
						<label><strong><?php _e( 'Buscar Producto', 'skincare' ); ?></strong></label>
						<select id="sk-product-search" class="widefat" style="width: 100%;"></select>
					</div>

					<div style="display: flex; gap: 20px; margin-bottom: 20px;">
						<div style="flex: 1;">
							<label><strong><?php _e( 'Cantidad a añadir', 'skincare' ); ?></strong></label>
							<input type="number" id="sk-stock-qty" class="widefat" min="1" value="1">
						</div>
						<div style="flex: 1;">
							<label><strong><?php _e( 'Costo Unitario (S/ - Opcional)', 'skincare' ); ?></strong></label>
							<input type="number" id="sk-stock-cost" class="widefat" step="0.01" placeholder="0.00">
						</div>
					</div>

					<div style="margin-bottom: 20px;">
						<label><strong><?php _e( 'Nota / Proveedor', 'skincare' ); ?></strong></label>
						<textarea id="sk-stock-note" class="widefat" rows="2"></textarea>
					</div>

					<button type="submit" class="button button-primary button-hero">
						<?php _e( 'Guardar Ingreso', 'skincare' ); ?>
					</button>
				</form>
				<div id="sk-stock-result" style="margin-top: 20px; display: none;"></div>
			</div>

			<div class="sk-admin-card" style="margin-top: 20px;">
				<h2><?php _e( 'Últimos Movimientos', 'skincare' ); ?></h2>
				<table class="widefat striped">
					<thead>
						<tr>
							<th><?php _e( 'Fecha', 'skincare' ); ?></th>
							<th><?php _e( 'Producto', 'skincare' ); ?></th>
							<th><?php _e( 'Cantidad', 'skincare' ); ?></th>
							<th><?php _e( 'Nota', 'skincare' ); ?></th>
							<th><?php _e( 'Usuario', 'skincare' ); ?></th>
						</tr>
					</thead>
					<tbody id="sk-stock-history">
						<?php self::render_history_rows(); ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Select2 CSS/JS needs to be loaded by WP if not already. Assuming WC loads it, otherwise we might need to enqueue.
		     However, standard WC admin pages usually have select2. We'll use standard WP ajax. -->
		<script>
		jQuery(document).ready(function($) {
			// Init Select2
			if ($.fn.select2) {
				$('#sk-product-search').select2({
					ajax: {
						url: ajaxurl,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term,
								action: 'sk_search_products'
							};
						},
						processResults: function (data) {
							return {
								results: data.data
							};
						},
						cache: true
					},
					minimumInputLength: 3,
					placeholder: '<?php _e( "Escribe nombre o SKU...", "skincare" ); ?>'
				});
			}

			// Handle Submit
			$('#sk-stock-form').on('submit', function(e) {
				e.preventDefault();
				var productId = $('#sk-product-search').val();
				var qty = $('#sk-stock-qty').val();
				var cost = $('#sk-stock-cost').val();
				var note = $('#sk-stock-note').val();

				if (!productId) {
					alert('Selecciona un producto');
					return;
				}

				$.post(ajaxurl, {
					action: 'sk_add_stock',
					product_id: productId,
					qty: qty,
					cost: cost,
					note: note,
					nonce: '<?php echo wp_create_nonce( "sk_stock_action" ); ?>'
				}, function(response) {
					if (response.success) {
						$('#sk-stock-result').html('<div class="notice notice-success inline"><p>' + response.data.message + '</p></div>').show();
						// Reset
						$('#sk-stock-qty').val(1);
						$('#sk-stock-cost').val('');
						$('#sk-stock-note').val('');
						$('#sk-product-search').val(null).trigger('change');
						// Reload history (simple append for now)
						location.reload();
					} else {
						alert('Error: ' + response.data);
					}
				});
			});
		});
		</script>
		<?php
	}

	private static function render_history_rows() {
		// This should pull from a custom table or meta logic.
		// For MVP, we will just list latest notes or a dedicated log if we had one.
		// Since we don't have a custom table yet, we can query posts with 'product' type and check recent revisions/logs?
		// Actually, standard WC logs stock changes in notes.
		// For now, let's leave it empty or show a placeholder, as the user asked for "Quick Stock Add" primarily.
		echo '<tr><td colspan="5">' . __( 'El historial se visualizará en la siguiente versión.', 'skincare' ) . '</td></tr>';
	}

	public static function ajax_search_products() {
		$term = sanitize_text_field( $_GET['q'] );
		$args = [
			'post_type' => ['product', 'product_variation'],
			'post_status' => 'publish',
			'posts_per_page' => 20,
			's' => $term
		];
		$query = new \WP_Query( $args );
		$results = [];
		foreach ( $query->posts as $post ) {
			$product = wc_get_product( $post->ID );
			if ( $product ) {
				$results[] = [
					'id' => $post->ID,
					'text' => $product->get_formatted_name() . ' (Stock: ' . $product->get_stock_quantity() . ')'
				];
			}
		}
		wp_send_json_success( $results );
	}

	public static function ajax_add_stock() {
		check_ajax_referer( 'sk_stock_action', 'nonce' );

		$product_id = absint( $_POST['product_id'] );
		$qty = intval( $_POST['qty'] );
		$cost = sanitize_text_field( $_POST['cost'] );
		$note = sanitize_textarea_field( $_POST['note'] );

		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json_error( 'Producto inválido' );
		}

		// Update Stock
		$current_stock = $product->get_stock_quantity();
		$new_stock = $current_stock + $qty;
		$product->set_stock_quantity( $new_stock );
		$product->save();

		// Log
		$log_msg = sprintf( 'Stock añadido manual: +%d. Costo: %s. Nota: %s. Usuario: %s', $qty, $cost, $note, wp_get_current_user()->display_name );
		// We can add a specialized meta or just use WC logging
		// Ideally we would have a custom table 'sk_stock_log', but let's stick to MVP requirements.

		wp_send_json_success( [
			'message' => sprintf( __( 'Stock actualizado. Nuevo total: %d', 'skincare' ), $new_stock )
		] );
	}
}
