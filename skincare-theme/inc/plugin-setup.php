<?php
/**
 * Plugin setup and dependency handling.
 *
 * @package SkincareTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define required plugins.
 */
function skincare_required_plugins() {
	return [
		[
			'name'       => 'WooCommerce',
			'slug'       => 'woocommerce',
			'file'       => 'woocommerce/woocommerce.php',
			'source'     => 'wporg',
			'required'   => true,
		],
		[
			'name'       => 'Elementor',
			'slug'       => 'elementor',
			'file'       => 'elementor/elementor.php',
			'source'     => 'wporg',
			'required'   => true,
		],
		[
			'name'         => 'Elementor Pro',
			'slug'         => 'elementor-pro',
			'file'         => 'elementor-pro/elementor-pro.php',
			'source'       => 'external',
			'external_url' => 'https://elementor.com/pro/',
			'required'     => false,
		],
		[
			'name'       => 'Skincare Site Kit',
			'slug'       => 'skincare-site-kit',
			'file'       => 'skincare-site-kit/skincare-site-kit.php',
			'source'     => 'bundled',
			'required'   => true,
		],
	];
}

/**
 * Check if a plugin is installed.
 */
function skincare_is_plugin_installed( $plugin_file ) {
	return file_exists( WP_PLUGIN_DIR . '/' . $plugin_file );
}

/**
 * Handle Theme Activation Redirect.
 */
function skincare_activation_redirect() {
	set_transient( 'sk_welcome_redirect', true, 60 );
}
add_action( 'after_switch_theme', 'skincare_activation_redirect' );

/**
 * Process Redirect on Admin Init.
 */
function skincare_welcome_redirect() {
	if ( ! get_transient( 'sk_welcome_redirect' ) ) {
		return;
	}
	delete_transient( 'sk_welcome_redirect' );

	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$site_kit_active = is_plugin_active( 'skincare-site-kit/skincare-site-kit.php' );

	// If site kit is active, go straight to its onboarding wizard.
	if ( $site_kit_active ) {
		wp_safe_redirect( admin_url( 'admin.php?page=sk-onboarding' ) );
		exit;
	}

	// Otherwise, go to setup page
	wp_safe_redirect( admin_url( 'admin.php?page=sk-theme-setup' ) );
	exit;
}
add_action( 'admin_init', 'skincare_welcome_redirect' );

/**
 * Check if all required plugins are active.
 */
function skincare_are_all_required_active() {
	$plugins = skincare_required_plugins();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	foreach ( $plugins as $plugin ) {
		if ( $plugin['required'] && ! is_plugin_active( $plugin['file'] ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Register Setup Page.
 */
function skincare_register_setup_page() {
	add_submenu_page(
		null, // Hidden page
		__( 'Skin Cupid Setup', 'skincare' ),
		__( 'Setup', 'skincare' ),
		'manage_options',
		'sk-theme-setup',
		'skincare_render_setup_page'
	);
}
add_action( 'admin_menu', 'skincare_register_setup_page' );

/**
 * Render Setup Page.
 */
function skincare_render_setup_page() {
	$plugins = skincare_required_plugins();
	$required = array_filter( $plugins, function($p) { return $p['required']; } );
	?>
	<div class="wrap sk-theme-setup-wrap">
		<style>
			.sk-theme-setup-wrap { max-width: 600px; margin: 100px auto; text-align: center; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
			.sk-logo { max-width: 200px; margin-bottom: 20px; }
			.sk-step-list { text-align: left; margin: 30px 0; border: 1px solid #eee; border-radius: 8px; overflow: hidden; }
			.sk-step { padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; }
			.sk-step:last-child { border-bottom: none; }
			.sk-step-status { font-weight: bold; }
			.sk-step-status.pending { color: #999; }
			.sk-step-status.working { color: #2271b1; }
			.sk-step-status.done { color: #46b450; }
			.sk-step-status.error { color: #d63638; }
			.button-hero { margin-top: 20px; }
		</style>

		<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.svg'; ?>" alt="Skin Cupid" class="sk-logo" onerror="this.style.display='none'">
		<h1><?php _e( 'Preparando tu tienda', 'skincare' ); ?></h1>
		<p><?php _e( 'Vamos a instalar los componentes necesarios para que todo funcione.', 'skincare' ); ?></p>

		<div class="sk-step-list">
			<?php foreach ( $required as $plugin ) : ?>
				<div class="sk-step" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>">
					<span class="sk-step-name"><?php echo esc_html( $plugin['name'] ); ?></span>
					<span class="sk-step-status pending"><?php _e( 'Pendiente', 'skincare' ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

	<button id="sk-start-setup" class="button button-primary button-hero"><?php _e( 'Iniciar Instalación', 'skincare' ); ?></button>
	<p id="sk-setup-message" style="margin-top: 15px; color: #666;"></p>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var plugins = <?php echo json_encode( array_values( $required ) ); ?>;
		var currentIndex = 0;

		$('#sk-start-setup').on('click', function() {
			$(this).prop('disabled', true).text('<?php _e( "Instalando...", "skincare" ); ?>');
			processNextPlugin();
		});

		function processNextPlugin() {
			if ( currentIndex >= plugins.length ) {
				$('#sk-setup-message').text('<?php _e( "¡Todo listo! Redirigiendo...", "skincare" ); ?>');
				window.location.href = '<?php echo admin_url( "admin.php?page=sk-onboarding" ); ?>';
				return;
			}

			var plugin = plugins[currentIndex];
			var $row = $('.sk-step[data-slug="' + plugin.slug + '"]');
			var $status = $row.find('.sk-step-status');

			$status.removeClass('pending').addClass('working').text('<?php _e( "Procesando...", "skincare" ); ?>');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'sk_theme_install_plugin',
					slug: plugin.slug,
					source: plugin.source,
					nonce: '<?php echo wp_create_nonce( "sk_theme_setup_nonce" ); ?>'
				},
				success: function(response) {
					if ( response.success ) {
						$status.removeClass('working').addClass('done').text('<?php _e( "Instalado", "skincare" ); ?>');
						currentIndex++;
						processNextPlugin();
					} else {
						$status.removeClass('working').addClass('error').text('<?php _e( "Error", "skincare" ); ?>');
						$('#sk-setup-message').text(response.data.message || 'Error desconocido');
						$('#sk-start-setup').prop('disabled', false).text('<?php _e( "Reintentar", "skincare" ); ?>');
					}
				},
				error: function() {
					$status.removeClass('working').addClass('error').text('<?php _e( "Error de red", "skincare" ); ?>');
				}
			});
		}
	});
	</script>
	<?php
}

/**
 * AJAX: Install Plugin.
 */
function skincare_ajax_install_plugin() {
	check_ajax_referer( 'sk_theme_setup_nonce', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( [ 'message' => 'Sin permisos.' ] );
	}

	$slug = sanitize_text_field( $_POST['slug'] );
	$source_type = sanitize_text_field( $_POST['source'] );

	// Find plugin details
	$plugins = skincare_required_plugins();
	$target_plugin = null;
	foreach ( $plugins as $p ) {
		if ( $p['slug'] === $slug ) {
			$target_plugin = $p;
			break;
		}
	}

	if ( ! $target_plugin ) {
		wp_send_json_error( [ 'message' => 'Plugin no encontrado.' ] );
	}

	// Check if already active
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( $target_plugin['file'] ) ) {
		wp_send_json_success( [ 'message' => 'Ya activo.' ] );
	}

	// Check if installed but inactive
	if ( skincare_is_plugin_installed( $target_plugin['file'] ) ) {
		activate_plugin( $target_plugin['file'] );
		wp_send_json_success( [ 'message' => 'Activado.' ] );
	}

	// Install logic
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
	include_once( ABSPATH . 'wp-admin/includes/file.php' );

	$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
	$download_link = '';

	if ( $source_type === 'bundled' ) {
		$source = trailingslashit( get_stylesheet_directory() ) . 'bundled-plugins/' . $slug;
		$target = trailingslashit( WP_PLUGIN_DIR ) . $slug;

		if ( ! is_dir( $source ) ) {
			wp_send_json_error( [ 'message' => 'Paquete local no encontrado.' ] );
		}

		WP_Filesystem();
		$result = copy_dir( $source, $target );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => $result->get_error_message() ] );
		}

	} elseif ( $source_type === 'wporg' ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$api = plugins_api( 'plugin_information', [ 'slug' => $slug, 'fields' => [ 'sections' => false ] ] );

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( [ 'message' => 'Error API WP.' ] );
		}

		$result = $upgrader->install( $api->download_link );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'message' => 'Error instalación.' ] );
		}
	}

	// Activate after install
	activate_plugin( $target_plugin['file'] );
	wp_send_json_success( [ 'message' => 'Instalado y activado.' ] );
}
add_action( 'wp_ajax_sk_theme_install_plugin', 'skincare_ajax_install_plugin' );

/**
 * Admin notice if setup is incomplete and redirect was skipped.
 */
function skincare_setup_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) || is_network_admin() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && in_array( $screen->id, [ 'appearance_page_sk-theme-setup', 'toplevel_page_skincare-site-kit', 'skincare-site-kit_page_sk-onboarding' ], true ) ) {
		return;
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$all_required_active = skincare_are_all_required_active();

	if ( ! $all_required_active ) {
		$link = admin_url( 'admin.php?page=sk-theme-setup' );
		echo '<div class="notice notice-warning is-dismissible"><p>' .
			esc_html__( 'La configuración de Skin Cupid no está completa.', 'skincare' ) .
			' <a class="button button-primary" href="' . esc_url( $link ) . '">' .
			esc_html__( 'Continuar configuración', 'skincare' ) .
			'</a></p></div>';
	}
}
add_action( 'admin_notices', 'skincare_setup_admin_notice' );
