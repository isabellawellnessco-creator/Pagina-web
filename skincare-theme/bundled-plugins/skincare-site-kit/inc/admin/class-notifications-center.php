<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Notifications_Center {
	const SETTINGS_OPTION = 'sk_notification_settings';
	const TEMPLATES_OPTION = 'sk_notification_templates';
	const NONCE_ACTION = 'sk_notifications_save';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_sk_notifications_save', [ __CLASS__, 'handle_save' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Notificaciones', 'skincare' ),
			__( 'Emails & WhatsApp', 'skincare' ),
			'manage_options',
			'sk-notifications-center',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$settings = get_option( self::SETTINGS_OPTION, [] );
		$templates = get_option( self::TEMPLATES_OPTION, [] );
		$nonce = wp_create_nonce( self::NONCE_ACTION );
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Emails & WhatsApp', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Configura plantillas, revisa vistas previas y ejecuta pruebas controladas.', 'skincare' ); ?></p>

			<div class="sk-admin-tabs">
				<button class="sk-admin-tab is-active" data-target="sk-notifications-email"><?php esc_html_e( 'Emails', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-notifications-whatsapp"><?php esc_html_e( 'WhatsApp', 'skincare' ); ?></button>
				<button class="sk-admin-tab" data-target="sk-notifications-test"><?php esc_html_e( 'Tests', 'skincare' ); ?></button>
			</div>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=sk_notifications_save' ) ); ?>">
				<input type="hidden" name="sk_nonce" value="<?php echo esc_attr( $nonce ); ?>">

				<div class="sk-admin-panel is-active" id="sk-notifications-email">
					<h2><?php esc_html_e( 'Plantillas de email', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Se envían cuando el estado del pedido cambia o se genera un cupón automático.', 'skincare' ); ?></p>
					<div class="sk-admin-form-grid">
						<label>
							<?php esc_html_e( 'Nombre remitente', 'skincare' ); ?>
							<input type="text" name="settings[sender_name]" value="<?php echo esc_attr( $settings['sender_name'] ?? get_bloginfo( 'name' ) ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'Email remitente', 'skincare' ); ?>
							<input type="email" name="settings[sender_email]" value="<?php echo esc_attr( $settings['sender_email'] ?? get_option( 'admin_email' ) ); ?>">
						</label>
						<label class="sk-admin-checkbox">
							<input type="checkbox" name="settings[enable_auto_emails]" value="1" <?php checked( ! empty( $settings['enable_auto_emails'] ) ); ?>>
							<?php esc_html_e( 'Activar envíos automáticos de email.', 'skincare' ); ?>
						</label>
					</div>

					<div class="sk-admin-template-grid">
						<div>
							<h4><?php esc_html_e( 'Pedido confirmado', 'skincare' ); ?></h4>
							<input type="text" name="templates[email_confirm_subject]" value="<?php echo esc_attr( $templates['email_confirm_subject'] ?? __( 'Tu pedido #{order_number} está confirmado', 'skincare' ) ); ?>">
							<textarea name="templates[email_confirm_body]" rows="4"><?php echo esc_textarea( $templates['email_confirm_body'] ?? __( 'Hola {customer_name}, recibimos tu pedido #{order_number} por {total}. Te avisaremos cuando esté en camino.', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Pedido en camino', 'skincare' ); ?></h4>
							<input type="text" name="templates[email_onway_subject]" value="<?php echo esc_attr( $templates['email_onway_subject'] ?? __( 'Tu pedido #{order_number} va en camino', 'skincare' ) ); ?>">
							<textarea name="templates[email_onway_body]" rows="4"><?php echo esc_textarea( $templates['email_onway_body'] ?? __( 'Tu pedido #{order_number} está con {carrier}. {tracking_url}', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Pedido entregado', 'skincare' ); ?></h4>
							<input type="text" name="templates[email_delivered_subject]" value="<?php echo esc_attr( $templates['email_delivered_subject'] ?? __( 'Pedido #{order_number} entregado', 'skincare' ) ); ?>">
							<textarea name="templates[email_delivered_body]" rows="4"><?php echo esc_textarea( $templates['email_delivered_body'] ?? __( 'Pedido #{order_number} entregado. Gracias por confiar en nosotros.', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Cupón automático', 'skincare' ); ?></h4>
							<input type="text" name="templates[email_coupon_subject]" value="<?php echo esc_attr( $templates['email_coupon_subject'] ?? __( 'Tienes un cupón exclusivo', 'skincare' ) ); ?>">
							<textarea name="templates[email_coupon_body]" rows="4"><?php echo esc_textarea( $templates['email_coupon_body'] ?? __( 'Hola {customer_name}, tu cupón es {coupon_code} con {coupon_amount} de descuento.', 'skincare' ) ); ?></textarea>
						</div>
					</div>
				</div>

				<div class="sk-admin-panel" id="sk-notifications-whatsapp">
					<h2><?php esc_html_e( 'Plantillas de WhatsApp', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Puedes usar estas plantillas en acciones manuales o integrarlas con tu proveedor.', 'skincare' ); ?></p>
					<div class="sk-admin-form-grid">
						<label>
							<?php esc_html_e( 'WhatsApp Access Token', 'skincare' ); ?>
							<input type="text" name="settings[whatsapp_access_token]" value="<?php echo esc_attr( $settings['whatsapp_access_token'] ?? '' ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'WhatsApp Phone ID', 'skincare' ); ?>
							<input type="text" name="settings[whatsapp_phone_id]" value="<?php echo esc_attr( $settings['whatsapp_phone_id'] ?? '' ); ?>">
						</label>
					</div>
					<div class="sk-admin-template-grid">
						<div>
							<h4><?php esc_html_e( 'Confirmar pedido', 'skincare' ); ?></h4>
							<textarea name="templates[whatsapp_confirm]" rows="4"><?php echo esc_textarea( $templates['whatsapp_confirm'] ?? __( 'Hola {customer_name}, confirmamos tu pedido #{order_number}. Total: {total}.', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Registrar delivery', 'skincare' ); ?></h4>
							<textarea name="templates[whatsapp_delivery]" rows="4"><?php echo esc_textarea( $templates['whatsapp_delivery'] ?? __( 'Tu pedido #{order_number} fue asignado a delivery. Te contactaremos antes de la entrega.', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Pedido en camino', 'skincare' ); ?></h4>
							<textarea name="templates[whatsapp_onway]" rows="4"><?php echo esc_textarea( $templates['whatsapp_onway'] ?? __( 'Tu pedido #{order_number} va en camino. {carrier} {tracking_url}', 'skincare' ) ); ?></textarea>
						</div>
						<div>
							<h4><?php esc_html_e( 'Pedido entregado', 'skincare' ); ?></h4>
							<textarea name="templates[whatsapp_delivered]" rows="4"><?php echo esc_textarea( $templates['whatsapp_delivered'] ?? __( 'Pedido #{order_number} entregado. ¡Gracias!', 'skincare' ) ); ?></textarea>
						</div>
					</div>
					<p class="sk-admin-muted"><?php esc_html_e( 'Nota: el envío automático por API puede generar costos según tu proveedor.', 'skincare' ); ?></p>
				</div>

				<div class="sk-admin-panel" id="sk-notifications-test">
					<h2><?php esc_html_e( 'Vista previa y test', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Genera una vista previa o envía un correo de prueba antes de activar.', 'skincare' ); ?></p>
					<div class="sk-admin-form-grid">
						<label>
							<?php esc_html_e( 'Email de prueba', 'skincare' ); ?>
							<input type="email" id="sk-test-email" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'Pedido ejemplo', 'skincare' ); ?>
							<input type="text" id="sk-test-order" value="1024">
						</label>
						<button type="button" class="button" id="sk-send-test-email"><?php esc_html_e( 'Enviar email de prueba', 'skincare' ); ?></button>
					</div>
					<div class="sk-admin-form-grid">
						<label>
							<?php esc_html_e( 'Teléfono WhatsApp (con código país)', 'skincare' ); ?>
							<input type="text" id="sk-test-whatsapp" value="51999999999">
						</label>
						<button type="button" class="button" id="sk-preview-whatsapp"><?php esc_html_e( 'Vista previa WhatsApp', 'skincare' ); ?></button>
					</div>
					<div id="sk-whatsapp-preview" class="sk-admin-report"></div>
					<div id="sk-test-feedback" class="sk-admin-report"></div>
				</div>

				<div style="margin-top: 16px;">
					<button class="button button-primary"><?php esc_html_e( 'Guardar cambios', 'skincare' ); ?></button>
				</div>
			</form>
		</div>
		<?php
	}

	public static function handle_save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No autorizado.', 'skincare' ) );
		}

		check_admin_referer( self::NONCE_ACTION, 'sk_nonce' );

		$settings = isset( $_POST['settings'] ) ? (array) wp_unslash( $_POST['settings'] ) : [];
		$templates = isset( $_POST['templates'] ) ? (array) wp_unslash( $_POST['templates'] ) : [];

		$sanitized_settings = [
			'sender_name' => sanitize_text_field( $settings['sender_name'] ?? '' ),
			'sender_email' => sanitize_email( $settings['sender_email'] ?? '' ),
			'enable_auto_emails' => ! empty( $settings['enable_auto_emails'] ) ? 1 : 0,
			'whatsapp_access_token' => sanitize_text_field( $settings['whatsapp_access_token'] ?? '' ),
			'whatsapp_phone_id' => sanitize_text_field( $settings['whatsapp_phone_id'] ?? '' ),
		];

		$sanitized_templates = [];
		foreach ( $templates as $key => $value ) {
			$sanitized_templates[ sanitize_key( $key ) ] = sanitize_textarea_field( $value );
		}

		update_option( self::SETTINGS_OPTION, $sanitized_settings );
		update_option( self::TEMPLATES_OPTION, $sanitized_templates );

		wp_safe_redirect( add_query_arg( [ 'page' => 'sk-notifications-center', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
		exit;
	}
}
