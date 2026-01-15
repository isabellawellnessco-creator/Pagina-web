<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Coupons_Automation {
	const OPTION = 'sk_coupon_automation_settings';

	public static function init() {
		add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'maybe_issue_coupon' ] );
	}

	public static function maybe_issue_coupon( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		if ( $order->get_meta( '_sk_auto_coupon_code', true ) ) {
			return;
		}

		$settings = get_option( self::OPTION, [] );
		if ( empty( $settings['enabled'] ) ) {
			return;
		}

		$total = (float) $order->get_total();
		$min_total = isset( $settings['min_order_total'] ) ? (float) $settings['min_order_total'] : 0;
		if ( $min_total > 0 && $total < $min_total ) {
			return;
		}

		$user_id = $order->get_user_id();
		$purchase_count_threshold = isset( $settings['purchase_count_threshold'] ) ? (int) $settings['purchase_count_threshold'] : 0;
		if ( $user_id && $purchase_count_threshold > 0 ) {
			$completed_orders = wc_get_orders( [
				'customer_id' => $user_id,
				'status' => [ 'completed' ],
				'limit' => -1,
				'return' => 'ids',
			] );
			if ( count( $completed_orders ) < $purchase_count_threshold ) {
				return;
			}
		}

		if ( ! empty( $settings['first_purchase_only'] ) && $user_id ) {
			$previous_orders = wc_get_orders( [
				'customer_id' => $user_id,
				'status' => [ 'completed' ],
				'limit' => 2,
				'return' => 'ids',
			] );
			if ( count( $previous_orders ) > 1 ) {
				return;
			}
		}

		$coupon_amount = isset( $settings['coupon_amount'] ) ? (float) $settings['coupon_amount'] : 0;
		if ( $coupon_amount <= 0 ) {
			return;
		}

		$coupon_type = isset( $settings['coupon_type'] ) ? $settings['coupon_type'] : 'fixed_cart';
		$expires_days = isset( $settings['expires_days'] ) ? (int) $settings['expires_days'] : 0;

		$coupon_code = 'AUTO-' . strtoupper( wp_generate_password( 8, false ) );
		$coupon = new \WC_Coupon();
		$coupon->set_code( $coupon_code );
		$coupon->set_amount( $coupon_amount );
		$coupon->set_discount_type( $coupon_type );
		$coupon->set_usage_limit( 1 );
		$coupon->set_individual_use( true );

		$email = $order->get_billing_email();
		if ( $email ) {
			$coupon->set_email_restrictions( [ $email ] );
		}

		if ( $expires_days > 0 ) {
			$coupon->set_date_expires( strtotime( '+' . $expires_days . ' days' ) );
		}

		$coupon->save();

		$order->update_meta_data( '_sk_auto_coupon_code', $coupon_code );
		$order->save_meta_data();

		Notifications::send_coupon_email( $order, $coupon_code, $coupon_amount );
	}
}
