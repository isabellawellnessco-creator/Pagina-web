<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Localization {

	public static function init() {
		// Initialize Settings (Frontend logic depends on options)
		// Register Filters for Pricing
		add_filter( 'woocommerce_product_get_price', [ __CLASS__, 'filter_price' ], 10, 2 );
		add_filter( 'woocommerce_product_get_regular_price', [ __CLASS__, 'filter_price' ], 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', [ __CLASS__, 'filter_price' ], 10, 2 );
		add_filter( 'woocommerce_product_variation_get_price', [ __CLASS__, 'filter_price' ], 10, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', [ __CLASS__, 'filter_price' ], 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', [ __CLASS__, 'filter_price' ], 10, 2 );

		// Cart Totals
		add_filter( 'woocommerce_cart_item_price', [ __CLASS__, 'filter_cart_item_price' ], 10, 3 );
		add_filter( 'woocommerce_cart_item_subtotal', [ __CLASS__, 'filter_cart_item_subtotal' ], 10, 3 );
		add_filter( 'woocommerce_cart_total', [ __CLASS__, 'filter_cart_total' ], 10 );
		add_filter( 'woocommerce_cart_subtotal', [ __CLASS__, 'filter_cart_total' ], 10 );

		// Ensure shipping costs are converted if necessary (simplified for standard rates)
		// We avoid complex shipping filters for now as they are risky without full plugin support.
		// But basic flat rates might need it.
		// For safety in this "lightweight" mode, we focus on product prices.

		// Symbol Currency - Visual
		add_filter( 'woocommerce_currency_symbol', [ __CLASS__, 'filter_currency_symbol' ], 10, 2 );

		// CRITICAL: Filter real WooCommerce Currency so Gateways/Checkout know what's happening
		add_filter( 'woocommerce_currency', [ __CLASS__, 'filter_woocommerce_currency' ] );

		// AJAX Handler for switching
		add_action( 'wp_ajax_sk_switch_currency', [ __CLASS__, 'ajax_switch_currency' ] );
		add_action( 'wp_ajax_nopriv_sk_switch_currency', [ __CLASS__, 'ajax_switch_currency' ] );

		add_action( 'wp_ajax_sk_switch_language', [ __CLASS__, 'ajax_switch_language' ] );
		add_action( 'wp_ajax_nopriv_sk_switch_language', [ __CLASS__, 'ajax_switch_language' ] );

		// Admin Settings
		add_action( 'admin_menu', [ __CLASS__, 'add_admin_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function add_admin_menu() {
		add_submenu_page(
			'woocommerce',
			'Skin Cupid Localization',
			'SC Localization',
			'manage_options',
			'sk-localization',
			[ __CLASS__, 'render_settings_page' ]
		);
	}

	public static function register_settings() {
		register_setting( 'sk_localization_group', 'sk_currencies' );
		register_setting( 'sk_localization_group', 'sk_currency_thresholds' );
	}

	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;

		// Handle Form Submission Manually to support array structures easily if needed
		// or rely on standard options.
		// For simplicity, we use standard settings fields but might need custom logic for the array.
		// Actually, let's use a simple POST handler within this render for array saving if standard settings API is too clunky for multidimensional arrays without callbacks.
		if ( isset( $_POST['sk_loc_submit'] ) && check_admin_referer( 'sk_loc_save' ) ) {
			$rates = isset( $_POST['rates'] ) ? $_POST['rates'] : [];
			$thresholds = isset( $_POST['thresholds'] ) ? $_POST['thresholds'] : [];

			// Updates rates in sk_currencies
			$currencies = self::get_currencies(); // Get defaults/existing
			foreach ( $rates as $code => $rate ) {
				if ( isset( $currencies[$code] ) ) {
					$currencies[$code]['rate'] = floatval( $rate );
				}
			}
			update_option( 'sk_currencies', $currencies );

			// Update thresholds
			$clean_thresholds = [];
			foreach ( $thresholds as $code => $val ) {
				if ( $val !== '' ) {
					$clean_thresholds[$code] = floatval( $val );
				}
			}
			update_option( 'sk_currency_thresholds', $clean_thresholds );

			echo '<div class="notice notice-success"><p>Settings Saved.</p></div>';
		}

		$currencies = self::get_currencies();
		$thresholds = get_option( 'sk_currency_thresholds', [] );
		?>
		<div class="wrap">
			<h1>Skin Cupid Localization</h1>
			<form method="post" action="">
				<?php wp_nonce_field( 'sk_loc_save' ); ?>
				<table class="widefat fixed" style="max-width: 800px; margin-top: 20px;">
					<thead>
						<tr>
							<th>Currency</th>
							<th>Exchange Rate (vs PEN)</th>
							<th>Free Shipping Threshold (Fixed)</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $currencies as $code => $data ) :
							$rate = isset( $data['rate'] ) ? $data['rate'] : 1;
							$thresh = isset( $thresholds[$code] ) ? $thresholds[$code] : '';
						?>
						<tr>
							<td><strong><?php echo esc_html( $code ); ?></strong> (<?php echo esc_html( $data['name'] ); ?>)</td>
							<td>
								<input type="number" step="0.0001" name="rates[<?php echo esc_attr( $code ); ?>]" value="<?php echo esc_attr( $rate ); ?>" class="regular-text">
							</td>
							<td>
								<input type="number" step="0.01" name="thresholds[<?php echo esc_attr( $code ); ?>]" value="<?php echo esc_attr( $thresh ); ?>" class="regular-text" placeholder="Auto (Conversion)">
								<p class="description">Leave empty to calculate from Base (200 PEN).</p>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="sk_loc_submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</form>
		</div>
		<?php
	}

	public static function get_free_shipping_threshold( $currency = null ) {
		if ( ! $currency ) $currency = self::get_active_currency();

		$thresholds = get_option( 'sk_currency_thresholds', [] );

		// 1. Look for manual override
		if ( isset( $thresholds[ $currency ] ) && is_numeric( $thresholds[ $currency ] ) ) {
			return (float) $thresholds[ $currency ];
		}

		// 2. Fallback to conversion
		$base_threshold = get_option( 'sk_free_shipping_fallback', 200 );

		$currencies = self::get_currencies();
		if ( isset( $currencies[ $currency ]['rate'] ) ) {
			return $base_threshold * (float) $currencies[ $currency ]['rate'];
		}

		return $base_threshold;
	}

	public static function get_currencies() {
		return get_option( 'sk_currencies', [
			'PEN' => [ 'symbol' => 'S/', 'rate' => 1, 'name' => 'Soles' ],
		] );
	}

	public static function get_languages() {
		return get_option( 'sk_languages', [
			'es_ES' => [ 'label' => 'Español', 'flag' => '' ],
		] );
	}

	public static function get_active_currency() {
		if ( isset( $_COOKIE['sk_currency'] ) ) {
			$currencies = self::get_currencies();
			if ( isset( $currencies[ $_COOKIE['sk_currency'] ] ) ) {
				return $_COOKIE['sk_currency'];
			}
		}
		return 'PEN'; // Default
	}

	public static function get_active_language() {
		if ( isset( $_COOKIE['sk_language'] ) ) {
			$languages = self::get_languages();
			if ( isset( $languages[ $_COOKIE['sk_language'] ] ) ) {
				return $_COOKIE['sk_language'];
			}
		}
		return 'es_ES'; // Default
	}

	public static function filter_woocommerce_currency( $currency ) {
		// This tells WooCommerce core (and Gateways) what the actual active currency is.
		// If the gateway doesn't support it, it should throw an error or handle conversion itself (e.g., Stripe/PayPal).
		// This fixes the "Charge 25 PEN instead of 25 USD" issue.
		// However, we must be careful not to create infinite loops if get_active_currency calls something that calls this.
		// get_active_currency uses COOKIE, so it's safe.
		return self::get_active_currency();
	}

	public static function filter_price( $price, $product ) {
		$currency = self::get_active_currency();
		if ( $currency === 'PEN' ) return $price; // Base currency

		$currencies = self::get_currencies();
		if ( ! isset( $currencies[ $currency ] ) ) return $price;

		$rate = (float) $currencies[ $currency ]['rate'];
		if ( ! $price ) return $price;

		return (float) $price * $rate;
	}

	public static function filter_currency_symbol( $symbol, $currency ) {
		$active = self::get_active_currency();
		$currencies = self::get_currencies();

		if ( isset( $currencies[ $active ] ) ) {
			return $currencies[ $active ]['symbol'];
		}

		return $symbol;
	}

	// Formatting cart html
	public static function filter_cart_item_price( $price_html, $cart_item, $cart_item_key ) {
		return $price_html;
	}

	public static function filter_cart_item_subtotal( $total_html, $cart_item, $cart_item_key ) {
		return $total_html;
	}

	public static function filter_cart_total( $total_html ) {
		return $total_html;
	}

	public static function ajax_switch_currency() {
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( $_POST['currency'] ) : '';
		$currencies = self::get_currencies();

		if ( array_key_exists( $currency, $currencies ) ) {
			setcookie( 'sk_currency', $currency, time() + 86400 * 30, '/' );
			$_COOKIE['sk_currency'] = $currency; // Set for current request if needed

			// Recalculate cart
			if ( isset( WC()->cart ) ) {
				WC()->cart->calculate_totals();
			}

			wp_send_json_success();
		}
		wp_send_json_error();
	}

	public static function ajax_switch_language() {
		$lang = isset( $_POST['language'] ) ? sanitize_text_field( $_POST['language'] ) : '';
		$languages = self::get_languages();

		if ( array_key_exists( $lang, $languages ) ) {
			setcookie( 'sk_language', $lang, time() + 86400 * 30, '/' );
			wp_send_json_success();
		}
		wp_send_json_error();
	}
}
