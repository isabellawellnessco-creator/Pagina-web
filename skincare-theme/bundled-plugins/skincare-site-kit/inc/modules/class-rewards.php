<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards {

	public static function calculate_points( $order ) {
		if ( ! $order ) {
			return 0;
		}

		$total = $order->get_total();
		$refunded_total = $order->get_total_refunded();
		$net_total = max( 0, $total - $refunded_total );
		return (int) floor( $net_total );
	}

	public static function init() {
		// Award points on purchase
		add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'award_points' ] );
		add_action( 'woocommerce_order_status_refunded', [ __CLASS__, 'revoke_points' ] );
		add_action( 'woocommerce_order_status_cancelled', [ __CLASS__, 'revoke_points' ] );

		// AJAX Redeem
		add_action( 'wp_ajax_sk_redeem_points', [ __CLASS__, 'ajax_redeem_points' ] );

		// Shortcode
		add_shortcode( 'sk_user_points', [ __CLASS__, 'shortcode_user_points' ] );
	}

	public static function award_points( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id = $order->get_user_id();

		if ( ! $user_id ) {
			return;
		}

		if ( $order->get_meta( '_sk_rewards_awarded', true ) ) {
			return;
		}

		$points = self::calculate_points( $order );

		if ( $points <= 0 ) {
			return;
		}

		$current_points = get_user_meta( $user_id, '_sk_rewards_points', true );
		$current_points = $current_points ? intval( $current_points ) : 0;

		$new_points = $current_points + $points;

		update_user_meta( $user_id, '_sk_rewards_points', $new_points );

		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => $points,
			'reason' => 'Pedido #' . $order_id
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
		$order->update_meta_data( '_sk_rewards_awarded', $points );
		$order->save_meta_data();
	}

	public static function revoke_points( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id = $order->get_user_id();
		if ( ! $user_id ) {
			return;
		}

		$awarded = (int) $order->get_meta( '_sk_rewards_awarded', true );
		if ( $awarded <= 0 ) {
			return;
		}

		if ( $order->get_meta( '_sk_rewards_reversed', true ) ) {
			return;
		}

		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );
		$new_points = max( 0, $current_points - $awarded );
		update_user_meta( $user_id, '_sk_rewards_points', $new_points );

		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) {
			$history = [];
		}

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => -$awarded,
			'reason' => 'Reverso del pedido #' . $order_id,
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
		$order->update_meta_data( '_sk_rewards_reversed', 1 );
		$order->save_meta_data();
	}

	public static function ajax_redeem_points() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Inicio de sesión requerido' ] );
		}

		$points_to_redeem = 500; // Fixed for demo
		$discount_amount = 5;

		$user_id = get_current_user_id();
		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );

		if ( $current_points < $points_to_redeem ) {
			wp_send_json_error( [ 'message' => 'No tienes suficientes puntos' ] );
		}

		// Deduct
		update_user_meta( $user_id, '_sk_rewards_points', $current_points - $points_to_redeem );

		// Create Coupon
		$coupon_code = 'REWARD-' . strtoupper( wp_generate_password( 8, false ) );
		$coupon = new \WC_Coupon();
		$coupon->set_code( $coupon_code );
		$coupon->set_amount( $discount_amount );
		$coupon->set_discount_type( 'fixed_cart' );
		$coupon->set_usage_limit( 1 );
		$coupon->set_individual_use( true );
		$user = get_user_by( 'id', $user_id );
		if ( $user && $user->user_email ) {
			$coupon->set_email_restrictions( [ $user->user_email ] );
		}
		$coupon->save();

		// Log History
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) $history = [];
		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => -$points_to_redeem,
			'reason' => 'Canjeado por cupón de £' . $discount_amount
		];
		update_user_meta( $user_id, '_sk_rewards_history', $history );

		wp_send_json_success( [
			'message' => 'Canje exitoso.',
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
