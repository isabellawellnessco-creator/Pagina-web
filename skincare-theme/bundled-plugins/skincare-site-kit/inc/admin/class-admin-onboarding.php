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
		add_action( 'admin_post_sk_onboarding_save_customization', [ __CLASS__, 'handle_customization_save' ] );

		// Redirect on activation (using a transient to check)
		add_action( 'admin_init', [ __CLASS__, 'redirect_on_activation' ] );
		add_action( 'admin_notices', [ __CLASS__, 'render_continue_notice' ] );
	}

	public static function register_page() {
		add_submenu_page(
			null,
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
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			'sk-site-kit-admin-settings',
			SKINCARE_KIT_URL . 'assets/js/admin-settings.js',
			[ 'jquery', 'wp-color-picker' ],
			'1.0.0',
			true
		);
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
				'elementor_widgets' => __( 'Verificando widgets de Elementor...', 'skincare' ),
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
				case 'elementor_widgets':
					self::ensure_elementor_widgets();
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

	public static function handle_customization_save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'skincare' ) );
		}

		check_admin_referer( 'sk_onboarding_save_customization', 'sk_onboarding_customization_nonce' );

		$theme_settings = isset( $_POST['sk_theme_builder_settings'] ) ? (array) wp_unslash( $_POST['sk_theme_builder_settings'] ) : [];
		$allowed_keys = [ 'global_header', 'global_footer', 'single_product', 'shop_archive' ];
		$sanitized_theme = [];
		foreach ( $allowed_keys as $key ) {
			$sanitized_theme[ $key ] = isset( $theme_settings[ $key ] ) ? absint( $theme_settings[ $key ] ) : 0;
		}
		update_option( 'sk_theme_builder_settings', $sanitized_theme );

		$branding_settings = isset( $_POST['sk_theme_branding_settings'] ) ? (array) wp_unslash( $_POST['sk_theme_branding_settings'] ) : [];
		$sanitized_branding = Settings::sanitize_branding_settings( $branding_settings );
		update_option( 'sk_theme_branding_settings', $sanitized_branding );

		wp_safe_redirect( admin_url( 'admin.php?page=sk-onboarding&customization=saved' ) );
		exit;
	}

	public static function render_continue_notice() {
		if ( ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
			return;
		}

		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'sk-onboarding', 'sk-theme-setup' ], true ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && ( strpos( $screen->id, 'sk-onboarding' ) !== false || strpos( $screen->id, 'sk-theme-setup' ) !== false ) ) {
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
		$widget_status = self::get_elementor_widget_status();
		$customization_saved = isset( $_GET['customization'] ) && $_GET['customization'] === 'saved';
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
					<?php if ( $customization_saved ) : ?>
						<div class="sk-msg sk-msg--success">
							<div>
								<strong><?php _e( 'Personalización guardada.', 'skincare' ); ?></strong>
								<p><?php _e( 'Tus cambios de branding y Theme Builder fueron actualizados.', 'skincare' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
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

				<div class="sk-onboarding-content">
					<div class="sk-section-header">
						<div>
							<strong><?php _e( 'Widgets de Elementor', 'skincare' ); ?></strong>
							<p><?php _e( 'Comprueba que los widgets de Skin Cupid están disponibles en Elementor.', 'skincare' ); ?></p>
						</div>
						<span class="sk-chip <?php echo empty( $widget_status['missing'] ) && $widget_status['loaded'] ? 'sk-chip--ok' : 'sk-chip--warn'; ?>">
							<?php echo empty( $widget_status['missing'] ) && $widget_status['loaded'] ? esc_html__( 'Listo', 'skincare' ) : esc_html__( 'Requiere revisión', 'skincare' ); ?>
						</span>
					</div>
					<?php if ( ! $widget_status['loaded'] ) : ?>
						<div class="sk-msg sk-msg--error">
							<div>
								<strong><?php _e( 'Elementor no está activo.', 'skincare' ); ?></strong>
								<p><?php _e( 'Activa Elementor para registrar los widgets del kit.', 'skincare' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
					<ul class="sk-status-list">
						<?php foreach ( $widget_status['widgets'] as $widget ) : ?>
							<li class="<?php echo $widget['ok'] ? 'is-ok' : 'is-missing'; ?>">
								<span><?php echo esc_html( $widget['label'] ); ?></span>
								<span class="sk-pill <?php echo $widget['ok'] ? 'sk-pill--ok' : 'sk-pill--warn'; ?>">
									<?php echo $widget['ok'] ? esc_html__( 'OK', 'skincare' ) : esc_html__( 'Faltante', 'skincare' ); ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
					<div class="sk-inline-actions">
						<button type="button" class="button button-secondary" id="sk-recheck-widgets">
							<?php _e( 'Reintentar widgets', 'skincare' ); ?>
						</button>
						<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ) ); ?>">
							<?php _e( 'Ejecutar reparación completa', 'skincare' ); ?>
						</a>
					</div>
					<p class="description" id="sk-widget-status-message"></p>
				</div>

				<div class="sk-onboarding-content">
					<div class="sk-section-header">
						<div>
							<strong><?php _e( 'Personaliza tu tienda', 'skincare' ); ?></strong>
							<p><?php _e( 'Selecciona templates y colores para que el sitio se vea profesional desde el inicio.', 'skincare' ); ?></p>
						</div>
						<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=sk-branding-settings' ) ); ?>">
							<?php _e( 'Configuración avanzada', 'skincare' ); ?>
						</a>
					</div>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="sk-customization-form">
						<input type="hidden" name="action" value="sk_onboarding_save_customization">
						<?php wp_nonce_field( 'sk_onboarding_save_customization', 'sk_onboarding_customization_nonce' ); ?>
						<div class="sk-form-grid">
							<div class="sk-form-field">
								<label><?php _e( 'Header global', 'skincare' ); ?></label>
								<?php Settings::render_select_field( [ 'id' => 'global_header' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Footer global', 'skincare' ); ?></label>
								<?php Settings::render_select_field( [ 'id' => 'global_footer' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Plantilla producto', 'skincare' ); ?></label>
								<?php Settings::render_select_field( [ 'id' => 'single_product' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Plantilla catálogo', 'skincare' ); ?></label>
								<?php Settings::render_select_field( [ 'id' => 'shop_archive' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Logo principal', 'skincare' ); ?></label>
								<?php Settings::render_media_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'logo_id', 'button_label' => __( 'Seleccionar logo', 'skincare' ) ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Color principal', 'skincare' ); ?></label>
								<?php Settings::render_color_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'accent_color', 'default' => '#E5757E' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Color principal hover', 'skincare' ); ?></label>
								<?php Settings::render_color_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'accent_hover_color', 'default' => '#D9656E' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Color de fondo', 'skincare' ); ?></label>
								<?php Settings::render_color_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'background_color', 'default' => '#FFFFFF' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Color texto principal', 'skincare' ); ?></label>
								<?php Settings::render_color_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'text_color', 'default' => '#0F3062' ] ); ?>
							</div>
							<div class="sk-form-field">
								<label><?php _e( 'Color texto secundario', 'skincare' ); ?></label>
								<?php Settings::render_color_field( [ 'option' => 'sk_theme_branding_settings', 'id' => 'text_light_color', 'default' => '#8798B0' ] ); ?>
							</div>
						</div>
						<div class="sk-form-actions">
							<button type="submit" class="button button-primary button-hero">
								<?php _e( 'Guardar personalización', 'skincare' ); ?>
							</button>
						</div>
					</form>
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

	private static function get_expected_widgets() {
		return [
			[ 'name' => 'sk_hero_slider', 'label' => __( 'Slider principal', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Hero_Slider' ],
			[ 'name' => 'sk_product_grid', 'label' => __( 'Grid de productos', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Product_Grid' ],
			[ 'name' => 'sk_wishlist_grid', 'label' => __( 'Wishlist', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Wishlist_Grid' ],
			[ 'name' => 'sk_rewards_dashboard', 'label' => __( 'Panel de recompensas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Rewards_Dashboard' ],
			[ 'name' => 'sk_ajax_search', 'label' => __( 'Búsqueda Ajax', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Ajax_Search' ],
			[ 'name' => 'sk_rewards_castle', 'label' => __( 'Castle de recompensas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Rewards_Castle' ],
			[ 'name' => 'sk_contact_section', 'label' => __( 'Sección contacto', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Contact_Section' ],
			[ 'name' => 'sk_faq_accordion', 'label' => __( 'FAQ accordion', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\FAQ_Accordion' ],
			[ 'name' => 'sk_shipping_table', 'label' => __( 'Tabla de envíos', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Shipping_Table' ],
			[ 'name' => 'sk_store_locator', 'label' => __( 'Localizador de tiendas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Store_Locator' ],
			[ 'name' => 'sk_account_dashboard', 'label' => __( 'Dashboard de cuenta', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Account_Dashboard' ],
			[ 'name' => 'sk_marquee', 'label' => __( 'Marquee', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Marquee' ],
			[ 'name' => 'sk_icon_box_grid', 'label' => __( 'Grid de íconos', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Icon_Box_Grid' ],
			[ 'name' => 'sk_concern_grid', 'label' => __( 'Grid de necesidades', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Concern_Grid' ],
			[ 'name' => 'sk_brand_slider', 'label' => __( 'Slider de marcas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Brand_Slider' ],
			[ 'name' => 'sk_instagram_feed', 'label' => __( 'Feed de Instagram', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Instagram_Feed' ],
			[ 'name' => 'sk_rewards_earn_redeem', 'label' => __( 'Recompensas ganar/canjear', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Rewards_Earn_Redeem' ],
			[ 'name' => 'sk_rewards_catalog', 'label' => __( 'Catálogo de recompensas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Rewards_Catalog' ],
			[ 'name' => 'sk_rewards_actions', 'label' => __( 'Acciones de recompensas', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Rewards_Actions' ],
			[ 'name' => 'sk_product_tabs', 'label' => __( 'Tabs de producto', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Product_Tabs' ],
			[ 'name' => 'sk_ajax_filter', 'label' => __( 'Filtro Ajax', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Ajax_Filter' ],
			[ 'name' => 'sk_product_gallery', 'label' => __( 'Galería de producto', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Product_Gallery' ],
			[ 'name' => 'sk_theme_part_title', 'label' => __( 'Theme: título', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Theme_Part_Title' ],
			[ 'name' => 'sk_theme_part_price', 'label' => __( 'Theme: precio', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Theme_Part_Price' ],
			[ 'name' => 'sk_theme_part_add_to_cart', 'label' => __( 'Theme: añadir al carrito', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Theme_Part_Add_To_Cart' ],
			[ 'name' => 'sk_switcher', 'label' => __( 'Switcher', 'skincare' ), 'class' => '\\Skincare\\SiteKit\\Widgets\\Sk_Switcher' ],
		];
	}

	private static function get_elementor_widget_status() {
		$loaded = did_action( 'elementor/loaded' ) && class_exists( '\\Elementor\\Plugin' );
		$widgets = [];
		$missing = [];

		$registered = [];
		if ( $loaded ) {
			$registered = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
		}

		foreach ( self::get_expected_widgets() as $widget ) {
			$is_registered = $loaded && isset( $registered[ $widget['name'] ] );
			$widgets[] = [
				'name' => $widget['name'],
				'label' => $widget['label'],
				'ok' => $is_registered,
			];
			if ( ! $is_registered ) {
				$missing[] = $widget['label'];
			}
		}

		return [
			'loaded' => $loaded,
			'widgets' => $widgets,
			'missing' => $missing,
		];
	}

	private static function ensure_elementor_widgets() {
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\\Elementor\\Plugin' ) ) {
			throw new \Exception( __( 'Elementor no está cargado. Actívalo e inténtalo de nuevo.', 'skincare' ) );
		}

		$manager = \Elementor\Plugin::instance()->widgets_manager;
		if ( ! $manager || ! method_exists( $manager, 'register' ) ) {
			throw new \Exception( __( 'No se pudo acceder al gestor de widgets de Elementor.', 'skincare' ) );
		}

		$registered = $manager->get_widget_types();

		foreach ( self::get_expected_widgets() as $widget ) {
			if ( isset( $registered[ $widget['name'] ] ) ) {
				continue;
			}

			$class = $widget['class'];
			if ( class_exists( $class ) ) {
				$instance = new $class();
				$manager->register( $instance );
			}
		}

		$registered = $manager->get_widget_types();
		$missing = [];
		foreach ( self::get_expected_widgets() as $widget ) {
			if ( ! isset( $registered[ $widget['name'] ] ) ) {
				$missing[] = $widget['label'];
			}
		}

		if ( ! empty( $missing ) ) {
			throw new \Exception( sprintf( __( 'Widgets faltantes: %s', 'skincare' ), implode( ', ', $missing ) ) );
		}
	}
}
