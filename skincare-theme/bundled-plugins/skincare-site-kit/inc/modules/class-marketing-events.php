<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Marketing_Events
 * Centralizes marketing triggers and automation hooks.
 */
class Marketing_Events {

	public static function init() {
		// Cron for abandoned carts
		add_action( 'sk_daily_cron_job', [ __CLASS__, 'check_abandoned_carts' ] );
		if ( ! wp_next_scheduled( 'sk_daily_cron_job' ) ) {
			wp_schedule_event( time(), 'daily', 'sk_daily_cron_job' );
		}

		// Order Status Hooks
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'handle_order_status_change' ], 10, 4 );

		// Email Injection
		add_action( 'woocommerce_email_order_meta', [ __CLASS__, 'inject_tracking_link' ], 20, 3 );
	}

	/**
	 * Scans for carts that haven't been modified in 24 hours.
	 * Fires 'sk_marketing_cart_abandoned' for external integrations.
	 */
	public static function check_abandoned_carts() {
		global $wpdb;

		// This is a simplified logic. In a real persistent cart system, we'd query the sessions table.
		// Since standard WC stores carts in sessions, we can't easily query them via SQL without a persistent cart plugin.
		// However, we can hook into 'woocommerce_cart_updated' to save a timestamp in user meta if logged in.

		// For this architecture demo, we will fire an event for "Potential Abandonment" if we had a mechanism.
		// Let's assume we have a transient or a custom table.
		// To be safe and "native", we will rely on a hook that *would* be triggered by a session manager.

		// Alternative: Check "Pending" orders older than 1 hour (often failed payments or abandoned at checkout).
		$time_ago = date( 'Y-m-d H:i:s', strtotime( '-1 hour' ) );
		$pending_orders = wc_get_orders( [
			'status' => 'pending',
			'date_created' => '<' . $time_ago,
			'limit' => 20,
		] );

		foreach ( $pending_orders as $order ) {
			$order_id = $order->get_id();
			// Check if we already processed this
			if ( get_post_meta( $order_id, '_sk_abandoned_notified', true ) ) {
				continue;
			}

			// Fire Event
			do_action( 'sk_marketing_cart_abandoned', $order_id, $order->get_billing_email() );

			// Mark processed
			update_post_meta( $order_id, '_sk_abandoned_notified', time() );
		}
	}

	public static function handle_order_status_change( $order_id, $old_status, $new_status, $order ) {
		// Shipped Event
		if ( in_array( $new_status, [ 'completed', 'shipped' ] ) ) { // 'shipped' might be a custom status
			do_action( 'sk_marketing_order_shipped', $order_id, $order );
		}

		// First Purchase?
		$user_id = $order->get_user_id();
		if ( $user_id ) {
			$order_count = wc_get_customer_order_count( $user_id );
			if ( $order_count === 1 && $new_status === 'processing' ) {
				do_action( 'sk_marketing_first_purchase', $order_id, $user_id );
			}
		}
	}

	public static function inject_tracking_link( $order, $sent_to_admin, $plain_text ) {
		if ( $sent_to_admin || $plain_text ) {
			return;
		}

		// Only show on relevant emails (Processing, Completed)
		$status = $order->get_status();
		if ( ! in_array( $status, [ 'processing', 'completed', 'sk-on-the-way' ] ) ) {
			return;
		}

		$account_link = wc_get_account_endpoint_url( 'orders' ); // Or deep link to order view

		echo '<div style="margin-top: 20px; padding: 20px; background-color: #F8F5F1; border-radius: 8px; text-align: center;">';
		echo '<h3 style="color: #0F3062; margin-top: 0;">' . __( 'Sigue tu pedido', 'skincare' ) . '</h3>';
		echo '<p>' . __( 'Puedes ver el estado detallado de tu paquete y ganar puntos en tu panel de cuenta.', 'skincare' ) . '</p>';
		echo '<a href="' . esc_url( $account_link ) . '" style="background-color: #E5757E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">' . __( 'Ver mi Tracking', 'skincare' ) . '</a>';
		echo '</div>';
	}
}
