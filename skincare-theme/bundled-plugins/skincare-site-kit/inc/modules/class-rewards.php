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

		$user_id = get_current_user_id();
		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			$result = \Skincare\SiteKit\Admin\Rewards_Master::redeem_points_for_user( $user_id );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( [ 'message' => $result->get_error_message() ] );
			}
			wp_send_json_success( $result );
		}

		wp_send_json_error( [ 'message' => 'Rewards system unavailable' ] );
	}

	public static function shortcode_user_points() {
		if ( ! is_user_logged_in() ) {
			return '';
		}
		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			return \Skincare\SiteKit\Admin\Rewards_Master::get_user_balance( get_current_user_id() );
		}
		return 0;
	}
}
