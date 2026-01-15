<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Order_Management {
	const NONCE_FIELD = 'sk_order_management_nonce';
	const NONCE_ACTION = 'sk_order_management_save';

	public static function init() {
		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

		// Meta Box
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_box' ] );
		add_action( 'save_post_shop_order', [ __CLASS__, 'save_meta_box' ] );

		// Columns
		add_filter( 'manage_edit-shop_order_columns', [ __CLASS__, 'register_order_columns' ], 20 );
		add_action( 'manage_shop_order_posts_custom_column', [ __CLASS__, 'render_order_columns' ], 20, 2 );

		// Filters
		add_action( 'restrict_manage_posts', [ __CLASS__, 'add_admin_filters' ] );
		add_filter( 'parse_query', [ __CLASS__, 'parse_admin_filters' ] );
	}

	public static function enqueue_scripts( $hook ) {
		// Only enqueue on order edit page
		$screen = get_current_screen();
		if ( ! $screen || 'shop_order' !== $screen->id ) {
			return;
		}

		wp_enqueue_script(
			'sk-admin-ops',
			plugin_dir_url( dirname( __DIR__ ) ) . 'assets/js/admin-ops.js',
			[ 'jquery' ],
			filemtime( plugin_dir_path( dirname( __DIR__ ) ) . 'assets/js/admin-ops.js' ),
			true
		);

		wp_localize_script( 'sk-admin-ops', 'sk_ops_vars', [
			'nonce' => wp_create_nonce( 'sk_ops_action' )
		] );

		wp_add_inline_style( 'woocommerce_admin_styles', '
			.sk-ops-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; background: #f0f0f1; padding: 10px; border-radius: 4px; }
			.sk-ops-badge { font-weight: bold; padding: 4px 8px; border-radius: 4px; background: #ddd; }
			.sk-ops-badge.status-sk-pending-confirm { background: #ffba00; color: #fff; }
			.sk-ops-badge.status-sk-confirmed { background: #7ad03a; color: #fff; }
			.sk-ops-badge.status-sk-delivered { background: #0073aa; color: #fff; }
			.sk-ops-section { margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px; }
			.sk-hidden { display: none; }
			.sk-required-mark { color: red; }
		' );
	}

	public static function register_meta_box() {
		add_meta_box(
			'sk-order-ops',
			__( 'Operación de Pedido', 'skincare' ),
			[ __CLASS__, 'render_meta_box' ],
			'shop_order',
			'normal',
			'high'
		);
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
		$order = wc_get_order( $post->ID );

		// Core Data
		$status = $order->get_meta( Operations_Core::META_STATUS, true ) ?: Operations_Core::STATUS_PENDING_CONFIRM;
		$zone = $order->get_meta( Operations_Core::META_ZONE, true );
		$payment_type = $order->get_meta( Operations_Core::META_PAYMENT_TYPE, true );
		$agency = $order->get_meta( Operations_Core::META_AGENCY, true );

		// Fields
		$ticket = $order->get_meta( Operations_Core::META_TICKET, true );
		$pickup_code = $order->get_meta( Operations_Core::META_PICKUP_CODE, true );
		$advance = $order->get_meta( Operations_Core::META_ADVANCE, true );
		$urpi_id = $order->get_meta( Operations_Core::META_URPI_ID, true );
		$real_cost = $order->get_meta( Operations_Core::META_REAL_COST, true );
		$internal_notes = $order->get_meta( '_sk_internal_notes', true );

		$statuses = Operations_Core::get_operational_states();
		?>
		<div class="sk-ops-panel">
			<!-- Header -->
			<div class="sk-ops-header">
				<div>
					<strong><?php _e( 'Estado Operativo:', 'skincare' ); ?></strong>
					<span id="sk-ops-current-status" class="sk-ops-badge status-<?php echo esc_attr( $status ); ?>">
						<?php echo esc_html( $statuses[ $status ] ?? $status ); ?>
					</span>
				</div>
				<div class="sk-ops-actions">
					<!-- Dynamic Buttons based on Logic -->
					<button type="button" class="button button-primary sk-ops-action-btn" data-order-id="<?php echo $order->get_id(); ?>" data-status="<?php echo Operations_Core::STATUS_CONFIRMED; ?>">
						<?php _e( 'Confirmar', 'skincare' ); ?>
					</button>
					<button type="button" class="button sk-ops-action-btn" data-order-id="<?php echo $order->get_id(); ?>" data-status="<?php echo Operations_Core::STATUS_PICKING; ?>">
						<?php _e( 'A Picking', 'skincare' ); ?>
					</button>
				</div>
			</div>

			<div class="sk-wa-block">
				<?php if ( class_exists( '\Skincare\SiteKit\Admin\Whatsapp_Context' ) ) : ?>
					<?php \Skincare\SiteKit\Admin\Whatsapp_Context::render_action_button( $order ); ?>
				<?php endif; ?>
			</div>

			<!-- Classification -->
			<div class="sk-ops-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
				<p class="form-field">
					<label for="sk_zone"><?php _e( 'Zona', 'skincare' ); ?></label>
					<select name="sk_zone" id="sk_zone" class="widefat">
						<option value=""><?php _e( 'Seleccionar...', 'skincare' ); ?></option>
						<?php foreach ( Operations_Core::get_zones() as $k => $v ) : ?>
							<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $zone, $k ); ?>><?php echo esc_html( $v ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p class="form-field">
					<label for="sk_payment_type_ops"><?php _e( 'Tipo de Pago', 'skincare' ); ?></label>
					<select name="sk_payment_type_ops" id="sk_payment_type_ops" class="widefat">
						<option value="web" <?php selected( $payment_type, 'web' ); ?>><?php _e( 'Pago Web', 'skincare' ); ?></option>
						<option value="contraentrega" <?php selected( $payment_type, 'contraentrega' ); ?>><?php _e( 'Contraentrega', 'skincare' ); ?></option>
					</select>
				</p>
				<p class="form-field">
					<label for="sk_agency"><?php _e( 'Agencia', 'skincare' ); ?></label>
					<select name="sk_agency" id="sk_agency" class="widefat">
						<option value=""><?php _e( 'Seleccionar...', 'skincare' ); ?></option>
						<?php foreach ( Operations_Core::get_agencies() as $k => $v ) : ?>
							<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $agency, $k ); ?>><?php echo esc_html( $v ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			</div>

			<!-- Conditional Groups -->
			<div class="sk-group-contraentrega sk-ops-section sk-hidden">
				<h4><?php _e( 'Gestión Contraentrega', 'skincare' ); ?></h4>
				<p><?php _e( 'Validar datos antes de confirmar.', 'skincare' ); ?></p>
				<textarea class="widefat" name="sk_internal_notes" rows="2" placeholder="<?php _e( 'Notas de llamada...', 'skincare' ); ?>"><?php echo esc_textarea( $internal_notes ); ?></textarea>
			</div>

			<div class="sk-group-provincia sk-ops-section sk-hidden">
				<h4><?php _e( 'Datos Shalom (Provincia)', 'skincare' ); ?></h4>
				<p class="form-field">
					<label><?php _e( 'Adelanto (S/)', 'skincare' ); ?></label>
					<input type="text" name="sk_advance_amount" value="<?php echo esc_attr( $advance ); ?>" placeholder="0.00">
				</p>
				<p class="form-field">
					<label><?php _e( 'Ticket / Guía', 'skincare' ); ?> <span class="sk-required-mark">*</span></label>
					<input type="text" name="sk_shalom_ticket" value="<?php echo esc_attr( $ticket ); ?>">
				</p>
				<p class="form-field">
					<label><?php _e( 'Clave de Recojo', 'skincare' ); ?> <span class="sk-required-mark">*</span></label>
					<input type="text" name="sk_pickup_code" value="<?php echo esc_attr( $pickup_code ); ?>">
				</p>
				<div style="margin-top: 10px;">
					<button type="button" class="button sk-ops-action-btn" data-order-id="<?php echo $order->get_id(); ?>" data-status="<?php echo Operations_Core::STATUS_DISPATCHED_PROV; ?>">
						<?php _e( 'Marcar Despachado (Provincia)', 'skincare' ); ?>
					</button>
					<button type="button" class="button sk-ops-action-btn" data-order-id="<?php echo $order->get_id(); ?>" data-status="<?php echo Operations_Core::STATUS_READY_PICKUP; ?>">
						<?php _e( 'Marcar Listo Recojo', 'skincare' ); ?>
					</button>
				</div>
			</div>

			<div class="sk-group-lima sk-ops-section sk-hidden">
				<h4><?php _e( 'Datos Urpi (Lima)', 'skincare' ); ?></h4>
				<p class="form-field">
					<label><?php _e( 'ID Registro Urpi', 'skincare' ); ?></label>
					<input type="text" name="sk_urpi_id" value="<?php echo esc_attr( $urpi_id ); ?>">
				</p>
				<p class="form-field">
					<label><?php _e( 'Costo Real Envío', 'skincare' ); ?></label>
					<input type="text" name="sk_real_shipping_cost" value="<?php echo esc_attr( $real_cost ); ?>" placeholder="0.00">
				</p>
				<div style="margin-top: 10px;">
					<button type="button" class="button sk-ops-action-btn" data-order-id="<?php echo $order->get_id(); ?>" data-status="<?php echo Operations_Core::STATUS_DISPATCHED_LIMA; ?>">
						<?php _e( 'Marcar Despachado (Lima)', 'skincare' ); ?>
					</button>
				</div>
			</div>

			<div class="sk-ops-section">
				<p class="description"><?php _e( 'Guarde el pedido para actualizar los campos antes de cambiar de estado.', 'skincare' ); ?></p>
			</div>

			<div class="sk-fulfillment-actions" style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 15px;">
				<p><strong><?php esc_html_e( 'Acciones rápidas', 'skincare' ); ?></strong></p>
				<p>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?action=sk_print_label&order_id=' . $post->ID ) ); ?>" target="_blank"><?php esc_html_e( 'Rótulo Interno (PDF)', 'skincare' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}

	public static function save_meta_box( $post_id ) {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( $_POST[ self::NONCE_FIELD ], self::NONCE_ACTION ) ) {
			return;
		}

		// Save fields
		$fields = [
			'sk_zone' => Operations_Core::META_ZONE,
			'sk_payment_type_ops' => Operations_Core::META_PAYMENT_TYPE,
			'sk_agency' => Operations_Core::META_AGENCY,
			'sk_shalom_ticket' => Operations_Core::META_TICKET,
			'sk_pickup_code' => Operations_Core::META_PICKUP_CODE,
			'sk_advance_amount' => Operations_Core::META_ADVANCE,
			'sk_urpi_id' => Operations_Core::META_URPI_ID,
			'sk_real_shipping_cost' => Operations_Core::META_REAL_COST,
			'sk_internal_notes' => '_sk_internal_notes'
		];

		foreach ( $fields as $post_key => $meta_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[ $post_key ] ) );
			}
		}
	}

	public static function register_order_columns( $columns ) {
		// Insert after Order Status
		$new_columns = [];
		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;
			if ( 'order_status' === $key ) {
				$new_columns['sk_ops_status'] = __( 'Estado Ops', 'skincare' );
				$new_columns['sk_ops_info']   = __( 'Info Ops', 'skincare' );
			}
		}
		return $new_columns;
	}

	public static function render_order_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'sk_ops_status':
				$status = get_post_meta( $post_id, Operations_Core::META_STATUS, true );
				$all = Operations_Core::get_operational_states();
				if ( $status ) {
					printf( '<span class="sk-ops-badge status-%s">%s</span>', esc_attr( $status ), esc_html( $all[ $status ] ?? $status ) );
				} else {
					echo '—';
				}
				break;
			case 'sk_ops_info':
				$zone = get_post_meta( $post_id, Operations_Core::META_ZONE, true );
				$agency = get_post_meta( $post_id, Operations_Core::META_AGENCY, true );
				if ( $zone ) {
					echo '<strong>' . esc_html( ucfirst( $zone ) ) . '</strong>';
				}
				if ( $agency ) {
					echo '<br><small>' . esc_html( ucfirst( $agency ) ) . '</small>';
				}

				// Alerts
				$ticket = get_post_meta( $post_id, Operations_Core::META_TICKET, true );
				if ( 'provincia' === $zone && ! $ticket ) {
					echo '<br><span style="color:red;">⚠ No Ticket</span>';
				}
				break;
		}
	}

	public static function add_admin_filters( $post_type ) {
		if ( 'shop_order' === $post_type ) {
			// Filter by Ops Status
			$current_status = isset( $_GET['sk_ops_status'] ) ? $_GET['sk_ops_status'] : '';
			$states = Operations_Core::get_operational_states();
			echo '<select name="sk_ops_status">';
			echo '<option value="">' . __( 'Todos los Estados Operativos', 'skincare' ) . '</option>';
			foreach ( $states as $key => $label ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $current_status, $key, false ), esc_html( $label ) );
			}
			echo '</select>';

			// Filter by Zone
			$current_zone = isset( $_GET['sk_zone'] ) ? $_GET['sk_zone'] : '';
			$zones = Operations_Core::get_zones();
			echo '<select name="sk_zone">';
			echo '<option value="">' . __( 'Todas las Zonas', 'skincare' ) . '</option>';
			foreach ( $zones as $key => $label ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $current_zone, $key, false ), esc_html( $label ) );
			}
			echo '</select>';
		}
	}

	public static function parse_admin_filters( $query ) {
		global $pagenow;
		if ( 'edit.php' === $pagenow && 'shop_order' === $query->query['post_type'] ) {
			if ( ! empty( $_GET['sk_ops_status'] ) ) {
				$query->query_vars['meta_query'][] = [
					'key'   => Operations_Core::META_STATUS,
					'value' => sanitize_text_field( $_GET['sk_ops_status'] ),
				];
			}
			if ( ! empty( $_GET['sk_zone'] ) ) {
				$query->query_vars['meta_query'][] = [
					'key'   => Operations_Core::META_ZONE,
					'value' => sanitize_text_field( $_GET['sk_zone'] ),
				];
			}
		}
		return $query;
	}
}
