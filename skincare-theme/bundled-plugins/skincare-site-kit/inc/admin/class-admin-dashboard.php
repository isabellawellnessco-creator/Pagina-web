<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Dashboard {

	public static function init() {
		// Replaces the old settings page callback
		// We hook into the existing menu page defined in Settings.php
		// or we can refactor Settings.php to call this.
		// For now, let's assume we modify Settings.php to call this render method.
	}

	public static function render() {
		wp_enqueue_style( 'sk-site-kit-admin-dashboard', SKINCARE_KIT_URL . 'assets/css/admin-dashboard.css', [], '1.0.0' );

		// Data gathering
		$theme_builder_active = ! empty( get_option( 'sk_theme_builder_settings', [] ) );
		$rewards_active = class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' );
		$tracking_active = true; // Always active
		$seeded_version = get_option( 'sk_content_seeded_version', 0 );
		$permalinks = get_option( 'permalink_structure' );

		$health_checks = [
			[
				'title' => __( 'Estructura de Enlaces', 'skincare' ),
				'desc' => $permalinks ? __( 'Los permalinks están configurados correctamente.', 'skincare' ) : __( 'Se recomienda activar "Nombre de la entrada" en Ajustes > Enlaces permanentes.', 'skincare' ),
				'status' => $permalinks ? 'ok' : 'issue',
				'action' => $permalinks ? '' : admin_url( 'options-permalink.php' ),
			],
			[
				'title' => __( 'Páginas Críticas', 'skincare' ),
				'desc' => get_page_by_path( 'rewards' ) ? __( 'Las páginas del sistema existen.', 'skincare' ) : __( 'Faltan páginas clave. Ejecuta el importador.', 'skincare' ),
				'status' => get_page_by_path( 'rewards' ) ? 'ok' : 'issue',
				'action' => get_page_by_path( 'rewards' ) ? '' : admin_url( 'admin.php?page=sk-onboarding' ),
			],
			[
				'title' => __( 'Motor de Tracking', 'skincare' ),
				'desc' => 'Proveedor activo: ' . \Skincare\SiteKit\Modules\Tracking_Manager::get_provider()->get_name(),
				'status' => 'ok',
				'action' => '',
			]
		];

		?>
		<div class="wrap">
			<div class="sk-control-center-header">
				<div>
					<h1><?php _e( 'Skin Cupid Control Center', 'skincare' ); ?></h1>
					<p><?php _e( 'Versión del sistema: 2.1.0', 'skincare' ); ?></p>
				</div>
				<div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding' ) ); ?>" class="button button-secondary"><?php _e( 'Asistente de Configuración', 'skincare' ); ?></a>
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
									<a href="<?php echo esc_url( $check['action'] ); ?>" class="button button-small"><?php _e( 'Corregir', 'skincare' ); ?></a>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	}
}
