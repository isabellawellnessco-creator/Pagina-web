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

		add_action( 'wp_ajax_sk_update_cart_item', [ __CLASS__, 'ajax_update_cart_item' ] );
		add_action( 'wp_ajax_nopriv_sk_update_cart_item', [ __CLASS__, 'ajax_update_cart_item' ] );

		// Policy Checkbox Logic
		add_action( 'wp_ajax_sk_set_policy_status', [ __CLASS__, 'ajax_set_policy_status' ] );
		add_action( 'wp_ajax_nopriv_sk_set_policy_status', [ __CLASS__, 'ajax_set_policy_status' ] );
		add_action( 'woocommerce_checkout_process', [ __CLASS__, 'check_policy_on_checkout' ] );
		add_action( 'woocommerce_review_order_before_submit', [ __CLASS__, 'add_policy_checkbox_to_checkout' ] );

		// Custom Item Render to include +/- buttons
		add_filter( 'woocommerce_widget_cart_item_quantity', [ __CLASS__, 'custom_item_quantity_input' ], 10, 3 );
	}

	public static function enqueue_scripts() {
		wp_enqueue_style( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/css/cart-drawer.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/js/cart-drawer.js', ['jquery', 'sk-site-kit'], '1.0.0', true );

		$threshold = 200;
		if ( class_exists( '\Skincare\SiteKit\Modules\Localization' ) ) {
			$threshold = Localization::get_free_shipping_threshold();
		}

		wp_localize_script( 'sk-site-kit', 'sk_cart_vars', [
			'free_shipping_threshold' => $threshold,
			'recommendations_html'    => self::get_recommendations_html(),
			'nonce'                   => wp_create_nonce( 'sk_ajax_nonce' ),
			'checkout_url'            => wc_get_checkout_url(),
			'policy_url'              => home_url( '/politicas/' ),
			'currency_symbol'         => get_woocommerce_currency_symbol(), // Filtered by Localization
			'currency_code'           => get_woocommerce_currency(), // Filtered by Localization
		] );
	}

	public static function get_recommendations_html() {
		// Logic: 1. Cross Sells of last added item?
		// Since we don't know the "last added" easily on page load,
		// we check the cart contents.

		$cross_sells = [];
		if ( WC()->cart ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( ! empty( $cart_item['product_id'] ) ) {
					$product = wc_get_product( $cart_item['product_id'] );
					if ( $product ) {
						$ids = $product->get_cross_sell_ids();
						if ( ! empty( $ids ) ) {
							$cross_sells = array_merge( $cross_sells, $ids );
						}
					}
				}
			}
		}

		$cross_sells = array_unique( $cross_sells );

		// Fallback: Category of first item
		if ( empty( $cross_sells ) && WC()->cart && ! WC()->cart->is_empty() ) {
			$items = WC()->cart->get_cart();
			$first = reset( $items );
			$pid = $first['product_id'];
			$cats = wp_get_post_terms( $pid, 'product_cat', [ 'fields' => 'ids' ] );
			if ( ! empty( $cats ) ) {
				$query = new \WP_Query( [
					'post_type' => 'product',
					'posts_per_page' => 4,
					'tax_query' => [
						[
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $cats,
						]
					],
					'post__not_in' => array_column( $items, 'product_id' ),
				] );
				if ( $query->have_posts() ) {
					$cross_sells = wp_list_pluck( $query->posts, 'ID' );
				}
			}
		}

		// Fallback: Featured
		if ( empty( $cross_sells ) ) {
			$featured = wc_get_products( [ 'featured' => true, 'limit' => 4 ] );
			foreach ( $featured as $f ) {
				$cross_sells[] = $f->get_id();
			}
		}

		// Filter for future manual rules
		$cross_sells = apply_filters( 'sk_drawer_upsells', $cross_sells, WC()->cart );

		if ( empty( $cross_sells ) ) return '';

		$cross_sells = array_slice( $cross_sells, 0, 4 );

		ob_start();
		foreach ( $cross_sells as $id ) {
			echo self::get_product_card_html( $id );
		}
		return ob_get_clean();
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
				<h5><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo $product->get_name(); ?></a></h5>
				<span class="price"><?php echo $product->get_price_html(); ?></span>
				<?php if ( $product->is_type( 'variable' ) ) : ?>
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="btn sk-btn-small"><?php _e( 'Ver Opciones', 'skincare' ); ?></a>
				<?php else : ?>
					<a href="?add-to-cart=<?php echo $product->get_id(); ?>" class="btn sk-btn-small ajax_add_to_cart" data-product_id="<?php echo $product->get_id(); ?>"><?php _e( 'Agregar', 'skincare' ); ?></a>
				<?php endif; ?>
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
			// Strict check
		}
		\WC_AJAX::get_refreshed_fragments();
	}

	public static function ajax_update_cart_item() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
		$qty = isset( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 0;

		if ( ! $key ) wp_send_json_error( [ 'message' => 'Invalid Item' ] );

		$cart = WC()->cart->get_cart();
		if ( ! isset( $cart[ $key ] ) ) {
			wp_send_json_error( [ 'message' => 'Item not found in cart' ] );
		}

		$cart_item = $cart[ $key ];
		$product = $cart_item['data'];

		// Stock Validation
		if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
			$stock = $product->get_stock_quantity();
			if ( $qty > $stock ) {
				$qty = $stock; // Cap at max stock
				// Add notice for UI
				wc_add_notice( sprintf( __( 'Lo sentimos, solo tenemos %s unidades de %s en stock.', 'skincare' ), $stock, $product->get_name() ), 'error' );
			}
		}

		if ( $qty <= 0 ) {
			WC()->cart->remove_cart_item( $key );
		} else {
			WC()->cart->set_quantity( $key, $qty );
		}

		WC()->cart->calculate_totals();

		// Return standard fragments for UI update
		\WC_AJAX::get_refreshed_fragments();
	}

	public static function ajax_set_policy_status() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$status = isset( $_POST['status'] ) && $_POST['status'] === 'true';

		if ( WC()->session ) {
			WC()->session->set( 'sk_policy_accepted', $status );
			wp_send_json_success( [ 'status' => $status ] );
		}
		wp_send_json_error();
	}

	public static function check_policy_on_checkout() {
		$accepted = WC()->session ? WC()->session->get( 'sk_policy_accepted' ) : false;

		// Also check POST if JS failed (fallback)
		if ( isset( $_POST['sk_policy_accepted_field'] ) ) {
			$accepted = true;
		}

		if ( ! $accepted ) {
			wc_add_notice( __( 'Debes aceptar las políticas de privacidad y términos para continuar.', 'skincare' ), 'error' );
		}
	}

	public static function add_policy_checkbox_to_checkout() {
		$checked = WC()->session && WC()->session->get( 'sk_policy_accepted' ) ? 'checked' : '';
		$url = home_url( '/politicas/' );
		?>
		<div class="sk-policy-checkout-wrapper">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="sk_policy_accepted_field" id="sk_policy_accepted_field" <?php echo $checked; ?> />
				<span class="woocommerce-terms-and-conditions-checkbox-text">Acepto las <a href="<?php echo esc_url( $url ); ?>" target="_blank">políticas de compra y devoluciones</a>.</span>
			</label>
			<script>
			jQuery(document).on('change', '#sk_policy_accepted_field', function() {
				var status = jQuery(this).is(':checked');
				jQuery.post(sk_vars.ajax_url, {
					action: 'sk_set_policy_status',
					nonce: sk_cart_vars.nonce,
					status: status
				});
			});
			</script>
		</div>
		<?php
	}

	// Override for widget mini cart quantity
	public static function custom_item_quantity_input( $html, $cart_item, $cart_item_key ) {
		$product_quantity = sprintf( '%2$s <input type="number" class="sk-qty-input" step="1" min="0" max="%3$s" name="cart[%1$s][qty]" value="%2$s" title="Qty" size="4" inputmode="numeric" data-key="%1$s" />',
			$cart_item_key,
			$cart_item['quantity'],
			$cart_item['data']->get_max_purchase_quantity() > 0 ? $cart_item['data']->get_max_purchase_quantity() : ''
		);
		// Wrap with controls
		return '<div class="sk-qty-control">
			<button type="button" class="sk-qty-btn minus">-</button>
			' . $product_quantity . '
			<button type="button" class="sk-qty-btn plus">+</button>
		</div>';
	}
}
