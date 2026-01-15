<?php
namespace Skincare\SiteKit\Admin;

use Skincare\SiteKit\Modules\Seeder;
use Skincare\SiteKit\Modules\Tracking_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Dashboard {

	public static function init() {
		// Replaces the old settings page callback
	}

	public static function render() {
		wp_enqueue_style( 'sk-site-kit-admin-dashboard', SKINCARE_KIT_URL . 'assets/css/admin-dashboard.css', [], '1.0.0' );

		// Data gathering
		$theme_builder_active = ! empty( get_option( 'sk_theme_builder_settings', [] ) );
		$rewards_active = class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' );

		// Smart Check Results
		$smart_check = Seeder::run_smart_check();

		// Tracking Check: Get last 20 orders without tracking
		$pending_tracking = 0;
		if ( function_exists( 'wc_get_orders' ) ) {
			$pending_tracking = count( wc_get_orders( [
				'limit' => 20,
				'status' => [ 'processing' ],
				'meta_query' => [
					[ 'key' => '_sk_tracking_number', 'compare' => 'NOT EXISTS' ]
				]
			] ) );
		}

		// SMTP Check
		$smtp_active = is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) || is_plugin_active( 'post-smtp/postman-smtp.php' );

		// WhatsApp Check
		$notifications = get_option( 'sk_notification_settings', [] );
		$whatsapp_configured = ! empty( $notifications['whatsapp_access_token'] );

		// Last Log
		$logs = get_option( Seeder::LOG_OPTION, [] );
		$last_log = ! empty( $logs ) ? $logs[0] : null;

		// Health Checks Array
		$health_checks = [
			[
				'title' => __( 'Integridad del Sitio', 'skincare' ),
				'desc' => $smart_check['status'] === 'ok' ? __( 'Todos los componentes base están instalados.', 'skincare' ) : __( 'Faltan componentes. Ejecuta el asistente.', 'skincare' ),
				'status' => $smart_check['status'],
				'action' => $smart_check['status'] === 'ok' ? '' : admin_url( 'admin.php?page=sk-onboarding&mode=repair' ),
				'action_label' => __( 'Reparar', 'skincare' ),
			],
			[
				'title' => __( 'Servicio de Email (SMTP)', 'skincare' ),
				'desc' => $smtp_active ? __( 'Plugin SMTP detectado.', 'skincare' ) : __( 'No se detectó plugin SMTP. Recomendado para entregabilidad.', 'skincare' ),
				'status' => $smtp_active ? 'ok' : 'warning',
				'action' => '',
			],
			[
				'title' => __( 'WhatsApp API', 'skincare' ),
				'desc' => $whatsapp_configured ? __( 'Token de acceso configurado.', 'skincare' ) : __( 'No configurado. Revisa Notificaciones > WhatsApp.', 'skincare' ),
				'status' => $whatsapp_configured ? 'ok' : 'warning',
				'action' => $whatsapp_configured ? '' : admin_url( 'admin.php?page=sk-notifications-center' ),
				'action_label' => __( 'Configurar', 'skincare' ),
			],
			[
				'title' => __( 'Tracking de Pedidos', 'skincare' ),
				'desc' => $pending_tracking === 0 ? __( 'Todos los pedidos recientes tienen tracking.', 'skincare' ) : sprintf( __( '%d pedidos recientes sin tracking.', 'skincare' ), $pending_tracking ),
				'status' => $pending_tracking === 0 ? 'ok' : 'warning',
				'action' => $pending_tracking === 0 ? '' : admin_url( 'edit.php?post_type=shop_order' ),
				'action_label' => __( 'Ver Pedidos', 'skincare' ),
			]
		];

		// Tax Check
		if ( ! taxonomy_exists( 'pa_brand' ) ) {
			$health_checks[] = [
				'title' => __( 'Filtro de Marcas', 'skincare' ),
				'desc' => __( 'Taxonomía "pa_brand" no existe. El filtro de marcas se ocultará.', 'skincare' ),
				'status' => 'warning',
				'action' => '',
			];
		}

		?>
		<div class="wrap">
			<div class="sk-control-center-header">
				<div>
					<h1><?php _e( 'Skin Cupid Control Center', 'skincare' ); ?></h1>
					<p><?php _e( 'Versión del sistema: 2.1.0', 'skincare' ); ?></p>
				</div>
				<div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>" class="button button-secondary"><?php _e( 'Reparar Instalación', 'skincare' ); ?></a>
				</div>
			</div>

			<div class="sk-dashboard-grid">
				<!-- Theme Builder Card -->
				<div class="sk-dashboard-card">
					<div class="sk-card-header">
						<div class="sk-card-icon"><span class="dashicons dashicons-layout"></span></div>
						<h3 class="sk-card-title"><?php _e( 'Theme Builder', 'skincare' ); ?></h3>
						<div class="sk-status-indicator <?php echo $theme_builder_active ? 'is-active' : 'is-warning'; ?>"></div>
					</div>
					<div class="sk-card-body">
						<p><?php _e( 'Controla el diseño de Cabeceras, Pies de página y Fichas de producto.', 'skincare' ); ?></p>
					</div>
					<div class="sk-card-footer">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-theme-builder' ) ); ?>" class="sk-link"><?php _e( 'Configurar', 'skincare' ); ?> →</a>
					</div>
				</div>

				<!-- Rewards Card -->
				<div class="sk-dashboard-card">
					<div class="sk-card-header">
						<div class="sk-card-icon"><span class="dashicons dashicons-awards"></span></div>
						<h3 class="sk-card-title"><?php _e( 'Sistema de Puntos', 'skincare' ); ?></h3>
						<div class="sk-status-indicator <?php echo $rewards_active ? 'is-active' : 'is-inactive'; ?>"></div>
					</div>
					<div class="sk-card-body">
						<p><?php _e( 'Gestión de lealtad, historial de puntos y canjes.', 'skincare' ); ?></p>
					</div>
					<div class="sk-card-footer">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-rewards-control' ) ); ?>" class="sk-link"><?php _e( 'Administrar', 'skincare' ); ?> →</a>
					</div>
				</div>

				<!-- Operations Card -->
				<div class="sk-dashboard-card">
					<div class="sk-card-header">
						<div class="sk-card-icon"><span class="dashicons dashicons-chart-line"></span></div>
						<h3 class="sk-card-title"><?php _e( 'Operaciones', 'skincare' ); ?></h3>
						<div class="sk-status-indicator is-active"></div>
					</div>
					<div class="sk-card-body">
						<p><?php _e( 'Dashboard de pedidos, almacén y métricas de cumplimiento.', 'skincare' ); ?></p>
					</div>
					<div class="sk-card-footer">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-operations-dashboard' ) ); ?>" class="sk-link"><?php _e( 'Ver Dashboard', 'skincare' ); ?> →</a>
					</div>
				</div>

				<!-- Tools Card -->
				<div class="sk-dashboard-card">
					<div class="sk-card-header">
						<div class="sk-card-icon"><span class="dashicons dashicons-hammer"></span></div>
						<h3 class="sk-card-title"><?php _e( 'Herramientas', 'skincare' ); ?></h3>
						<div class="sk-status-indicator is-active"></div>
					</div>
					<div class="sk-card-body">
						<p><?php _e( 'Mantenimiento, reinicio de importación y caché.', 'skincare' ); ?></p>
					</div>
					<div class="sk-card-footer">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-tools' ) ); ?>" class="sk-link"><?php _e( 'Abrir Herramientas', 'skincare' ); ?> →</a>
					</div>
				</div>
			</div>

			<div class="sk-health-section">
				<div class="sk-health-header">
					<h2><?php _e( 'Salud del Sistema', 'skincare' ); ?></h2>
				</div>
				<ul class="sk-health-list">
					<?php foreach ( $health_checks as $check ) : ?>
						<li class="sk-health-item">
							<div class="sk-health-status <?php echo esc_attr( $check['status'] ); ?>">
								<span class="dashicons dashicons-<?php echo $check['status'] === 'ok' ? 'yes' : 'warning'; ?>"></span>
							</div>
							<div class="sk-health-info">
								<h3><?php echo esc_html( $check['title'] ); ?></h3>
								<p><?php echo esc_html( $check['desc'] ); ?></p>
							</div>
							<?php if ( ! empty( $check['action'] ) ) : ?>
								<div class="sk-health-action">
									<a href="<?php echo esc_url( $check['action'] ); ?>" class="button button-small"><?php echo esc_html( $check['action_label'] ?? __( 'Corregir', 'skincare' ) ); ?></a>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php if ( $last_log ) : ?>
			<div class="sk-health-section" style="margin-top: 20px;">
				<div class="sk-health-header">
					<h2><?php _e( 'Última Ejecución del Seeder', 'skincare' ); ?></h2>
					<button class="button button-small" onclick="document.getElementById('sk-history-modal').style.display='block'"><?php _e( 'Ver Historial', 'skincare' ); ?></button>
				</div>
				<div class="sk-log-summary">
					<p>
						<strong><?php echo date( 'd/m/Y H:i:s', $last_log['timestamp'] ); ?></strong> -
						<span class="sk-log-status <?php echo esc_attr( $last_log['status'] ); ?>"><?php echo esc_html( strtoupper( $last_log['status'] ) ); ?></span>
						: <?php echo esc_html( $last_log['message'] ); ?>
						(v<?php echo esc_html( $last_log['version'] ); ?>)
					</p>
				</div>
			</div>
			<?php endif; ?>

			<!-- History Modal -->
			<div id="sk-history-modal" class="sk-modal" style="display:none;">
				<div class="sk-modal-content">
					<span class="close" onclick="document.getElementById('sk-history-modal').style.display='none'">&times;</span>
					<h2><?php _e( 'Historial de Ejecuciones', 'skincare' ); ?></h2>
					<table class="widefat fixed striped">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Acción</th>
								<th>Paso</th>
								<th>Estado</th>
								<th>Mensaje</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $logs as $log ) : ?>
								<tr>
									<td><?php echo date( 'Y-m-d H:i', $log['timestamp'] ); ?></td>
									<td><?php echo esc_html( $log['action'] ?? '-' ); ?></td>
									<td><?php echo esc_html( $log['step'] ?? '-' ); ?></td>
									<td><?php echo esc_html( $log['status'] ); ?></td>
									<td><?php echo esc_html( $log['message'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<style>
				/* Inline styles for modal simplicity */
				.sk-modal { position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
				.sk-modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 800px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 8px; }
				.close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
				.close:hover { color: black; }
				.sk-log-status.error { color: #d63638; font-weight: bold; }
				.sk-log-status.success { color: #00a32a; font-weight: bold; }
			</style>
		</div>
		<?php
	}
}
