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
		if ( ! $product_id ) wp_send_json_error();

		$items = self::get_wishlist_items();

		if ( ! in_array( $product_id, $items ) ) {
			$items[] = $product_id;
			self::save_wishlist_items( $items );
		}

		wp_send_json_success( [ 'count' => count( $items ) ] );
	}

	public static function ajax_remove_from_wishlist() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
		if ( ! $product_id ) wp_send_json_error();

		$items = self::get_wishlist_items();

		if ( ( $key = array_search( $product_id, $items ) ) !== false ) {
			unset( $items[ $key ] );
			self::save_wishlist_items( $items );
		}

		wp_send_json_success( [ 'count' => count( $items ) ] );
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
}
