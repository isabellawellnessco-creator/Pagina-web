<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards {

	public static function init() {
		// Award points on purchase
		add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'award_points' ] );

		// Shortcode to display points
		add_shortcode( 'sk_user_points', [ __CLASS__, 'shortcode_user_points' ] );
	}

	public static function award_points( $order_id ) {
		$order = wc_get_order( $order_id );
		$user_id = $order->get_user_id();

		if ( ! $user_id ) return;

		// 1 Point for every 1 unit of currency (simplified)
		$total = $order->get_total();
		$points = floor( $total );

		$current_points = get_user_meta( $user_id, '_sk_rewards_points', true );
		$current_points = $current_points ? intval( $current_points ) : 0;

		$new_points = $current_points + $points;

		update_user_meta( $user_id, '_sk_rewards_points', $new_points );

		// Log History (Optional, storing in user meta for simplicity in this brief)
		// A real system would use a custom table
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => $points,
			'reason' => 'Order #' . $order_id
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
	}

	public static function shortcode_user_points() {
		if ( ! is_user_logged_in() ) return '';

		$points = get_user_meta( get_current_user_id(), '_sk_rewards_points', true );
		return intval( $points );
	}
}
