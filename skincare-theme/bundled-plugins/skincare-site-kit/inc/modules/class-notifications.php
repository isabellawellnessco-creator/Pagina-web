<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Notifications {
	const SETTINGS_OPTION = 'sk_notification_settings';
	const TEMPLATES_OPTION = 'sk_notification_templates';

	public static function init() {
		add_action( 'wp_ajax_sk_send_test_email', [ __CLASS__, 'ajax_send_test_email' ] );
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'handle_order_status_change' ], 10, 4 );
	}

	public static function handle_order_status_change( $order_id, $old_status, $new_status, $order ) {
		$settings = get_option( self::SETTINGS_OPTION, [] );
		if ( empty( $settings['enable_auto_emails'] ) ) {
			return;
		}

		$template_key = null;
		if ( in_array( $new_status, [ 'processing', 'on-hold' ], true ) ) {
			$template_key = 'confirm';
		} elseif ( 'sk-on-the-way' === $new_status ) {
			$template_key = 'onway';
		} elseif ( in_array( $new_status, [ 'completed', 'sk-delivered' ], true ) ) {
			$template_key = 'delivered';
		}

		if ( ! $template_key || ! $order ) {
			return;
		}

		self::send_order_email( $order, $template_key );
	}

	public static function send_order_email( $order, $type ) {
		$templates = get_option( self::TEMPLATES_OPTION, [] );
		$subject_key = 'email_' . $type . '_subject';
		$body_key = 'email_' . $type . '_body';
		$subject = $templates[ $subject_key ] ?? '';
		$body = $templates[ $body_key ] ?? '';

		if ( empty( $subject ) || empty( $body ) ) {
			return false;
		}

		$placeholders = self::build_placeholders( $order );
		$subject = strtr( $subject, $placeholders );
		$body = strtr( $body, $placeholders );

		return self::send_email( $order->get_billing_email(), $subject, $body );
	}

	public static function send_coupon_email( $order, $coupon_code, $coupon_amount ) {
		$templates = get_option( self::TEMPLATES_OPTION, [] );
		$subject = $templates['email_coupon_subject'] ?? '';
		$body = $templates['email_coupon_body'] ?? '';

		if ( empty( $subject ) || empty( $body ) ) {
			return false;
		}

		$placeholders = self::build_placeholders( $order );
		$placeholders['{coupon_code}'] = $coupon_code;
		$placeholders['{coupon_amount}'] = $coupon_amount;

		$subject = strtr( $subject, $placeholders );
		$body = strtr( $body, $placeholders );

		return self::send_email( $order->get_billing_email(), $subject, $body );
	}

	public static function ajax_send_test_email() {
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'No autorizado.', 'skincare' ) ] );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
		$type = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : 'confirm';

		if ( ! $email ) {
			wp_send_json_error( [ 'message' => __( 'Ingresa un email válido.', 'skincare' ) ] );
		}

		$order = $order_id ? wc_get_order( $order_id ) : null;
		if ( ! $order ) {
			$order = self::build_mock_order( $email );
		}

		$sent = self::send_order_email( $order, $type );
		if ( ! $sent ) {
			wp_send_json_error( [ 'message' => __( 'No se pudo enviar el email.', 'skincare' ) ] );
		}

		wp_send_json_success( [ 'message' => __( 'Email enviado correctamente.', 'skincare' ) ] );
	}

	public static function get_whatsapp_template( $key ) {
		$templates = get_option( self::TEMPLATES_OPTION, [] );
		$map = [
			'confirm' => 'whatsapp_confirm',
			'delivery' => 'whatsapp_delivery',
			'onway' => 'whatsapp_onway',
			'delivered' => 'whatsapp_delivered',
		];
		$template_key = $map[ $key ] ?? '';
		if ( $template_key && ! empty( $templates[ $template_key ] ) ) {
			return $templates[ $template_key ];
		}

		$press = get_option( 'sk_press_page_settings', [] );
		$legacy_map = [
			'confirm' => 'whatsapp_template_confirm',
			'delivery' => 'whatsapp_template_delivery',
			'onway' => 'whatsapp_template_onway',
			'delivered' => 'whatsapp_template_delivered',
		];
		$legacy_key = $legacy_map[ $key ] ?? '';
		return $legacy_key && ! empty( $press[ $legacy_key ] ) ? $press[ $legacy_key ] : '';
	}

	private static function send_email( $to, $subject, $body ) {
		if ( empty( $to ) ) {
			return false;
		}

		$settings = get_option( self::SETTINGS_OPTION, [] );
		$sender_name = $settings['sender_name'] ?? get_bloginfo( 'name' );
		$sender_email = $settings['sender_email'] ?? get_option( 'admin_email' );

		$headers = [
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'From: %s <%s>', $sender_name, $sender_email ),
		];

		return wp_mail( $to, $subject, $body, $headers );
	}

	private static function build_placeholders( $order ) {
		$tracking_url = $order ? $order->get_meta( '_sk_tracking_url', true ) : '';
		$carrier = $order ? $order->get_meta( '_sk_carrier', true ) : '';
		$tracking_label = $tracking_url ? sprintf( __( 'Seguimiento: %s', 'skincare' ), $tracking_url ) : '';
		$carrier_label = $carrier ? sprintf( __( 'Transportista: %s', 'skincare' ), $carrier ) : '';

		return [
			'{order_number}' => $order ? $order->get_order_number() : '0000',
			'{total}' => $order ? $order->get_formatted_order_total() : 'S/ 0.00',
			'{customer_name}' => $order ? $order->get_formatted_billing_full_name() : __( 'Cliente', 'skincare' ),
			'{carrier}' => $carrier_label,
			'{tracking_url}' => $tracking_label,
		];
	}

	private static function build_mock_order( $email ) {
		$order = new \WC_Order();
		$order->set_billing_email( $email );
		$order->set_billing_first_name( __( 'Cliente', 'skincare' ) );
		$order->set_billing_last_name( __( 'Demo', 'skincare' ) );
		$order->set_total( 0 );
		$order->update_meta_data( '_sk_tracking_url', 'https://tracking.demo' );
		$order->update_meta_data( '_sk_carrier', 'Demo Carrier' );
		return $order;
	}
}
