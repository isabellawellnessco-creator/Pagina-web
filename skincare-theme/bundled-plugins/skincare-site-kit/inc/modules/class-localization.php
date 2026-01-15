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
