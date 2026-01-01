<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Forms {

	public static function init() {
		add_action( 'wp_ajax_sk_contact_submit', [ __CLASS__, 'handle_contact_submit' ] );
		add_action( 'wp_ajax_nopriv_sk_contact_submit', [ __CLASS__, 'handle_contact_submit' ] );
	}

	public static function handle_contact_submit() {
		// Nonce check
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$name = sanitize_text_field( $_POST['name'] );
		$email = sanitize_email( $_POST['email'] );
		$message = sanitize_textarea_field( $_POST['message'] );

		if ( ! $email || ! $message ) {
			wp_send_json_error( [ 'message' => __( 'Por favor completa los campos requeridos.', 'skincare' ) ] );
		}

		$to = get_option( 'admin_email' );
		$subject = 'Nuevo Mensaje de Contacto - ' . get_bloginfo( 'name' );
		$body = "Nombre: $name\nEmail: $email\n\nMensaje:\n$message";
		$headers = [ 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ];

		if ( wp_mail( $to, $subject, $body, $headers ) ) {
			wp_send_json_success( [ 'message' => __( '¡Mensaje enviado correctamente! Te responderemos pronto.', 'skincare' ) ] );
		} else {
			wp_send_json_error( [ 'message' => __( 'Hubo un error al enviar el mensaje.', 'skincare' ) ] );
		}
	}
}
