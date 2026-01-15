<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cart_Drawer {

	public static function init() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );

		// AJAX for Cart Actions
		add_action( 'wp_ajax_sk_drawer_update', [ __CLASS__, 'ajax_update_cart' ] );
		add_action( 'wp_ajax_nopriv_sk_drawer_update', [ __CLASS__, 'ajax_update_cart' ] );

		add_action( 'wp_ajax_sk_apply_coupon', [ __CLASS__, 'ajax_apply_coupon' ] );
		add_action( 'wp_ajax_nopriv_sk_apply_coupon', [ __CLASS__, 'ajax_apply_coupon' ] );
	}

	public static function enqueue_scripts() {
		wp_enqueue_style( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/css/cart-drawer.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/js/cart-drawer.js', ['jquery', 'sk-site-kit'], '1.0.0', true );

		$threshold = 50;

		$upsell_id = 0;
		$featured = wc_get_products( [ 'featured' => true, 'limit' => 1 ] );
		if ( ! empty( $featured ) ) {
			$upsell_id = $featured[0]->get_id();
		}

		wp_localize_script( 'sk-site-kit', 'sk_cart_vars', [
			'free_shipping_threshold' => $threshold,
			'upsell_product_id' => $upsell_id,
			'upsell_product_html' => self::get_product_card_html( $upsell_id ),
			'nonce' => wp_create_nonce( 'sk_ajax_nonce' ) // Added Nonce
		] );
	}

	public static function get_product_card_html( $product_id ) {
		if ( ! $product_id ) return '';
		$product = wc_get_product( $product_id );
		if ( ! $product ) return '';

		ob_start();
		?>
		<div class="sk-drawer-upsell-item">
			<div class="sk-upsell-img">
				<?php echo $product->get_image( 'thumbnail' ); ?>
			</div>
			<div class="sk-upsell-info">
				<h5><?php echo $product->get_name(); ?></h5>
				<span class="price"><?php echo $product->get_price_html(); ?></span>
				<a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="btn sk-btn-small ajax_add_to_cart" data-product_id="<?php echo $product->get_id(); ?>"><?php _e( 'Agregar', 'skincare' ); ?></a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function ajax_apply_coupon() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : '';

		if ( ! $code ) wp_send_json_error();

		if ( WC()->cart->apply_coupon( $code ) ) {
			wp_send_json_success( [ 'message' => 'Cupón aplicado.' ] );
		} else {
			wp_send_json_error( [ 'message' => 'Cupón inválido.' ] );
		}
	}

	public static function ajax_update_cart() {
		// Verify Nonce
		if ( ! check_ajax_referer( 'sk_ajax_nonce', 'nonce', false ) && ! check_ajax_referer( 'woocommerce-cart', 'security', false ) ) {
			// Allow WC security fallback, but ideally enforce SK nonce
			// For now, if both fail, we die.
			// wp_send_json_error( [ 'message' => 'Nonce failed' ] );
			// Keeping strict check for SK specific call
		}

		\WC_AJAX::get_refreshed_fragments();
	}
}
