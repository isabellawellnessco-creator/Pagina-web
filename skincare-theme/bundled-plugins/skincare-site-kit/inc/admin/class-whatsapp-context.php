<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Whatsapp_Context {

	public static function init() {
		add_action( 'wp_ajax_sk_log_whatsapp_click', [ __CLASS__, 'ajax_log_click' ] );
	}

	public static function get_templates() {
		return [
			'confirm_cod' => [
				'label' => __( 'Confirmar Pedido', 'skincare' ),
				'text'  => "Hola {NOMBRE}, gracias por tu compra en Skin Cupid.\nPara confirmar tu pedido *{PEDIDO}* (Total: {TOTAL}) con pago Contraentrega, por favor responde a este mensaje.\nDirección: {DIRECCION}",
			],
			'reminder' => [
				'label' => __( 'Recordatorio', 'skincare' ),
				'text'  => "Hola {NOMBRE}, intentamos contactarte para tu pedido *{PEDIDO}* pero no tuvimos respuesta. Por favor confírmanos si aún deseas recibirlo.",
			],
			'advance_request' => [
				'label' => __( 'Solicitar Adelanto', 'skincare' ),
				'text'  => "Hola {NOMBRE}, para proceder con el envío a provincia de tu pedido *{PEDIDO}*, requerimos un adelanto de seguridad. Puedes yapear a este número y enviarnos la constancia.",
			],
			'dispatched_shalom' => [
				'label' => __( 'Enviado Shalom', 'skincare' ),
				'text'  => "¡Hola {NOMBRE}! Tu pedido *{PEDIDO}* ya fue dejado en Shalom.\nTicket: *{TICKET}*\nClave: *{CLAVE}*\nPuedes rastrearlo aquí: https://rastreo.shalom.com.pe/",
			],
			'dispatched_urpi' => [
				'label' => __( 'Enviado Lima', 'skincare' ),
				'text'  => "¡Hola {NOMBRE}! Tu pedido *{PEDIDO}* está en ruta con nuestro courier.\nLlegará a: {DIRECCION}.",
			],
			'ready_pickup' => [
				'label' => __( 'Listo para Recoger', 'skincare' ),
				'text'  => "¡Tu pedido *{PEDIDO}* ya llegó a la agencia Shalom! Recógelo con tu DNI y la Clave *{CLAVE}*.",
			],
			'completed' => [
				'label' => __( 'Pedido Entregado', 'skincare' ),
				'text'  => "¡Gracias por tu compra {NOMBRE}! Esperamos que disfrutes tus productos.",
			],
		];
	}

	public static function get_suggested_template_id( $order ) {
		$status = $order->get_meta( Operations_Core::META_STATUS, true );
		$zone = $order->get_meta( Operations_Core::META_ZONE, true );
		$payment = $order->get_meta( Operations_Core::META_PAYMENT_TYPE, true );

		if ( ! $status || $status === Operations_Core::STATUS_PENDING_CONFIRM ) {
			if ( $payment === 'contraentrega' ) {
				return 'confirm_cod';
			}
			if ( $zone === 'provincia' ) {
				return 'advance_request';
			}
		}

		if ( $status === Operations_Core::STATUS_NO_RESPONSE_1 || $status === Operations_Core::STATUS_NO_RESPONSE_2 ) {
			return 'reminder';
		}

		if ( $status === Operations_Core::STATUS_DISPATCHED_PROV ) {
			return 'dispatched_shalom';
		}

		if ( $status === Operations_Core::STATUS_DISPATCHED_LIMA ) {
			return 'dispatched_urpi';
		}

		if ( $status === Operations_Core::STATUS_READY_PICKUP ) {
			return 'ready_pickup';
		}

		if ( $status === Operations_Core::STATUS_DELIVERED ) {
			return 'completed';
		}

		return null; // No suggestion
	}

	public static function generate_link( $order, $template_id ) {
		$templates = self::get_templates();
		if ( ! isset( $templates[ $template_id ] ) ) {
			return '#';
		}

		$text = $templates[ $template_id ]['text'];

		// Variables
		$vars = [
			'{NOMBRE}'    => $order->get_billing_first_name(),
			'{PEDIDO}'    => $order->get_order_number(),
			'{TOTAL}'     => $order->get_formatted_order_total(),
			'{DIRECCION}' => $order->get_billing_address_1() . ' ' . $order->get_billing_city(),
			'{TICKET}'    => $order->get_meta( Operations_Core::META_TICKET, true ) ?: 'te lo enviamos pronto',
			'{CLAVE}'     => $order->get_meta( Operations_Core::META_PICKUP_CODE, true ) ?: '****',
		];

		$text = str_replace( array_keys( $vars ), array_values( $vars ), $text );

		$phone = $order->get_billing_phone();
		$phone = preg_replace( '/\D/', '', $phone ); // Numbers only

		return 'https://wa.me/' . $phone . '?text=' . rawurlencode( $text );
	}

	public static function render_action_button( $order ) {
		$tpl_id = self::get_suggested_template_id( $order );
		if ( ! $tpl_id ) {
			return '';
		}

		$templates = self::get_templates();
		$label = $templates[ $tpl_id ]['label'];
		$link = self::generate_link( $order, $tpl_id );

		?>
		<a href="<?php echo esc_url( $link ); ?>"
		   class="button button-hero sk-wa-action-btn"
		   target="_blank"
		   style="background-color: #25D366; color: #fff; border-color: #25D366; display: flex; align-items: center; justify-content: center; margin-top: 10px;"
		   data-order-id="<?php echo $order->get_id(); ?>"
		   data-template="<?php echo esc_attr( $tpl_id ); ?>">
			<span class="dashicons dashicons-whatsapp" style="margin-right: 5px;"></span>
			<?php echo esc_html( $label ); ?>
		</a>
		<?php
	}

	public static function ajax_log_click() {
		check_ajax_referer( 'sk_ops_action', 'nonce' );

		$order_id = absint( $_POST['order_id'] );
		$template = sanitize_text_field( $_POST['template'] );

		$order = wc_get_order( $order_id );
		if ( $order ) {
			$order->add_order_note( sprintf( __( 'WhatsApp enviado (Click): Plantilla %s', 'skincare' ), $template ) );
			$order->update_meta_data( '_sk_whatsapp_last_sent', current_time( 'mysql' ) );
			$order->save();
		}

		wp_die();
	}
}
