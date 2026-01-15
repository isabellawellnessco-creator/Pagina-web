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

		wp_localize_script( 'sk-admin-onboarding', 'sk_onboarding', [
			'nonce' => wp_create_nonce( 'sk_onboarding_nonce' ),
			'steps' => [
				'pages' => __( 'Creando páginas...', 'skincare' ),
				'categories' => __( 'Configurando categorías...', 'skincare' ),
				'products' => __( 'Importando productos demo...', 'skincare' ),
				'theme_parts' => __( 'Construyendo Theme Builder...', 'skincare' ),
				'menus' => __( 'Asignando menús...', 'skincare' ),
				'finalize' => __( 'Finalizando...', 'skincare' ),
			]
		] );
	}

	public static function redirect_on_activation() {
		if ( get_transient( 'sk_site_kit_activated' ) ) {
			delete_transient( 'sk_site_kit_activated' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_safe_redirect( admin_url( 'admin.php?page=sk-onboarding' ) );
				exit;
			}
		}
	}

	public static function handle_step() {
		check_ajax_referer( 'sk_onboarding_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		$step = isset( $_POST['step'] ) ? sanitize_text_field( $_POST['step'] ) : '';

		try {
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
					// Identity
					update_option( 'blogname', 'Skin Cupid' );
					update_option( 'blogdescription', 'Skincare coreana y belleza' );

					// Mark seeded
					update_option( Seeder::OPTION_NAME, Seeder::SEED_VERSION );
					flush_rewrite_rules();
					break;
				default:
					wp_send_json_error( [ 'message' => 'Invalid step' ] );
			}
			wp_send_json_success();
		} catch ( \Exception $e ) {
			wp_send_json_error( [ 'message' => $e->getMessage() ] );
		}
	}

	public static function render_page() {
		?>
		<div class="sk-onboarding-wrapper">
			<div class="sk-onboarding-card">
				<div class="sk-onboarding-header">
					<img src="<?php echo esc_url( SKINCARE_KIT_URL . 'assets/images/logo.svg' ); ?>" alt="Skin Cupid" class="sk-logo" onerror="this.style.display='none'">
					<h1><?php _e( 'Bienvenido a Skin Cupid', 'skincare' ); ?></h1>
					<p><?php _e( 'Vamos a configurar tu tienda en unos segundos.', 'skincare' ); ?></p>
				</div>

				<div class="sk-onboarding-content" id="sk-wizard-intro">
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
					<h2><?php _e( '¡Todo listo!', 'skincare' ); ?></h2>
					<p><?php _e( 'Tu sitio ha sido configurado correctamente.', 'skincare' ); ?></p>
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
