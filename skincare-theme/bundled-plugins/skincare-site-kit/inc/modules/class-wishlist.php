<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wishlist {

	public static function init() {
		// AJAX
		add_action( 'wp_ajax_sk_add_to_wishlist', [ __CLASS__, 'ajax_add_to_wishlist' ] );
		add_action( 'wp_ajax_nopriv_sk_add_to_wishlist', [ __CLASS__, 'ajax_add_to_wishlist' ] );

		add_action( 'wp_ajax_sk_remove_from_wishlist', [ __CLASS__, 'ajax_remove_from_wishlist' ] );
		add_action( 'wp_ajax_nopriv_sk_remove_from_wishlist', [ __CLASS__, 'ajax_remove_from_wishlist' ] );

		add_action( 'wp_ajax_sk_get_wishlist_count', [ __CLASS__, 'ajax_get_count' ] );
		add_action( 'wp_ajax_nopriv_sk_get_wishlist_count', [ __CLASS__, 'ajax_get_count' ] );

		add_action( 'woocommerce_after_shop_loop_item', [ __CLASS__, 'render_wishlist_button' ], 20 );
		add_action( 'woocommerce_single_product_summary', [ __CLASS__, 'render_wishlist_button' ], 35 );
	}

	public static function get_wishlist_items() {
		if ( is_user_logged_in() ) {
			$items = get_user_meta( get_current_user_id(), '_sk_wishlist', true );
		} else {
			// Get from cookie
			$cookie = isset( $_COOKIE['sk_wishlist'] ) ? $_COOKIE['sk_wishlist'] : '';
			$items = $cookie ? explode( ',', $cookie ) : [];
		}

		if ( ! is_array( $items ) ) {
			$items = [];
		}

		return array_filter( array_map( 'intval', $items ) );
	}

	public static function ajax_add_to_wishlist() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Producto inválido.', 'skincare' ) ] );
		}

		$items = self::get_wishlist_items();

		if ( ! in_array( $product_id, $items ) ) {
			$items[] = $product_id;
			self::save_wishlist_items( $items );
		}

		wp_send_json_success(
			[
				'count' => count( $items ),
				'message' => __( 'Añadido a favoritos.', 'skincare' ),
			]
		);
	}

	public static function ajax_remove_from_wishlist() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
			wp_send_json_error( [ 'message' => __( 'Producto inválido.', 'skincare' ) ] );
		}

		$items = self::get_wishlist_items();

		if ( ( $key = array_search( $product_id, $items ) ) !== false ) {
			unset( $items[ $key ] );
			self::save_wishlist_items( $items );
		}

		wp_send_json_success(
			[
				'count' => count( $items ),
				'message' => __( 'Producto eliminado de favoritos.', 'skincare' ),
			]
		);
	}

	public static function ajax_get_count() {
		// No nonce needed for just getting count often
		$items = self::get_wishlist_items();
		wp_send_json_success( [ 'count' => count( $items ) ] );
	}

	private static function save_wishlist_items( $items ) {
		$items = array_unique( $items );

		if ( is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), '_sk_wishlist', $items );
		} else {
			// Set cookie for 30 days
			setcookie( 'sk_wishlist', implode( ',', $items ), time() + ( 86400 * 30 ), '/' );
		}
	}

	public static function is_in_wishlist( $product_id ) {
		$items = self::get_wishlist_items();
		return in_array( (int) $product_id, $items, true );
	}

	public static function render_wishlist_button() {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return;
		}

		global $product;
		if ( ! $product ) {
			return;
		}

		$product_id = $product->get_id();
		$in_wishlist = self::is_in_wishlist( $product_id );
		$label = $in_wishlist ? __( 'Quitar de favoritos', 'skincare' ) : __( 'Añadir a favoritos', 'skincare' );
		$classes = 'sk-wishlist-toggle' . ( $in_wishlist ? ' is-active' : '' );

		printf(
			'<button type="button" class="%1$s" data-product-id="%2$d" data-in-wishlist="%3$s" aria-pressed="%4$s">%5$s</button>',
			esc_attr( $classes ),
			(int) $product_id,
			$in_wishlist ? '1' : '0',
			$in_wishlist ? 'true' : 'false',
			esc_html( $label )
		);
	}
}
