<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tools {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
		add_action( 'admin_post_sk_tools_action', [ __CLASS__, 'handle_actions' ] );
	}

	public static function register_page() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Herramientas', 'skincare' ),
			__( 'Herramientas', 'skincare' ),
			'manage_options',
			'sk-tools',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function handle_actions() {
		check_admin_referer( 'sk_tools_action', 'sk_tools_nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		$action = isset( $_POST['tool_action'] ) ? sanitize_text_field( $_POST['tool_action'] ) : '';

		switch ( $action ) {
			case 'flush_permalinks':
				flush_rewrite_rules();
				add_settings_error( 'sk_tools', 'flushed', __( 'Enlaces permanentes actualizados.', 'skincare' ), 'success' );
				break;
			case 'clear_transients':
				global $wpdb;
				$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('_transient_sk_%')" );
				add_settings_error( 'sk_tools', 'cleared', __( 'Caché interna (Transients) limpiada.', 'skincare' ), 'success' );
				break;
			case 'force_update_css':
				update_option( 'sk_css_version', time() );
				add_settings_error( 'sk_tools', 'css_updated', __( 'Versión de CSS forzada para evitar caché de navegador.', 'skincare' ), 'success' );
				break;
			case 'reset_seeder':
				delete_option( \Skincare\SiteKit\Modules\Seeder::OPTION_NAME );
				add_settings_error( 'sk_tools', 'seeder_reset', __( 'Estado del Seeder reiniciado. Puedes volver a ejecutar el Asistente.', 'skincare' ), 'success' );
				break;
		}

		set_transient( 'sk_tools_errors', get_settings_errors( 'sk_tools' ), 30 );
		wp_safe_redirect( admin_url( 'admin.php?page=sk-tools' ) );
		exit;
	}

	public static function render_page() {
		$errors = get_transient( 'sk_tools_errors' );
		if ( $errors ) {
			foreach ( $errors as $error ) {
				echo '<div class="notice notice-' . esc_attr( $error['type'] ) . ' is-dismissible"><p>' . esc_html( $error['message'] ) . '</p></div>';
			}
			delete_transient( 'sk_tools_errors' );
		}
		?>
		<div class="wrap">
			<h1><?php _e( 'Herramientas del Sistema', 'skincare' ); ?></h1>
			<p><?php _e( 'Utilidades para mantenimiento y resolución de problemas.', 'skincare' ); ?></p>

			<div class="card" style="max-width: 800px; padding: 20px;">
				<h2><?php _e( 'Acciones Rápidas', 'skincare' ); ?></h2>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><?php _e( 'Enlaces Permanentes', 'skincare' ); ?></th>
							<td>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="sk_tools_action">
									<input type="hidden" name="tool_action" value="flush_permalinks">
									<?php wp_nonce_field( 'sk_tools_action', 'sk_tools_nonce' ); ?>
									<button type="submit" class="button"><?php _e( 'Actualizar Permalinks', 'skincare' ); ?></button>
									<p class="description"><?php _e( 'Usa esto si recibes errores 404 en páginas personalizadas.', 'skincare' ); ?></p>
								</form>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Caché Interna', 'skincare' ); ?></th>
							<td>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="sk_tools_action">
									<input type="hidden" name="tool_action" value="clear_transients">
									<?php wp_nonce_field( 'sk_tools_action', 'sk_tools_nonce' ); ?>
									<button type="submit" class="button"><?php _e( 'Limpiar Transients', 'skincare' ); ?></button>
									<p class="description"><?php _e( 'Borra datos temporales almacenados por el plugin (ej. caché de Instagram).', 'skincare' ); ?></p>
								</form>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Versión CSS', 'skincare' ); ?></th>
							<td>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="sk_tools_action">
									<input type="hidden" name="tool_action" value="force_update_css">
									<?php wp_nonce_field( 'sk_tools_action', 'sk_tools_nonce' ); ?>
									<button type="submit" class="button"><?php _e( 'Forzar recarga de CSS', 'skincare' ); ?></button>
									<p class="description"><?php _e( 'Cambia el ID de versión de los assets para que los usuarios vean los cambios de estilo inmediatamente.', 'skincare' ); ?></p>
								</form>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Reiniciar Seeder', 'skincare' ); ?></th>
							<td>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="sk_tools_action">
									<input type="hidden" name="tool_action" value="reset_seeder">
									<?php wp_nonce_field( 'sk_tools_action', 'sk_tools_nonce' ); ?>
									<button type="submit" class="button button-link-delete"><?php _e( 'Reiniciar Estado de Importación', 'skincare' ); ?></button>
									<p class="description"><?php _e( 'Permite volver a ejecutar el Asistente de Configuración desde cero. No borra contenido, solo reinicia la marca de "completado".', 'skincare' ); ?></p>
								</form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
}
