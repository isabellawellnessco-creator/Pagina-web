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
		add_action( 'admin_notices', [ __CLASS__, 'render_continue_notice' ] );
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
		if ( function_exists( 'get_stylesheet_directory_uri' ) ) {
			wp_enqueue_style( 'sk-admin-components', get_stylesheet_directory_uri() . '/assets/css/components.css', [], '1.0.0' );
		}
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
			update_option( Seeder::OPTION_LAST_RUN, current_time( 'timestamp' ) );
			if ( $step === 'pages' ) {
				update_option( Seeder::OPTION_LAST_ERROR, '' );
				update_option( Seeder::OPTION_COMPLETED, false );
			}
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
					update_option( Seeder::OPTION_VERSION, Seeder::SEED_VERSION );
					update_option( Seeder::OPTION_COMPLETED, true );
					update_option( Seeder::OPTION_LAST_ERROR, '' );
					update_option( Seeder::OPTION_LAST_RUN, current_time( 'timestamp' ) );
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
			update_option( Seeder::OPTION_LAST_ERROR, $e->getMessage() );
			update_option( Seeder::OPTION_COMPLETED, false );
			Seeder::log_event( $mode, $step, 'error', $e->getMessage() );
			wp_send_json_error( [ 'message' => $e->getMessage() ] );
		}
	}

	public static function render_continue_notice() {
		if ( ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && strpos( $screen->id, 'sk-onboarding' ) !== false ) {
			return;
		}

		$legacy_seed_version = (int) get_option( Seeder::OPTION_NAME, 0 );
		$seed_completed = (bool) get_option( Seeder::OPTION_COMPLETED, $legacy_seed_version ? true : false );
		$seed_version = (int) get_option( Seeder::OPTION_VERSION, $legacy_seed_version );

		if ( $seed_completed && $seed_version >= Seeder::SEED_VERSION ) {
			return;
		}

		echo '<div class="notice notice-warning is-dismissible"><p>' .
			esc_html__( 'La configuración inicial de Skin Cupid está incompleta.', 'skincare' ) .
			' <a class="button button-primary" href="' . esc_url( admin_url( 'admin.php?page=sk-onboarding' ) ) . '">' .
			esc_html__( 'Continuar configuración', 'skincare' ) .
			'</a></p></div>';
	}

	public static function render_page() {
		$mode = isset( $_GET['mode'] ) && $_GET['mode'] === 'repair' ? 'repair' : 'install';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$dependencies = [
			[
				'label' => __( 'WooCommerce', 'skincare' ),
				'active' => is_plugin_active( 'woocommerce/woocommerce.php' ),
				'required' => true,
				'help' => __( 'Necesario para tienda y checkout.', 'skincare' ),
			],
			[
				'label' => __( 'Elementor', 'skincare' ),
				'active' => is_plugin_active( 'elementor/elementor.php' ),
				'required' => true,
				'help' => __( 'Necesario para editar plantillas y secciones visuales.', 'skincare' ),
			],
			[
				'label' => __( 'Elementor Pro', 'skincare' ),
				'active' => is_plugin_active( 'elementor-pro/elementor-pro.php' ),
				'required' => false,
				'help' => __( 'Opcional. Habilita Theme Builder avanzado y widgets WooCommerce extra.', 'skincare' ),
				'action' => 'https://elementor.com/pro/',
			],
		];
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

				<div class="sk-onboarding-content">
					<div class="sk-msg sk-msg--info">
						<div>
							<strong><?php _e( 'Estado de dependencias', 'skincare' ); ?></strong>
							<p><?php _e( 'Verifica que WooCommerce y Elementor estén activos. Elementor Pro es opcional.', 'skincare' ); ?></p>
							<ul>
								<?php foreach ( $dependencies as $dependency ) : ?>
									<li>
										<strong><?php echo esc_html( $dependency['label'] ); ?>:</strong>
										<?php echo $dependency['active'] ? esc_html__( 'OK', 'skincare' ) : esc_html__( 'Faltante', 'skincare' ); ?>
										<?php if ( ! $dependency['required'] ) : ?>
											(<?php echo esc_html__( 'Opcional', 'skincare' ); ?>)
										<?php endif; ?>
										- <?php echo esc_html( $dependency['help'] ); ?>
										<?php if ( ! $dependency['active'] && ! empty( $dependency['action'] ) ) : ?>
											<a href="<?php echo esc_url( $dependency['action'] ); ?>" target="_blank" rel="noopener noreferrer">
												<?php _e( 'Instalar Pro', 'skincare' ); ?>
											</a>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
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
