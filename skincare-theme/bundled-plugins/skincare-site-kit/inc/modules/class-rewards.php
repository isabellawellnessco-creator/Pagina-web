<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards {

	public static function init() {
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

		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			\Skincare\SiteKit\Admin\Rewards_Master::award_points_for_order( $order );
		}
	}

	public static function revoke_points( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			\Skincare\SiteKit\Admin\Rewards_Master::revoke_points_for_order( $order );
		}
	}

	public static function ajax_redeem_points() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Inicio de sesión requerido' ] );
		}

		$rules = get_option( 'sk_rewards_rules', [] );
		$points_to_redeem = isset( $rules['redeem_points'] ) ? (int) $rules['redeem_points'] : 500;
		$discount_amount = isset( $rules['redeem_amount'] ) ? (float) $rules['redeem_amount'] : 5;

		if ( $points_to_redeem <= 0 || $discount_amount <= 0 ) {
			wp_send_json_error( [ 'message' => 'Configuración de canje inválida' ] );
		}

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
		$currency_symbol = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '£';
		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => -$points_to_redeem,
			'reason' => 'Canjeado por cupón de ' . $currency_symbol . $discount_amount
		];
		update_user_meta( $user_id, '_sk_rewards_history', $history );

		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			\Skincare\SiteKit\Admin\Rewards_Master::record_ledger_entry(
				$user_id,
				0,
				-$points_to_redeem,
				sprintf( 'Redeemed for coupon %s', $coupon_code )
			);
		}

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
