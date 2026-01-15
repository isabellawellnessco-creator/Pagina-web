<?php
namespace Skincare\SiteKit\Admin;

use Skincare\SiteKit\Modules\Seeder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Dashboard {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
	}

	public static function register_page() {
		add_menu_page(
			__( 'Skin Cupid', 'skincare' ),
			__( 'Skin Cupid', 'skincare' ),
			'manage_options',
			'skincare-site-kit',
			[ __CLASS__, 'render' ],
			'dashicons-store',
			2
		);
	}

	public static function enqueue_assets( $hook ) {
		if ( 'toplevel_page_skincare-site-kit' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'sk-site-kit-admin-dashboard', SKINCARE_KIT_URL . 'assets/css/admin-dashboard.css', [], '2.1.0' );

		// Enqueue Components CSS for utility classes if theme is active
		if ( function_exists( 'get_stylesheet_directory_uri' ) ) {
			wp_enqueue_style( 'sk-admin-components', get_stylesheet_directory_uri() . '/assets/css/components.css', [], '2.1.0' );
		}
	}

	public static function render() {
		// Run logic to determine status
		$setup_check = Seeder::run_smart_check();
		$is_setup_ok = $setup_check['status'] === 'ok';

		// Data for cards
		$theme_builder_active = ! empty( get_option( 'sk_theme_builder_settings', [] ) );
		$demo_products = wp_count_posts( 'product' )->publish > 0;
		$menus_assigned = has_nav_menu( 'primary' );
		$legacy_seed_version = (int) get_option( Seeder::OPTION_NAME, 0 );
		$seed_completed = (bool) get_option( Seeder::OPTION_COMPLETED, $legacy_seed_version ? true : false );
		$seed_version = (int) get_option( Seeder::OPTION_VERSION, $legacy_seed_version );
		$seed_last_run = get_option( Seeder::OPTION_LAST_RUN, 0 );
		$seed_last_error = get_option( Seeder::OPTION_LAST_ERROR, '' );

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$elementor_pro_active = is_plugin_active( 'elementor-pro/elementor-pro.php' );

		// Overall Status
		$system_status = 'pending';
		if ( $is_setup_ok && $theme_builder_active && $demo_products && $menus_assigned ) {
			$system_status = 'complete';
		}

		// Calculate progress bar
		$steps_total = 4;
		$steps_done = 0;
		if ( $is_setup_ok ) $steps_done++;
		if ( $theme_builder_active ) $steps_done++;
		if ( $demo_products ) $steps_done++;
		if ( $menus_assigned ) $steps_done++;
		$progress_pct = ( $steps_done / $steps_total ) * 100;

		?>
		<div class="wrap sk-dashboard-wrap">
			<div class="sk-header-hero">
				<div class="sk-header-content">
					<h1><?php _e( 'Skin Cupid Control Center', 'skincare' ); ?></h1>
					<p><?php _e( 'Gestiona el estado de tu tienda y configura las herramientas esenciales.', 'skincare' ); ?></p>
				</div>
				<div class="sk-header-actions">
					<?php if ( $system_status !== 'complete' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding' ) ); ?>" class="btn btn-primary">
							<?php _e( 'Completar Configuración', 'skincare' ); ?>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-secondary" target="_blank">
							<?php _e( 'Ver Tienda', 'skincare' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- System Status Overview -->
			<div class="sk-section-status">
				<div class="sk-status-card <?php echo $system_status === 'complete' ? 'sk-card--ok' : 'sk-card--warning'; ?>">
					<div class="sk-status-header">
						<h2><?php _e( 'Estado del Sistema', 'skincare' ); ?></h2>
						<span class="sk-chip"><?php echo $system_status === 'complete' ? __( 'Operativo', 'skincare' ) : __( 'Requiere Atención', 'skincare' ); ?></span>
					</div>

					<div class="sk-progress-wrapper">
						<div class="sk-progress-bar">
							<div class="sk-progress-fill" style="width: <?php echo esc_attr( $progress_pct ); ?>%"></div>
						</div>
						<p><?php printf( __( '%d de %d pasos completados', 'skincare' ), $steps_done, $steps_total ); ?></p>
					</div>

					<div class="sk-actions-grid">
						<!-- Onboarding Action -->
						<div class="sk-action-item">
							<div class="sk-action-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
							<div class="sk-action-text">
								<h3><?php _e( 'Asistente de Configuración', 'skincare' ); ?></h3>
								<p><?php _e( 'Repara o reinstala contenido demo, páginas y menús.', 'skincare' ); ?></p>
							</div>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>" class="btn btn-secondary btn-sm">
								<?php _e( 'Ejecutar Wizard', 'skincare' ); ?>
							</a>
						</div>

						<div class="sk-action-item">
							<div class="sk-action-icon"><span class="dashicons dashicons-update"></span></div>
							<div class="sk-action-text">
								<h3><?php _e( 'Re-ejecutar Seed', 'skincare' ); ?></h3>
								<p>
									<?php _e( 'Acción segura para volver a validar el contenido demo.', 'skincare' ); ?>
									<?php if ( $seed_last_run ) : ?>
										<br><?php printf( __( 'Última ejecución: %s', 'skincare' ), date_i18n( 'd/m/Y H:i', $seed_last_run ) ); ?>
									<?php endif; ?>
								</p>
								<?php if ( $seed_last_error ) : ?>
									<div class="sk-msg sk-msg--error"><?php echo esc_html( $seed_last_error ); ?></div>
								<?php endif; ?>
							</div>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>" class="btn btn-primary btn-sm">
								<?php _e( 'Re-ejecutar', 'skincare' ); ?>
							</a>
						</div>

						<!-- Demo Content Action -->
						<div class="sk-action-item">
							<div class="sk-action-icon"><span class="dashicons dashicons-products"></span></div>
							<div class="sk-action-text">
								<h3><?php _e( 'Contenido Demo', 'skincare' ); ?></h3>
								<p><?php echo $demo_products ? __( 'Productos instalados.', 'skincare' ) : __( 'Faltan productos.', 'skincare' ); ?></p>
							</div>
							<?php if ( ! $demo_products ) : ?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>" class="btn btn-primary btn-sm"><?php _e( 'Importar', 'skincare' ); ?></a>
							<?php endif; ?>
						</div>

						<!-- Theme Builder Action -->
						<div class="sk-action-item">
							<div class="sk-action-icon"><span class="dashicons dashicons-layout"></span></div>
							<div class="sk-action-text">
								<h3><?php _e( 'Theme Builder', 'skincare' ); ?></h3>
								<p><?php echo $theme_builder_active ? __( 'Plantillas activas.', 'skincare' ) : __( 'Sin configurar.', 'skincare' ); ?></p>
							</div>
							<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sk_template' ) ); ?>" class="btn btn-secondary btn-sm"><?php _e( 'Gestionar', 'skincare' ); ?></a>
						</div>
					</div>
				</div>
			</div>

			<!-- Quick Actions & Health -->
			<div class="sk-dashboard-columns">

				<!-- Column 1: Shortcuts -->
				<div class="sk-dashboard-column">
					<h3><?php _e( 'Accesos Directos', 'skincare' ); ?></h3>
					<div class="sk-shortcuts-grid">
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=shop_order' ) ); ?>" class="sk-shortcut-card">
							<span class="dashicons dashicons-cart"></span>
							<span><?php _e( 'Pedidos', 'skincare' ); ?></span>
						</a>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=product' ) ); ?>" class="sk-shortcut-card">
							<span class="dashicons dashicons-tag"></span>
							<span><?php _e( 'Productos', 'skincare' ); ?></span>
						</a>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-rewards-control' ) ); ?>" class="sk-shortcut-card">
							<span class="dashicons dashicons-awards"></span>
							<span><?php _e( 'Puntos', 'skincare' ); ?></span>
						</a>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-operations-dashboard' ) ); ?>" class="sk-shortcut-card">
							<span class="dashicons dashicons-chart-bar"></span>
							<span><?php _e( 'Operaciones', 'skincare' ); ?></span>
						</a>
					</div>
				</div>

				<!-- Column 2: Health Checks (Legacy logic kept but styled) -->
				<div class="sk-dashboard-column">
					<h3><?php _e( 'Salud del Sistema', 'skincare' ); ?></h3>
					<div class="sk-health-card sk-card">
						<ul class="sk-health-list-new">
							<?php
							// Reuse logic from previous dashboard
							$checks = [
								'smtp' => [
									'label' => __( 'Servidor de Correo (SMTP)', 'skincare' ),
									'ok' => is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) || is_plugin_active( 'post-smtp/postman-smtp.php' )
								],
								'whatsapp' => [
									'label' => __( 'Conexión WhatsApp', 'skincare' ),
									'ok' => ! empty( get_option( 'sk_notification_settings', [] )['whatsapp_access_token'] )
								],
								'seeder' => [
									'label' => __( 'Integridad de Datos', 'skincare' ),
									'ok' => $is_setup_ok && $seed_completed && $seed_version >= Seeder::SEED_VERSION
								],
								'elementor_pro' => [
									'label' => __( 'Elementor Pro (opcional)', 'skincare' ),
									'ok' => $elementor_pro_active,
									'help' => __( 'Habilita Theme Builder avanzado y widgets WooCommerce extra.', 'skincare' ),
									'action' => 'https://elementor.com/pro/',
								]
							];

							foreach ( $checks as $key => $check ) : ?>
								<li class="sk-health-item-new">
									<span class="sk-indicator <?php echo $check['ok'] ? 'ok' : 'issue'; ?>"></span>
									<span><?php echo esc_html( $check['label'] ); ?></span>
									<?php if ( ! $check['ok'] ) : ?>
										<?php if ( ! empty( $check['action'] ) ) : ?>
											<a href="<?php echo esc_url( $check['action'] ); ?>" class="sk-fix-link" target="_blank" rel="noopener noreferrer"><?php _e( 'Ver', 'skincare' ); ?></a>
										<?php else : ?>
											<a href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>" class="sk-fix-link"><?php _e( 'Corregir', 'skincare' ); ?></a>
										<?php endif; ?>
									<?php endif; ?>
									<?php if ( ! empty( $check['help'] ) ) : ?>
										<span class="description"><?php echo esc_html( $check['help'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>

			<!-- Recent Logs -->
			<div class="sk-logs-section">
				<h3><?php _e( 'Actividad Reciente', 'skincare' ); ?></h3>
				<?php
				$logs = get_option( Seeder::LOG_OPTION, [] );
				if ( ! empty( $logs ) ) : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th><?php _e( 'Fecha', 'skincare' ); ?></th>
								<th><?php _e( 'Acción', 'skincare' ); ?></th>
								<th><?php _e( 'Estado', 'skincare' ); ?></th>
								<th><?php _e( 'Mensaje', 'skincare' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( array_slice( $logs, 0, 5 ) as $log ) : ?>
								<tr>
									<td><?php echo date( 'd/m H:i', $log['timestamp'] ); ?></td>
									<td><?php echo esc_html( $log['action'] ); ?></td>
									<td>
										<span class="sk-chip <?php echo $log['status'] === 'success' ? 'sk-msg--success' : ( $log['status'] === 'error' ? 'sk-msg--error' : '' ); ?>">
											<?php echo esc_html( $log['status'] ); ?>
										</span>
									</td>
									<td><?php echo esc_html( $log['message'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<p class="sk-msg sk-msg--info"><?php _e( 'No hay registros recientes.', 'skincare' ); ?></p>
				<?php endif; ?>
			</div>

		</div>
		<?php
	}
}
