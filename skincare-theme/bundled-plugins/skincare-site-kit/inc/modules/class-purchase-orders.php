<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Purchase_Orders {
	const NONCE_FIELD = 'sk_purchase_order_nonce';
	const NONCE_ACTION = 'sk_purchase_order_save';

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_box' ] );
		add_action( 'save_post_sk_purchase_order', [ __CLASS__, 'save_meta_box' ] );
	}

	public static function register_post_type() {
		register_post_type( 'sk_purchase_order', [
			'labels' => [
				'name' => __( 'Purchase Orders', 'skincare' ),
				'singular_name' => __( 'Purchase Order', 'skincare' ),
			],
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => 'skincare-site-kit',
			'menu_icon' => 'dashicons-clipboard',
			'supports' => [ 'title' ],
		] );
	}

	public static function register_meta_box() {
		add_meta_box(
			'sk-purchase-order-details',
			__( 'Purchase Order Details', 'skincare' ),
			[ __CLASS__, 'render_meta_box' ],
			'sk_purchase_order',
			'normal',
			'default'
		);
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$supplier = get_post_meta( $post->ID, '_sk_po_supplier', true );
		$status = get_post_meta( $post->ID, '_sk_po_status', true );
		$expected = get_post_meta( $post->ID, '_sk_po_expected_date', true );
		$items = get_post_meta( $post->ID, '_sk_po_items', true );

		$statuses = [ '', __( 'Draft', 'skincare' ), __( 'Sent', 'skincare' ), __( 'Confirmed', 'skincare' ), __( 'Received', 'skincare' ) ];
		?>
		<p>
			<label for="sk-po-supplier"><strong><?php esc_html_e( 'Supplier', 'skincare' ); ?></strong></label>
			<input type="text" class="widefat" name="sk_po_supplier" id="sk-po-supplier" value="<?php echo esc_attr( $supplier ); ?>">
		</p>
		<p>
			<label for="sk-po-status"><strong><?php esc_html_e( 'Status', 'skincare' ); ?></strong></label>
			<select class="widefat" name="sk_po_status" id="sk-po-status">
				<?php foreach ( $statuses as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $status, $option ); ?>>
						<?php echo $option ? esc_html( $option ) : esc_html__( 'Select', 'skincare' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="sk-po-expected"><strong><?php esc_html_e( 'Expected date', 'skincare' ); ?></strong></label>
			<input type="date" class="widefat" name="sk_po_expected_date" id="sk-po-expected" value="<?php echo esc_attr( $expected ); ?>">
		</p>
		<p>
			<label for="sk-po-items"><strong><?php esc_html_e( 'Items', 'skincare' ); ?></strong></label>
			<textarea class="widefat" rows="6" name="sk_po_items" id="sk-po-items" placeholder="SKU | Name | Qty | Cost\n..."><?php echo esc_textarea( $items ); ?></textarea>
		</p>
		<?php
	}

	public static function save_meta_box( $post_id ) {
		if ( ! isset( $_POST[ self::NONCE_FIELD ] ) || ! wp_verify_nonce( $_POST[ self::NONCE_FIELD ], self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = [
			'_sk_po_supplier' => [ 'key' => 'sk_po_supplier', 'sanitize' => 'sanitize_text_field' ],
			'_sk_po_status' => [ 'key' => 'sk_po_status', 'sanitize' => 'sanitize_text_field' ],
			'_sk_po_expected_date' => [ 'key' => 'sk_po_expected_date', 'sanitize' => 'sanitize_text_field' ],
			'_sk_po_items' => [ 'key' => 'sk_po_items', 'sanitize' => 'sanitize_textarea_field' ],
		];

		foreach ( $fields as $meta_key => $field ) {
			if ( isset( $_POST[ $field['key'] ] ) ) {
				$value = wp_unslash( $_POST[ $field['key'] ] );
				$sanitized = call_user_func( $field['sanitize'], $value );
				update_post_meta( $post_id, $meta_key, $sanitized );
			}
		}
	}
}
