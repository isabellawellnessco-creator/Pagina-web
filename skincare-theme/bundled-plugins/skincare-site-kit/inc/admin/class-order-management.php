<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Order_Management {
	const NONCE_FIELD = 'sk_order_management_nonce';
	const NONCE_ACTION = 'sk_order_management_save';

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_order_statuses' ] );
		add_filter( 'wc_order_statuses', [ __CLASS__, 'add_order_statuses' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_box' ] );
		add_action( 'save_post_shop_order', [ __CLASS__, 'save_meta_box' ] );
		add_filter( 'manage_edit-shop_order_columns', [ __CLASS__, 'register_order_columns' ], 20 );
		add_action( 'manage_shop_order_posts_custom_column', [ __CLASS__, 'render_order_columns' ], 20, 2 );
	}

	public static function register_order_statuses() {
		register_post_status(
			'wc-sk-on-the-way',
			[
				'label' => _x( 'En camino', 'Order status', 'skincare' ),
				'public' => true,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				'label_count' => _n_noop( 'En camino (%s)', 'En camino (%s)', 'skincare' ),
			]
		);

		register_post_status(
			'wc-sk-delivered',
			[
				'label' => _x( 'Entregado', 'Order status', 'skincare' ),
				'public' => true,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				'label_count' => _n_noop( 'Entregado (%s)', 'Entregado (%s)', 'skincare' ),
			]
		);
	}

	public static function add_order_statuses( $statuses ) {
		$updated = [];
		foreach ( $statuses as $key => $label ) {
			$updated[ $key ] = $label;
			if ( 'wc-processing' === $key ) {
				$updated['wc-sk-on-the-way'] = _x( 'En camino', 'Order status', 'skincare' );
				$updated['wc-sk-delivered'] = _x( 'Entregado', 'Order status', 'skincare' );
			}
		}

		return $updated;
	}

	public static function register_meta_box() {
		add_meta_box(
			'sk-order-management',
			__( 'Seguimiento interno (almacén)', 'skincare' ),
			[ __CLASS__, 'render_meta_box' ],
			'shop_order',
			'side',
			'default'
		);
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$order = wc_get_order( $post->ID );
		$warehouse = get_post_meta( $post->ID, '_sk_warehouse_location', true );
		$rating = get_post_meta( $post->ID, '_sk_order_rating', true );
		$priority = get_post_meta( $post->ID, '_sk_order_priority', true );
		$packing_status = get_post_meta( $post->ID, '_sk_packing_status', true );
		$public_status = get_post_meta( $post->ID, '_sk_public_status', true );
		$tracking_number = get_post_meta( $post->ID, '_sk_tracking_number', true );
		$carrier = get_post_meta( $post->ID, '_sk_carrier', true );
		$tracking_url = get_post_meta( $post->ID, '_sk_tracking_url', true );
		$internal_notes = get_post_meta( $post->ID, '_sk_internal_notes', true );
		$picker_id = get_post_meta( $post->ID, '_sk_picker_id', true );
		$picking_batch_id = get_post_meta( $post->ID, '_sk_picking_batch_id', true );
		$package_weight = get_post_meta( $post->ID, '_sk_package_weight', true );
		$package_dimensions = get_post_meta( $post->ID, '_sk_package_dimensions', true );
		$invoice_number = get_post_meta( $post->ID, '_sk_invoice_number', true );
		$packing_checklist = get_post_meta( $post->ID, '_sk_packing_checklist', true );
		$awarded_points = get_post_meta( $post->ID, '_sk_rewards_awarded', true );
		$user_id = $order ? $order->get_user_id() : 0;
		$user_points = $user_id ? (int) get_user_meta( $user_id, '_sk_rewards_points', true ) : 0;
		$billing_phone = $order ? $order->get_billing_phone() : '';
		$billing_phone = self::sanitize_phone( $billing_phone );

		$rating_options = [ '', '1', '2', '3', '4', '5' ];
		$priority_options = [ '', __( 'Baja', 'skincare' ), __( 'Media', 'skincare' ), __( 'Alta', 'skincare' ) ];
		$packing_options = [ '', __( 'Pendiente', 'skincare' ), __( 'En preparación', 'skincare' ), __( 'Listo para envío', 'skincare' ), __( 'Enviado', 'skincare' ) ];
		$public_status_options = [
			'' => __( 'No actualizar estado del cliente', 'skincare' ),
			'sk-on-the-way' => __( 'En camino', 'skincare' ),
			'sk-delivered' => __( 'Entregado', 'skincare' ),
		];
		$packing_checklist = is_array( $packing_checklist ) ? $packing_checklist : [];
		$picker_users = get_users( [ 'role__in' => [ 'administrator', 'shop_manager' ] ] );
		$whatsapp_links = self::build_whatsapp_links( $order, $billing_phone );
		?>
		<p>
			<label for="sk-warehouse"><strong><?php _e( 'Almacén', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_warehouse_location" id="sk-warehouse" value="<?php echo esc_attr( $warehouse ); ?>" placeholder="<?php esc_attr_e( 'Ej: Lima - Central', 'skincare' ); ?>">
		</p>
		<p>
			<label for="sk-order-rating"><strong><?php _e( 'Calificación del pedido', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_order_rating" id="sk-order-rating">
				<?php foreach ( $rating_options as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $rating, $option ); ?>>
						<?php echo $option ? esc_html( $option . ' / 5' ) : esc_html__( 'Seleccionar', 'skincare' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="sk-order-priority"><strong><?php _e( 'Prioridad interna', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_order_priority" id="sk-order-priority">
				<?php foreach ( $priority_options as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $priority, $option ); ?>>
						<?php echo $option ? esc_html( $option ) : esc_html__( 'Seleccionar', 'skincare' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="sk-packing-status"><strong><?php _e( 'Estado de almacén', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_packing_status" id="sk-packing-status">
				<?php foreach ( $packing_options as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $packing_status, $option ); ?>>
						<?php echo $option ? esc_html( $option ) : esc_html__( 'Seleccionar', 'skincare' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="sk-public-status"><strong><?php _e( 'Estado visible al cliente', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_public_status" id="sk-public-status">
				<?php foreach ( $public_status_options as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $public_status, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<small><?php _e( 'Solo estos estados se mostrarán en la cuenta del cliente.', 'skincare' ); ?></small>
		</p>
		<p>
			<label for="sk-carrier"><strong><?php _e( 'Transportista', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_carrier" id="sk-carrier" value="<?php echo esc_attr( $carrier ); ?>" placeholder="<?php esc_attr_e( 'Ej: Olva, Shalom', 'skincare' ); ?>">
		</p>
		<p>
			<label for="sk-tracking-number"><strong><?php _e( 'Número de guía', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_tracking_number" id="sk-tracking-number" value="<?php echo esc_attr( $tracking_number ); ?>">
		</p>
		<p>
			<label for="sk-tracking-url"><strong><?php _e( 'URL de tracking', 'skincare' ); ?></strong></label>
			<input type="url" class="widefat" name="sk_tracking_url" id="sk-tracking-url" value="<?php echo esc_attr( $tracking_url ); ?>" placeholder="https://">
		</p>
		<p>
			<label for="sk-internal-notes"><strong><?php _e( 'Notas internas', 'skincare' ); ?></strong></label>
			<textarea class="widefat" name="sk_internal_notes" id="sk-internal-notes" rows="4"><?php echo esc_textarea( $internal_notes ); ?></textarea>
		</p>
		<p>
			<label for="sk-picking-batch"><strong><?php _e( 'Batch picking', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_picking_batch_id" id="sk-picking-batch" value="<?php echo esc_attr( $picking_batch_id ); ?>" placeholder="<?php esc_attr_e( 'Ej: BATCH-2025-001', 'skincare' ); ?>">
		</p>
		<p>
			<label for="sk-picker"><strong><?php _e( 'Asignado a', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_picker_id" id="sk-picker">
				<option value=""><?php esc_html_e( 'Seleccionar', 'skincare' ); ?></option>
				<?php foreach ( $picker_users as $user ) : ?>
					<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $picker_id, $user->ID ); ?>>
						<?php echo esc_html( $user->display_name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="sk-package-weight"><strong><?php _e( 'Peso del paquete', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_package_weight" id="sk-package-weight" value="<?php echo esc_attr( $package_weight ); ?>" placeholder="<?php esc_attr_e( 'Ej: 1.2 kg', 'skincare' ); ?>">
		</p>
		<p>
			<label for="sk-package-dimensions"><strong><?php _e( 'Dimensiones', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_package_dimensions" id="sk-package-dimensions" value="<?php echo esc_attr( $package_dimensions ); ?>" placeholder="<?php esc_attr_e( 'Ej: 20x15x10 cm', 'skincare' ); ?>">
		</p>
		<p>
			<label for="sk-invoice-number"><strong><?php _e( 'Número de boleta/factura', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_invoice_number" id="sk-invoice-number" value="<?php echo esc_attr( $invoice_number ); ?>">
		</p>
		<div>
			<strong><?php _e( 'Checklist de empaque', 'skincare' ); ?></strong>
			<p><label><input type="checkbox" name="sk_packing_checklist[]" value="items" <?php checked( in_array( 'items', $packing_checklist, true ) ); ?>> <?php esc_html_e( 'Productos correctos', 'skincare' ); ?></label></p>
			<p><label><input type="checkbox" name="sk_packing_checklist[]" value="sealed" <?php checked( in_array( 'sealed', $packing_checklist, true ) ); ?>> <?php esc_html_e( 'Sellado correcto', 'skincare' ); ?></label></p>
			<p><label><input type="checkbox" name="sk_packing_checklist[]" value="invoice" <?php checked( in_array( 'invoice', $packing_checklist, true ) ); ?>> <?php esc_html_e( 'Boleta/factura incluida', 'skincare' ); ?></label></p>
			<p><label><input type="checkbox" name="sk_packing_checklist[]" value="label" <?php checked( in_array( 'label', $packing_checklist, true ) ); ?>> <?php esc_html_e( 'Etiqueta pegada', 'skincare' ); ?></label></p>
		</div>
		<div class="sk-fulfillment-actions">
			<p><strong><?php esc_html_e( 'Acciones rápidas', 'skincare' ); ?></strong></p>
			<p>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-packing-slips&order_ids=' . $post->ID ) ); ?>" target="_blank"><?php esc_html_e( 'Packing Slip', 'skincare' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-shipping-labels&order_ids=' . $post->ID ) ); ?>" target="_blank"><?php esc_html_e( 'Etiqueta envío', 'skincare' ); ?></a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-invoices&order_ids=' . $post->ID ) ); ?>" target="_blank"><?php esc_html_e( 'Boleta/Factura', 'skincare' ); ?></a>
			</p>
		</div>
		<p>
			<strong><?php _e( 'Puntos por este pedido', 'skincare' ); ?></strong><br>
			<?php echo $awarded_points ? esc_html( $awarded_points ) : '—'; ?><br>
			<strong><?php _e( 'Saldo del cliente', 'skincare' ); ?></strong><br>
			<?php echo $user_id ? esc_html( $user_points ) : '—'; ?>
		</p>
		<?php if ( $billing_phone ) : ?>
			<div class="sk-whatsapp-actions">
				<p><strong><?php _e( 'Acciones WhatsApp', 'skincare' ); ?></strong></p>
				<ul>
					<?php foreach ( $whatsapp_links as $label => $url ) : ?>
						<li><a class="button" target="_blank" rel="noopener" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php else : ?>
			<p><em><?php _e( 'No hay teléfono en el pedido para WhatsApp.', 'skincare' ); ?></em></p>
		<?php endif; ?>
		<?php
	}

	public static function save_meta_box( $post_id ) {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( $_POST[ self::NONCE_FIELD ], self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_shop_order', $post_id ) ) {
			return;
		}

		$fields = [
			'_sk_warehouse_location' => [ 'key' => 'sk_warehouse_location', 'sanitize' => 'sanitize_text_field' ],
			'_sk_order_rating' => [ 'key' => 'sk_order_rating', 'sanitize' => 'sanitize_text_field' ],
			'_sk_order_priority' => [ 'key' => 'sk_order_priority', 'sanitize' => 'sanitize_text_field' ],
			'_sk_packing_status' => [ 'key' => 'sk_packing_status', 'sanitize' => 'sanitize_text_field' ],
			'_sk_public_status' => [ 'key' => 'sk_public_status', 'sanitize' => 'sanitize_text_field' ],
			'_sk_tracking_number' => [ 'key' => 'sk_tracking_number', 'sanitize' => 'sanitize_text_field' ],
			'_sk_carrier' => [ 'key' => 'sk_carrier', 'sanitize' => 'sanitize_text_field' ],
			'_sk_tracking_url' => [ 'key' => 'sk_tracking_url', 'sanitize' => 'esc_url_raw' ],
			'_sk_internal_notes' => [ 'key' => 'sk_internal_notes', 'sanitize' => 'sanitize_textarea_field' ],
			'_sk_picker_id' => [ 'key' => 'sk_picker_id', 'sanitize' => 'absint' ],
			'_sk_picking_batch_id' => [ 'key' => 'sk_picking_batch_id', 'sanitize' => 'sanitize_text_field' ],
			'_sk_package_weight' => [ 'key' => 'sk_package_weight', 'sanitize' => 'sanitize_text_field' ],
			'_sk_package_dimensions' => [ 'key' => 'sk_package_dimensions', 'sanitize' => 'sanitize_text_field' ],
			'_sk_invoice_number' => [ 'key' => 'sk_invoice_number', 'sanitize' => 'sanitize_text_field' ],
		];

		foreach ( $fields as $meta_key => $field ) {
			if ( isset( $_POST[ $field['key'] ] ) ) {
				$value = wp_unslash( $_POST[ $field['key'] ] );
				$sanitized = call_user_func( $field['sanitize'], $value );
				update_post_meta( $post_id, $meta_key, $sanitized );
			}
		}

		if ( isset( $_POST['sk_packing_checklist'] ) && is_array( $_POST['sk_packing_checklist'] ) ) {
			$checklist = array_map( 'sanitize_text_field', wp_unslash( $_POST['sk_packing_checklist'] ) );
			update_post_meta( $post_id, '_sk_packing_checklist', $checklist );
		} else {
			delete_post_meta( $post_id, '_sk_packing_checklist' );
		}

		if ( ! empty( $_POST['sk_public_status'] ) ) {
			$order = wc_get_order( $post_id );
			if ( $order ) {
				$status = sanitize_text_field( wp_unslash( $_POST['sk_public_status'] ) );
				if ( in_array( $status, [ 'sk-on-the-way', 'sk-delivered' ], true ) ) {
					$order->update_status( $status );
				}
			}
		}
	}

	public static function register_order_columns( $columns ) {
		$columns['sk_warehouse_location'] = __( 'Almacén', 'skincare' );
		$columns['sk_order_rating'] = __( 'Calificación', 'skincare' );
		$columns['sk_packing_status'] = __( 'Estado almacén', 'skincare' );
		$columns['sk_picker'] = __( 'Asignado', 'skincare' );
		return $columns;
	}

	public static function render_order_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'sk_warehouse_location':
				echo esc_html( get_post_meta( $post_id, '_sk_warehouse_location', true ) );
				break;
			case 'sk_order_rating':
				$rating = get_post_meta( $post_id, '_sk_order_rating', true );
				echo $rating ? esc_html( $rating . '/5' ) : '—';
				break;
			case 'sk_packing_status':
				echo esc_html( get_post_meta( $post_id, '_sk_packing_status', true ) );
				break;
			case 'sk_picker':
				$picker_id = get_post_meta( $post_id, '_sk_picker_id', true );
				if ( $picker_id ) {
					$user = get_user_by( 'id', $picker_id );
					echo $user ? esc_html( $user->display_name ) : '—';
				} else {
					echo '—';
				}
				break;
		}
	}

	private static function sanitize_phone( $phone ) {
		return preg_replace( '/\D+/', '', (string) $phone );
	}

	private static function build_whatsapp_links( $order, $phone ) {
		if ( ! $order || ! $phone ) {
			return [];
		}

		$press_settings = get_option( 'sk_press_page_settings', [] );
		$order_number = $order->get_order_number();
		$total = $order->get_formatted_order_total();
		$carrier = get_post_meta( $order->get_id(), '_sk_carrier', true );
		$tracking_url = get_post_meta( $order->get_id(), '_sk_tracking_url', true );

		$placeholders = [
			'{order_number}' => $order_number,
			'{total}' => $total,
			'{carrier}' => $carrier ? sprintf( __( 'Transportista: %s.', 'skincare' ), $carrier ) : '',
			'{tracking_url}' => $tracking_url ? sprintf( __( 'Seguimiento: %s', 'skincare' ), $tracking_url ) : '',
		];

		$defaults = [
			'confirm' => __( 'Hola, confirmamos tu pedido #{order_number}. Total: {total}. ¿Pago confirmado o contraentrega?', 'skincare' ),
			'delivery' => __( 'Tu pedido #{order_number} fue asignado a delivery. Te contactaremos antes de la entrega.', 'skincare' ),
			'onway' => __( 'Tu pedido #{order_number} va en camino. {carrier} {tracking_url}', 'skincare' ),
			'delivered' => __( 'Pedido #{order_number} entregado. ¡Gracias por tu compra!', 'skincare' ),
		];

		$messages = [
			__( 'Confirmar pedido', 'skincare' ) => strtr(
				isset( $press_settings['whatsapp_template_confirm'] ) && $press_settings['whatsapp_template_confirm'] ? $press_settings['whatsapp_template_confirm'] : $defaults['confirm'],
				$placeholders
			),
			__( 'Registrar delivery', 'skincare' ) => strtr(
				isset( $press_settings['whatsapp_template_delivery'] ) && $press_settings['whatsapp_template_delivery'] ? $press_settings['whatsapp_template_delivery'] : $defaults['delivery'],
				$placeholders
			),
			__( 'Pedido en camino', 'skincare' ) => strtr(
				isset( $press_settings['whatsapp_template_onway'] ) && $press_settings['whatsapp_template_onway'] ? $press_settings['whatsapp_template_onway'] : $defaults['onway'],
				$placeholders
			),
			__( 'Pedido entregado', 'skincare' ) => strtr(
				isset( $press_settings['whatsapp_template_delivered'] ) && $press_settings['whatsapp_template_delivered'] ? $press_settings['whatsapp_template_delivered'] : $defaults['delivered'],
				$placeholders
			),
		];

		$links = [];
		foreach ( $messages as $label => $message ) {
			$links[ $label ] = 'https://wa.me/' . rawurlencode( $phone ) . '?text=' . rawurlencode( $message );
		}

		return $links;
	}
}
