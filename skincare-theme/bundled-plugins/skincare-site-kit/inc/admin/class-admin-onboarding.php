<?php
namespace Skincare\SiteKit\Admin;

use Skincare\SiteKit\Modules\Seeder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Onboarding {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );

		// AJAX Actions
		add_action( 'wp_ajax_sk_onboarding_run_step', [ __CLASS__, 'handle_step' ] );

		// Redirect on activation (using a transient to check)
		add_action( 'admin_init', [ __CLASS__, 'redirect_on_activation' ] );
	}

	public static function register_page() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Asistente de Configuración', 'skincare' ),
			__( 'Configuración', 'skincare' ),
			'manage_options',
			'sk-onboarding',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function enqueue_assets( $hook ) {
		if ( strpos( $hook, 'sk-onboarding' ) === false ) {
			return;
		}

		wp_enqueue_style( 'sk-admin-onboarding', SKINCARE_KIT_URL . 'assets/css/admin-onboarding.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-admin-onboarding', SKINCARE_KIT_URL . 'assets/js/admin-onboarding.js', ['jquery'], '1.0.0', true );

		$mode = isset( $_GET['mode'] ) && $_GET['mode'] === 'repair' ? 'repair' : 'install';

		wp_localize_script( 'sk-admin-onboarding', 'sk_onboarding', [
			'nonce' => wp_create_nonce( 'sk_onboarding_nonce' ),
			'mode'  => $mode,
			'steps' => [
				'pages' => __( 'Verificando páginas...', 'skincare' ),
				'categories' => __( 'Configurando categorías...', 'skincare' ),
				'products' => __( 'Revisando productos demo...', 'skincare' ),
				'theme_parts' => __( 'Construyendo Theme Builder...', 'skincare' ),
				'menus' => __( 'Asignando menús...', 'skincare' ),
				'finalize' => __( 'Finalizando...', 'skincare' ),
			]
		] );
	}

	public static function redirect_on_activation() {
		if ( get_transient( 'sk_site_kit_activated' ) ) {
			delete_transient( 'sk_site_kit_activated' );
			if ( ! isset( $_GET['activate-multi'] ) && current_user_can( 'manage_options' ) ) {
				// Prevent redirect loop in network admin or if headers sent
				if ( ! is_network_admin() ) {
					wp_safe_redirect( admin_url( 'admin.php?page=sk-onboarding' ) );
					exit;
				}
			}
		}
	}

	public static function handle_step() {
		check_ajax_referer( 'sk_onboarding_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		$step = isset( $_POST['step'] ) ? sanitize_text_field( $_POST['step'] ) : '';
		$mode = isset( $_POST['mode'] ) ? sanitize_text_field( $_POST['mode'] ) : 'install';

		try {
			// Log start of step
			Seeder::log_event( $mode, $step, 'pending', 'Iniciando paso...' );

			switch ( $step ) {
				case 'pages':
					Seeder::create_pages();
					break;
				case 'categories':
					Seeder::create_categories();
					break;
				case 'products':
					Seeder::create_products();
					break;
				case 'theme_parts':
					Seeder::create_theme_parts();
					break;
				case 'menus':
					Seeder::create_menus();
					break;
				case 'finalize':
					// Set homepage
					$home = get_page_by_path( 'home' );
					if ( $home ) {
						update_option( 'show_on_front', 'page' );
						update_option( 'page_on_front', $home->ID );
					}

					// Identity: Only update if not set or if we want to force it (maybe only on install mode?)
					// Requirement: B2 - Do not overwrite if exists
					if ( $mode === 'install' ) {
						if ( ! get_option( 'blogname' ) || get_option( 'blogname' ) === 'My Blog' || get_option( 'blogname' ) === 'WordPress' ) {
							update_option( 'blogname', 'Skin Cupid' );
							update_option( 'blogdescription', 'Skincare coreana y belleza' );
						}
					}

					// Mark seeded
					update_option( Seeder::OPTION_NAME, Seeder::SEED_VERSION );
					// Recalculate smart check immediately
					delete_transient( 'sk_smart_check_results' );
					Seeder::run_smart_check();

					flush_rewrite_rules();
					break;
				default:
					throw new \Exception( 'Invalid step' );
			}

			Seeder::log_event( $mode, $step, 'success', 'Paso completado correctamente.' );
			wp_send_json_success();

		} catch ( \Exception $e ) {
			Seeder::log_event( $mode, $step, 'error', $e->getMessage() );
			wp_send_json_error( [ 'message' => $e->getMessage() ] );
		}
	}

	public static function render_page() {
		$mode = isset( $_GET['mode'] ) && $_GET['mode'] === 'repair' ? 'repair' : 'install';
		?>
		<div class="sk-onboarding-wrapper">
			<div class="sk-onboarding-card">
				<div class="sk-onboarding-header">
					<img src="<?php echo esc_url( SKINCARE_KIT_URL . 'assets/images/logo.svg' ); ?>" alt="Skin Cupid" class="sk-logo" onerror="this.style.display='none'">
					<?php if ( $mode === 'repair' ) : ?>
						<h1><?php _e( 'Reparación del Sitio', 'skincare' ); ?></h1>
						<p><?php _e( 'Verificando y restaurando componentes faltantes.', 'skincare' ); ?></p>
					<?php else : ?>
						<h1><?php _e( 'Bienvenido a Skin Cupid', 'skincare' ); ?></h1>
						<p><?php _e( 'Vamos a configurar tu tienda en unos segundos.', 'skincare' ); ?></p>
					<?php endif; ?>
				</div>

				<div class="sk-onboarding-content" id="sk-wizard-intro">
					<?php if ( $mode === 'repair' ) : ?>
						<div class="sk-feature-list">
							<div class="sk-feature">
								<span class="dashicons dashicons-hammer"></span>
								<div>
									<strong><?php _e( 'Modo Reparación', 'skincare' ); ?></strong>
									<p><?php _e( 'Este proceso es seguro. Solo creará los elementos que falten.', 'skincare' ); ?></p>
								</div>
							</div>
						</div>
						<button class="button button-primary button-hero" id="sk-start-import">
							<?php _e( 'Iniciar Reparación', 'skincare' ); ?>
						</button>
					<?php else : ?>
						<div class="sk-feature-list">
							<div class="sk-feature">
								<span class="dashicons dashicons-layout"></span>
								<div>
									<strong><?php _e( 'Plantillas Editables', 'skincare' ); ?></strong>
									<p><?php _e( 'Importaremos Header, Footer y Ficha de Producto.', 'skincare' ); ?></p>
								</div>
							</div>
							<div class="sk-feature">
								<span class="dashicons dashicons-products"></span>
								<div>
									<strong><?php _e( 'Contenido Demo', 'skincare' ); ?></strong>
									<p><?php _e( 'Productos, categorías y menús listos para usar.', 'skincare' ); ?></p>
								</div>
							</div>
						</div>
						<button class="button button-primary button-hero" id="sk-start-import">
							<?php _e( 'Instalar Todo Ahora', 'skincare' ); ?>
						</button>
					<?php endif; ?>
				</div>

				<div class="sk-onboarding-content" id="sk-wizard-progress" style="display:none;">
					<div class="sk-progress-bar-wrapper">
						<div class="sk-progress-bar">
							<div class="sk-progress-fill" style="width: 0%"></div>
						</div>
						<p class="sk-progress-status"><?php _e( 'Iniciando...', 'skincare' ); ?></p>
					</div>
					<ul class="sk-log-list">
						<!-- Steps will appear here -->
					</ul>
				</div>

				<div class="sk-onboarding-content" id="sk-wizard-success" style="display:none;">
					<div class="sk-success-icon">
						<span class="dashicons dashicons-yes"></span>
					</div>
					<h2><?php _e( '¡Proceso finalizado!', 'skincare' ); ?></h2>
					<p><?php _e( 'El sistema ha sido verificado y actualizado.', 'skincare' ); ?></p>
					<div class="sk-actions">
						<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary button-hero" target="_blank">
							<?php _e( 'Ver mi Sitio', 'skincare' ); ?>
						</a>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=skincare-site-kit' ) ); ?>" class="button button-secondary">
							<?php _e( 'Ir al Dashboard', 'skincare' ); ?>
						</a>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
