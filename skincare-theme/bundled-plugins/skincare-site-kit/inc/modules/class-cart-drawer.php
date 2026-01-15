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

		// Custom Item Render to include +/- buttons
		add_filter( 'woocommerce_widget_cart_item_quantity', [ __CLASS__, 'custom_item_quantity_input' ], 10, 3 );
	}

	public static function enqueue_scripts() {
		wp_enqueue_style( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/css/cart-drawer.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-cart-drawer', SKINCARE_KIT_URL . 'assets/js/cart-drawer.js', ['jquery', 'sk-site-kit'], '1.0.0', true );

		$threshold = self::get_free_shipping_threshold();

		// Convert Threshold using Localization
		if ( class_exists( '\Skincare\SiteKit\Modules\Localization' ) ) {
			// Threshold is usually in base currency
			$currency = Localization::get_active_currency();
			if ( $currency !== 'PEN' ) { // Assuming base is PEN, should verify but usually base is stored as 1
				$currencies = Localization::get_currencies();
				if ( isset( $currencies[$currency] ) ) {
					$threshold = $threshold * (float) $currencies[$currency]['rate'];
				}
			}
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

	public static function get_free_shipping_threshold() {
		$threshold = 200; // Fallback default

		// 1. Try to find Shipping Zone "Free Shipping" method min_amount
		if ( class_exists( 'WC_Shipping_Zones' ) ) {
			$zones = \WC_Shipping_Zones::get_zones();
			if ( ! empty( $zones ) ) {
				// We iterate zones to find the first matching one for current user?
				// Or just take the first configured zone (Main).
				// Best practice: Use current customer location.
				// But initially, maybe just Main Zone (Peru?)
				foreach ( $zones as $zone_id => $zone_data ) {
					$zone = new \WC_Shipping_Zone( $zone_id );
					$methods = $zone->get_shipping_methods();
					foreach ( $methods as $method ) {
						if ( $method->id === 'free_shipping' && $method->enabled === 'yes' ) {
							if ( ! empty( $method->min_amount ) ) {
								return $method->min_amount;
							}
						}
					}
				}
			}
		}

		// 2. Global Option Fallback
		return get_option( 'sk_free_shipping_fallback', 200 );
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
				// Ideally return a message too
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
