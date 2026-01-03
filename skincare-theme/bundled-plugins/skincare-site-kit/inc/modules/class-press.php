<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Press {
	const META_NONCE_FIELD = 'sk_press_meta_nonce';
	const META_NONCE_ACTION = 'sk_press_meta_save';

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'register_meta_boxes' ] );
		add_action( 'save_post_sk_press', [ __CLASS__, 'save_meta_boxes' ] );
	}

	public static function register_post_type() {
		register_post_type( 'sk_press', [
			'labels' => [
				'name' => __( 'Press', 'skincare' ),
				'singular_name' => __( 'Press item', 'skincare' ),
			],
			'public' => false,
			'show_ui' => true,
			'menu_icon' => 'dashicons-format-aside',
			'supports' => [ 'title', 'editor', 'thumbnail' ],
			'show_in_menu' => 'skincare-site-kit',
		] );
	}

	public static function register_meta_boxes() {
		add_meta_box(
			'sk-press-details',
			__( 'Press Details', 'skincare' ),
			[ __CLASS__, 'render_meta_box' ],
			'sk_press',
			'normal',
			'default'
		);
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( self::META_NONCE_ACTION, self::META_NONCE_FIELD );

		$logo_id = get_post_meta( $post->ID, '_sk_press_logo_id', true );
		$cta_text = get_post_meta( $post->ID, '_sk_press_cta_text', true );
		$cta_url = get_post_meta( $post->ID, '_sk_press_cta_url', true );
		$product_vendor = get_post_meta( $post->ID, '_sk_press_product_vendor', true );
		$product_name = get_post_meta( $post->ID, '_sk_press_product_name', true );
		$product_price = get_post_meta( $post->ID, '_sk_press_product_price', true );
		$product_url = get_post_meta( $post->ID, '_sk_press_product_url', true );
		$product_image_id = get_post_meta( $post->ID, '_sk_press_product_image_id', true );
		$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'thumbnail' ) : '';
		$product_image_url = $product_image_id ? wp_get_attachment_image_url( $product_image_id, 'thumbnail' ) : '';
		?>
		<div style="display:grid; gap:16px; max-width: 700px;">
			<div>
				<p><strong><?php esc_html_e( 'Logo de la revista', 'skincare' ); ?></strong></p>
				<div class="sk-media-field">
					<input type="hidden" name="sk_press_logo_id" value="<?php echo esc_attr( $logo_id ); ?>">
					<div class="sk-media-preview">
						<?php if ( $logo_url ) : ?>
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="" style="max-width: 120px; height: auto;">
						<?php endif; ?>
					</div>
					<button type="button" class="button sk-media-upload"><?php esc_html_e( 'Seleccionar logo', 'skincare' ); ?></button>
					<button type="button" class="button sk-media-remove"><?php esc_html_e( 'Quitar', 'skincare' ); ?></button>
				</div>
			</div>
			<div>
				<label for="sk-press-cta-text"><strong><?php esc_html_e( 'Texto del CTA', 'skincare' ); ?></strong></label>
				<input type="text" class="widefat" id="sk-press-cta-text" name="sk_press_cta_text" value="<?php echo esc_attr( $cta_text ); ?>">
			</div>
			<div>
				<label for="sk-press-cta-url"><strong><?php esc_html_e( 'URL del CTA', 'skincare' ); ?></strong></label>
				<input type="url" class="widefat" id="sk-press-cta-url" name="sk_press_cta_url" value="<?php echo esc_attr( $cta_url ); ?>" placeholder="https://">
			</div>
			<hr>
			<h4><?php esc_html_e( 'Producto destacado (opcional)', 'skincare' ); ?></h4>
			<div>
				<label for="sk-press-product-vendor"><strong><?php esc_html_e( 'Marca', 'skincare' ); ?></strong></label>
				<input type="text" class="widefat" id="sk-press-product-vendor" name="sk_press_product_vendor" value="<?php echo esc_attr( $product_vendor ); ?>">
			</div>
			<div>
				<label for="sk-press-product-name"><strong><?php esc_html_e( 'Nombre del producto', 'skincare' ); ?></strong></label>
				<input type="text" class="widefat" id="sk-press-product-name" name="sk_press_product_name" value="<?php echo esc_attr( $product_name ); ?>">
			</div>
			<div>
				<label for="sk-press-product-price"><strong><?php esc_html_e( 'Precio', 'skincare' ); ?></strong></label>
				<input type="text" class="widefat" id="sk-press-product-price" name="sk_press_product_price" value="<?php echo esc_attr( $product_price ); ?>" placeholder="£10.00">
			</div>
			<div>
				<label for="sk-press-product-url"><strong><?php esc_html_e( 'URL del producto', 'skincare' ); ?></strong></label>
				<input type="url" class="widefat" id="sk-press-product-url" name="sk_press_product_url" value="<?php echo esc_attr( $product_url ); ?>" placeholder="https://">
			</div>
			<div>
				<p><strong><?php esc_html_e( 'Imagen del producto', 'skincare' ); ?></strong></p>
				<div class="sk-media-field">
					<input type="hidden" name="sk_press_product_image_id" value="<?php echo esc_attr( $product_image_id ); ?>">
					<div class="sk-media-preview">
						<?php if ( $product_image_url ) : ?>
							<img src="<?php echo esc_url( $product_image_url ); ?>" alt="" style="max-width: 120px; height: auto;">
						<?php endif; ?>
					</div>
					<button type="button" class="button sk-media-upload"><?php esc_html_e( 'Seleccionar imagen', 'skincare' ); ?></button>
					<button type="button" class="button sk-media-remove"><?php esc_html_e( 'Quitar', 'skincare' ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}

	public static function save_meta_boxes( $post_id ) {
		if ( ! isset( $_POST[ self::META_NONCE_FIELD ] ) || ! wp_verify_nonce( $_POST[ self::META_NONCE_FIELD ], self::META_NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = [
			'_sk_press_logo_id' => [ 'key' => 'sk_press_logo_id', 'sanitize' => 'absint' ],
			'_sk_press_cta_text' => [ 'key' => 'sk_press_cta_text', 'sanitize' => 'sanitize_text_field' ],
			'_sk_press_cta_url' => [ 'key' => 'sk_press_cta_url', 'sanitize' => 'esc_url_raw' ],
			'_sk_press_product_vendor' => [ 'key' => 'sk_press_product_vendor', 'sanitize' => 'sanitize_text_field' ],
			'_sk_press_product_name' => [ 'key' => 'sk_press_product_name', 'sanitize' => 'sanitize_text_field' ],
			'_sk_press_product_price' => [ 'key' => 'sk_press_product_price', 'sanitize' => 'sanitize_text_field' ],
			'_sk_press_product_url' => [ 'key' => 'sk_press_product_url', 'sanitize' => 'esc_url_raw' ],
			'_sk_press_product_image_id' => [ 'key' => 'sk_press_product_image_id', 'sanitize' => 'absint' ],
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
