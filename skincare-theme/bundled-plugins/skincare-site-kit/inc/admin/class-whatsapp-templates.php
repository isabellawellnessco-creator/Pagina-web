<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Whatsapp_Templates {
	const OPTION_NAME = 'sk_whatsapp_templates';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_sk_save_whatsapp_templates', [ __CLASS__, 'save_templates' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Plantillas WhatsApp', 'skincare' ),
			__( 'Plantillas WhatsApp', 'skincare' ),
			'manage_woocommerce',
			'sk-whatsapp-templates',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function get_templates() {
		$defaults = [
			[
				'id' => 'confirm',
				'title' => 'Confirmar Pedido',
				'message' => "Hola {customer_name}, confirmamos tu pedido #{order_id}. \nTotal: {total}\nDirección: {address}\n\nLo estaremos preparando pronto. ¡Gracias por elegir Skin Cupid!",
			],
			[
				'id' => 'shipping',
				'title' => 'Pedido Enviado',
				'message' => "¡Buenas noticias {customer_name}! \nTu pedido #{order_id} ha sido enviado. \nLlega aprox: {delivery_date}. \nTracking: {tracking_url}",
			],
			[
				'id' => 'pickup',
				'title' => 'Listo para Recoger',
				'message' => "Hola {customer_name}, tu pedido #{order_id} ya está listo para recoger en nuestra tienda. \nHorario: 9am - 6pm.",
			],
		];
		return get_option( self::OPTION_NAME, $defaults );
	}

	public static function render_page() {
		$templates = self::get_templates();
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Plantillas de Mensajes WhatsApp', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Configura los mensajes predefinidos para enviar manualmente desde el Dashboard.', 'skincare' ); ?></p>

			<div class="notice notice-info inline">
				<p>
					<strong>Variables disponibles:</strong>
					<code>{customer_name}</code>, <code>{order_id}</code>, <code>{total}</code>,
					<code>{address}</code>, <code>{tracking_url}</code>, <code>{delivery_date}</code>
				</p>
			</div>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="sk_save_whatsapp_templates">
				<?php wp_nonce_field( 'sk_save_whatsapp_templates_action', 'sk_nonce' ); ?>

				<div id="sk-templates-container">
					<?php foreach ( $templates as $index => $template ) : ?>
						<div class="sk-admin-card" style="margin-bottom: 20px;">
							<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
								<label><strong>Título de la Plantilla</strong></label>
								<button type="button" class="button sk-remove-template" style="color: #b32d2e; border-color: #b32d2e;">Eliminar</button>
							</div>
							<input type="text" name="templates[<?php echo $index; ?>][title]" value="<?php echo esc_attr( $template['title'] ); ?>" class="widefat" placeholder="Ej: Confirmación de Pedido">
							<input type="hidden" name="templates[<?php echo $index; ?>][id]" value="<?php echo esc_attr( $template['id'] ?? 'tpl_' . uniqid() ); ?>">

							<div style="margin-top: 10px;">
								<label><strong>Mensaje</strong></label>
								<textarea name="templates[<?php echo $index; ?>][message]" rows="4" class="widefat"><?php echo esc_textarea( $template['message'] ); ?></textarea>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" id="sk-add-template" class="button button-secondary">
					<span class="dashicons dashicons-plus-alt2" style="margin-top: 3px;"></span> Añadir Nueva Plantilla
				</button>

				<hr>

				<button type="submit" class="button button-primary button-hero">Guardar Cambios</button>
			</form>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const container = document.getElementById('sk-templates-container');
					const addBtn = document.getElementById('sk-add-template');

					// Remove template
					container.addEventListener('click', function(e) {
						if (e.target.classList.contains('sk-remove-template')) {
							if (confirm('¿Estás seguro de eliminar esta plantilla?')) {
								e.target.closest('.sk-admin-card').remove();
							}
						}
					});

					// Add template
					addBtn.addEventListener('click', function() {
						const index = container.children.length;
						const html = `
							<div class="sk-admin-card" style="margin-bottom: 20px;">
								<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
									<label><strong>Título de la Plantilla</strong></label>
									<button type="button" class="button sk-remove-template" style="color: #b32d2e; border-color: #b32d2e;">Eliminar</button>
								</div>
								<input type="text" name="templates[${index}][title]" value="" class="widefat" placeholder="Ej: Nueva Plantilla">
								<input type="hidden" name="templates[${index}][id]" value="tpl_${Date.now()}">
								<div style="margin-top: 10px;">
									<label><strong>Mensaje</strong></label>
									<textarea name="templates[${index}][message]" rows="4" class="widefat"></textarea>
								</div>
							</div>
						`;
						container.insertAdjacentHTML('beforeend', html);
					});
				});
			</script>
		</div>
		<?php
	}

	public static function save_templates() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'Unauthorized' );
		}
		check_admin_referer( 'sk_save_whatsapp_templates_action', 'sk_nonce' );

		$raw_templates = isset( $_POST['templates'] ) ? $_POST['templates'] : [];
		$clean_templates = [];

		foreach ( $raw_templates as $tpl ) {
			if ( ! empty( $tpl['title'] ) ) {
				$clean_templates[] = [
					'id' => sanitize_text_field( $tpl['id'] ),
					'title' => sanitize_text_field( $tpl['title'] ),
					'message' => sanitize_textarea_field( $tpl['message'] ),
				];
			}
		}

		update_option( self::OPTION_NAME, $clean_templates );
		wp_redirect( admin_url( 'admin.php?page=sk-whatsapp-templates&success=1' ) );
		exit;
	}
}
