<?php
namespace Skincare\SiteKit\Admin;

use Skincare\SiteKit\Modules\Seeder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Dashboard {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
		add_action( 'admin_menu', [ __CLASS__, 'hide_submenus' ], 999 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
		add_action( 'admin_post_sk_advanced_suite_setup', [ __CLASS__, 'handle_advanced_setup' ] );
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
		$advanced_settings = get_option( 'sk_advanced_suite', [] );
		$advanced_modules = self::get_advanced_modules();
		$advanced_enabled = array_filter( array_map( function( $key ) use ( $advanced_settings ) {
			return ! empty( $advanced_settings[ $key ] );
		}, array_keys( $advanced_modules ) ) );
		$advanced_total = count( $advanced_modules );
		$advanced_done = count( $advanced_enabled );
		$advanced_progress = $advanced_total ? ( $advanced_done / $advanced_total ) * 100 : 0;
		$advanced_updated = isset( $_GET['sk_advanced_setup'] ) && $_GET['sk_advanced_setup'] === 'done';
		$area_groups = self::get_area_groups();

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

			<div class="sk-areas-section">
				<h2><?php _e( 'Áreas del plugin', 'skincare' ); ?></h2>
				<p><?php _e( 'Accede a cada módulo desde una sola vista para evitar navegar por múltiples páginas.', 'skincare' ); ?></p>
				<?php foreach ( $area_groups as $group ) : ?>
					<div class="sk-areas-group">
						<div class="sk-areas-group-header">
							<h3><?php echo esc_html( $group['title'] ); ?></h3>
							<?php if ( ! empty( $group['description'] ) ) : ?>
								<p><?php echo esc_html( $group['description'] ); ?></p>
							<?php endif; ?>
						</div>
						<div class="sk-areas-grid">
							<?php foreach ( $group['items'] as $item ) : ?>
								<?php if ( ! empty( $item['cap'] ) && ! current_user_can( $item['cap'] ) ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<div class="sk-area-card">
									<h4><?php echo esc_html( $item['label'] ); ?></h4>
									<p><?php echo esc_html( $item['description'] ); ?></p>
									<a class="button button-secondary" href="<?php echo esc_url( $item['link'] ); ?>">
										<?php echo esc_html( $item['cta'] ); ?>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
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

			<div class="sk-logs-section">
				<h3><?php _e( 'Suite Avanzada', 'skincare' ); ?></h3>
				<p><?php _e( 'Activa analítica avanzada, inventario, tracking y facturación con un flujo de 1 clic.', 'skincare' ); ?></p>
				<?php if ( $advanced_updated ) : ?>
					<div class="notice notice-success is-dismissible"><p><?php _e( 'Suite avanzada configurada.', 'skincare' ); ?></p></div>
				<?php endif; ?>
				<div class="sk-status-card sk-card">
					<div class="sk-status-header">
						<h4><?php _e( 'Progreso', 'skincare' ); ?></h4>
						<span class="sk-chip"><?php echo esc_html( sprintf( __( '%d/%d activos', 'skincare' ), $advanced_done, $advanced_total ) ); ?></span>
					</div>
					<div class="sk-progress-wrapper">
						<div class="sk-progress-bar">
							<div class="sk-progress-fill" style="width: <?php echo esc_attr( $advanced_progress ); ?>%"></div>
						</div>
					</div>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=sk_advanced_suite_setup' ) ); ?>">
						<?php wp_nonce_field( 'sk_advanced_suite_setup', 'sk_advanced_suite_nonce' ); ?>
						<button class="btn btn-primary" type="submit"><?php _e( 'Configurar en 1 clic', 'skincare' ); ?></button>
						<a class="btn btn-secondary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ), 'sk_repair_link' ) ); ?>">
							<?php _e( 'Importar contenido (1 clic)', 'skincare' ); ?>
						</a>
					</form>
				</div>

				<div class="sk-dashboard-columns" style="margin-top: 20px;">
					<div class="sk-dashboard-column">
						<div class="sk-health-card sk-card">
							<ul class="sk-health-list-new">
								<?php foreach ( $advanced_modules as $key => $module ) : ?>
									<?php $active = ! empty( $advanced_settings[ $key ] ); ?>
									<li class="sk-health-item-new">
										<span class="sk-indicator <?php echo $active ? 'ok' : 'issue'; ?>"></span>
										<span><?php echo esc_html( $module['label'] ); ?></span>
										<?php if ( ! empty( $module['link'] ) ) : ?>
											<a href="<?php echo esc_url( $module['link'] ); ?>" class="sk-fix-link"><?php _e( 'Configurar', 'skincare' ); ?></a>
										<?php endif; ?>
										<?php if ( ! empty( $module['description'] ) ) : ?>
											<span class="description"><?php echo esc_html( $module['description'] ); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php
	}

	public static function handle_advanced_setup() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No autorizado.', 'skincare' ) );
		}

		check_admin_referer( 'sk_advanced_suite_setup', 'sk_advanced_suite_nonce' );

		$defaults = [];
		foreach ( self::get_advanced_modules() as $key => $module ) {
			$defaults[ $key ] = true;
		}
		update_option( 'sk_advanced_suite', $defaults );

		wp_safe_redirect( add_query_arg( [ 'page' => 'skincare-site-kit', 'sk_advanced_setup' => 'done' ], admin_url( 'admin.php' ) ) );
		exit;
	}

	private static function get_advanced_modules() {
		return [
			'analytics' => [
				'label' => __( 'Analítica avanzada', 'skincare' ),
				'description' => __( 'KPIs de ventas, cohortes y rendimiento.', 'skincare' ),
				'link' => admin_url( 'admin.php?page=wc-admin&path=/analytics/overview' ),
			],
			'inventory' => [
				'label' => __( 'Inventario avanzado', 'skincare' ),
				'description' => __( 'Control de stock y ajustes rápidos.', 'skincare' ),
				'link' => admin_url( 'admin.php?page=sk-stock-manager' ),
			],
			'tracking' => [
				'label' => __( 'Tracking y estados', 'skincare' ),
				'description' => __( 'Configura los pasos que ve el cliente.', 'skincare' ),
				'link' => admin_url( 'admin.php?page=sk-tracking-settings' ),
			],
			'sunat' => [
				'label' => __( 'SUNAT / Comprobantes', 'skincare' ),
				'description' => __( 'Emite boletas y facturas desde Fulfillment.', 'skincare' ),
				'link' => admin_url( 'admin.php?page=sk-fulfillment-center#sk-fulfillment-invoices' ),
			],
			'crm' => [
				'label' => __( 'CRM y WhatsApp', 'skincare' ),
				'description' => __( 'Plantillas y comunicación con clientes.', 'skincare' ),
				'link' => admin_url( 'admin.php?page=sk-whatsapp-templates' ),
			],
		];
	}

	public static function hide_submenus() {
		$submenus = [
			'sk-branding-settings',
			'sk-theme-builder',
			'sk-localization',
			'sk-operations-dashboard',
			'sk-stock-manager',
			'sk-fulfillment-center',
			'sk-sla-monitor',
			'sk-batch-picking',
			'sk-packing-slips',
			'sk-shipping-labels',
			'sk-invoices',
			'sk-rewards-master',
			'sk-rewards-control',
			'sk-notifications-center',
			'sk-whatsapp-templates',
			'sk-coupons-automation',
			'sk-tracking-settings',
			'sk-migration-center',
			'sk-tools',
		];

		foreach ( $submenus as $submenu ) {
			remove_submenu_page( 'skincare-site-kit', $submenu );
		}
	}

	private static function get_area_groups() {
		return [
			[
				'title' => __( 'Marca y contenido', 'skincare' ),
				'description' => __( 'Identidad visual y estructura de la tienda.', 'skincare' ),
				'items' => [
					[
						'label' => __( 'Branding & Content', 'skincare' ),
						'description' => __( 'Logos, colores, tipografías y contenido editorial.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-branding-settings' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
					[
						'label' => __( 'Theme Builder', 'skincare' ),
						'description' => __( 'Asignación de plantillas globales y layout de producto.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-theme-builder' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
					[
						'label' => __( 'Localization', 'skincare' ),
						'description' => __( 'Moneda, idiomas y configuración regional.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-localization' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
				],
			],
			[
				'title' => __( 'Operaciones y logística', 'skincare' ),
				'description' => __( 'Pedidos, stock y fulfillment en un solo lugar.', 'skincare' ),
				'items' => [
					[
						'label' => __( 'Dashboard Ops', 'skincare' ),
						'description' => __( 'Panel operativo diario para estados de pedidos.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-operations-dashboard' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Ingreso Stock', 'skincare' ),
						'description' => __( 'Actualiza inventario rápidamente.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-stock-manager' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Fulfillment Center', 'skincare' ),
						'description' => __( 'Gestión de pedidos, etiquetas e incidencias.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-fulfillment-center' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'SLA Monitor', 'skincare' ),
						'description' => __( 'Detecta pedidos con retrasos críticos.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-sla-monitor' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Batch Picking', 'skincare' ),
						'description' => __( 'Agrupa pedidos para preparar en lote.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-batch-picking' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Packing Slips', 'skincare' ),
						'description' => __( 'Impresión de packing slips.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-packing-slips' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Shipping Labels', 'skincare' ),
						'description' => __( 'Genera etiquetas de envío.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-shipping-labels' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Invoices', 'skincare' ),
						'description' => __( 'Boletas y facturas desde el centro.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-invoices' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
				],
			],
			[
				'title' => __( 'Clientes y marketing', 'skincare' ),
				'description' => __( 'Mensajería, tracking y automatizaciones.', 'skincare' ),
				'items' => [
					[
						'label' => __( 'Emails & WhatsApp', 'skincare' ),
						'description' => __( 'Plantillas de notificación y pruebas.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-notifications-center' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
					[
						'label' => __( 'Plantillas WhatsApp', 'skincare' ),
						'description' => __( 'Mensajes predefinidos para equipo de ventas.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-whatsapp-templates' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Tracking', 'skincare' ),
						'description' => __( 'Configura estados visibles para el cliente.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-tracking-settings' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Cupones automáticos', 'skincare' ),
						'description' => __( 'Reglas para generar cupones automáticos.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-coupons-automation' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
				],
			],
			[
				'title' => __( 'Rewards y fidelización', 'skincare' ),
				'description' => __( 'Programas de puntos y control de recompensas.', 'skincare' ),
				'items' => [
					[
						'label' => __( 'Rewards Master', 'skincare' ),
						'description' => __( 'Reglas principales de puntos.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-rewards-master' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
					[
						'label' => __( 'Rewards Control', 'skincare' ),
						'description' => __( 'Monitorea puntos y ajustes por pedido.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-rewards-control' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_woocommerce',
					],
				],
			],
			[
				'title' => __( 'Mantenimiento', 'skincare' ),
				'description' => __( 'Migraciones y utilidades del sistema.', 'skincare' ),
				'items' => [
					[
						'label' => __( 'Migración fácil', 'skincare' ),
						'description' => __( 'Exporta e importa la configuración completa.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-migration-center' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
					[
						'label' => __( 'Herramientas', 'skincare' ),
						'description' => __( 'Acciones rápidas de mantenimiento.', 'skincare' ),
						'link' => admin_url( 'admin.php?page=sk-tools' ),
						'cta' => __( 'Abrir', 'skincare' ),
						'cap' => 'manage_options',
					],
				],
			],
		];
	}
}
