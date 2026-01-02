<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards {

	public static function init() {
		// Award points on purchase
		add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'award_points' ] );

		// AJAX Redeem
		add_action( 'wp_ajax_sk_redeem_points', [ __CLASS__, 'ajax_redeem_points' ] );

		// Shortcode
		add_shortcode( 'sk_user_points', [ __CLASS__, 'shortcode_user_points' ] );
	}

	public static function award_points( $order_id ) {
		$order = wc_get_order( $order_id );
		$user_id = $order->get_user_id();

		if ( ! $user_id ) return;

		$total = $order->get_total();
		$points = floor( $total );

		$current_points = get_user_meta( $user_id, '_sk_rewards_points', true );
		$current_points = $current_points ? intval( $current_points ) : 0;

		$new_points = $current_points + $points;

		update_user_meta( $user_id, '_sk_rewards_points', $new_points );

		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => $points,
			'reason' => 'Order #' . $order_id
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
	}

	public static function ajax_redeem_points() {
		if ( ! is_user_logged_in() ) wp_send_json_error( [ 'message' => 'Login required' ] );

		$points_to_redeem = 500; // Fixed for demo
		$discount_amount = 5;

		$user_id = get_current_user_id();
		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );

		if ( $current_points < $points_to_redeem ) {
			wp_send_json_error( [ 'message' => 'Not enough points' ] );
		}

		// Deduct
		update_user_meta( $user_id, '_sk_rewards_points', $current_points - $points_to_redeem );

		// Create Coupon
		$coupon_code = 'REWARD-' . strtoupper( wp_generate_password( 8, false ) );
		$coupon = new \WC_Coupon();
		$coupon->set_code( $coupon_code );
		$coupon->set_amount( $discount_amount );
		$coupon->set_discount_type( 'fixed_cart' );
		$coupon->save();

		// Log History
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];
		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => -$points_to_redeem,
			'reason' => 'Redeemed for £' . $discount_amount . ' Coupon'
		];
		update_user_meta( $user_id, '_sk_rewards_history', $history );

		wp_send_json_success( [
			'message' => 'Redeemed successfully!',
			'code' => $coupon_code,
			'new_balance' => $current_points - $points_to_redeem
		] );
	}

	public static function shortcode_user_points() {
		if ( ! is_user_logged_in() ) return '';
		$points = get_user_meta( get_current_user_id(), '_sk_rewards_points', true );
		return intval( $points );
	}
}
