<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Migration_Center {
	const EXPORT_ACTION = 'sk_migration_export';
	const NONCE_ACTION = 'sk_migration_nonce';
	const OPTION_KEYS = [
		'sk_theme_builder_settings',
		'sk_theme_branding_settings',
		'sk_press_page_settings',
		'sk_tracking_settings',
		'sk_coupon_automation_settings',
		'sk_notification_settings',
		'sk_notification_templates',
	];
	const WC_OPTIONS = [
		'woocommerce_currency',
		'woocommerce_currency_pos',
		'woocommerce_thousand_sep',
		'woocommerce_decimal_sep',
		'woocommerce_price_num_decimals',
		'woocommerce_calc_taxes',
		'woocommerce_tax_based_on',
		'woocommerce_prices_include_tax',
		'woocommerce_tax_round_at_subtotal',
		'woocommerce_tax_display_shop',
		'woocommerce_tax_display_cart',
		'woocommerce_shipping_tax_class',
		'woocommerce_default_country',
		'woocommerce_allowed_countries',
		'woocommerce_all_except_countries',
		'woocommerce_cart_page_id',
		'woocommerce_checkout_page_id',
		'woocommerce_myaccount_page_id',
		'woocommerce_shop_page_id',
		'woocommerce_terms_page_id',
		'woocommerce_checkout_pay_endpoint',
		'woocommerce_checkout_order_received_endpoint',
		'woocommerce_myaccount_add_payment_method_endpoint',
		'woocommerce_myaccount_edit_account_endpoint',
		'woocommerce_myaccount_edit_address_endpoint',
		'woocommerce_myaccount_lost_password_endpoint',
		'woocommerce_myaccount_orders_endpoint',
		'woocommerce_myaccount_payment_methods_endpoint',
		'woocommerce_myaccount_view_order_endpoint',
	];
	const SENSITIVE_FIELDS = [
		'whatsapp_access_token',
		'whatsapp_phone_id',
		'mailgun_api_key',
		'sendgrid_api_key',
	];

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_' . self::EXPORT_ACTION, [ __CLASS__, 'handle_export' ] );
		add_action( 'wp_ajax_sk_migration_dry_run', [ __CLASS__, 'handle_dry_run' ] );
		add_action( 'wp_ajax_sk_migration_apply', [ __CLASS__, 'handle_apply' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Migración fácil', 'skincare' ),
			__( 'Migración fácil', 'skincare' ),
			'manage_options',
			'sk-migration-center',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$env_checks = self::get_environment_checks();
		$nonce = wp_create_nonce( self::NONCE_ACTION );
		$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=' . self::EXPORT_ACTION ), self::NONCE_ACTION );
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Migración fácil (One Click)', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Exporta la configuración completa y restáurala en un WordPress limpio sin pasos manuales.', 'skincare' ); ?></p>

			<div class="sk-admin-hero">
				<div class="sk-admin-grid">
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Paso 1', 'skincare' ); ?></strong>
						<p><?php esc_html_e( 'Instala WordPress + WooCommerce + WoodMart + Skincare Site Kit.', 'skincare' ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Paso 2', 'skincare' ); ?></strong>
						<p><?php esc_html_e( 'Sube el archivo de exportación y revisa el simulador.', 'skincare' ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Paso 3', 'skincare' ); ?></strong>
						<p><?php esc_html_e( 'Aplica los cambios y la tienda queda lista.', 'skincare' ); ?></p>
					</div>
				</div>
			</div>

			<div class="sk-admin-panel is-active">
				<h2><?php esc_html_e( 'Verificador de entorno', 'skincare' ); ?></h2>
				<p><?php esc_html_e( 'Confirma que el servidor cumple con los requisitos mínimos para migrar.', 'skincare' ); ?></p>
				<div class="sk-admin-card">
					<table class="widefat striped sk-admin-table">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Chequeo', 'skincare' ); ?></th>
								<th><?php esc_html_e( 'Estado', 'skincare' ); ?></th>
								<th><?php esc_html_e( 'Detalle', 'skincare' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $env_checks as $check ) : ?>
								<tr>
									<td><?php echo esc_html( $check['label'] ); ?></td>
									<td><span class="sk-admin-status sk-admin-status--<?php echo esc_attr( $check['status'] ); ?>"><?php echo esc_html( strtoupper( $check['status'] ) ); ?></span></td>
									<td><?php echo esc_html( $check['message'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="sk-admin-panel is-active" style="margin-top: 20px;">
				<h2><?php esc_html_e( 'Exportador', 'skincare' ); ?></h2>
				<p><?php esc_html_e( 'Descarga un JSON con configuraciones, ajustes de WooCommerce y manifest del entorno.', 'skincare' ); ?></p>
				<form method="post" action="<?php echo esc_url( $export_url ); ?>">
					<input type="hidden" name="sk_nonce" value="<?php echo esc_attr( $nonce ); ?>">
					<label class="sk-admin-checkbox">
						<input type="checkbox" name="exclude_sensitive" value="1" checked>
						<?php esc_html_e( 'Excluir datos sensibles (tokens, llaves API).', 'skincare' ); ?>
					</label>
					<div style="margin-top: 12px;">
						<button class="button button-primary"><?php esc_html_e( 'Descargar exportación', 'skincare' ); ?></button>
					</div>
				</form>
			</div>

			<div class="sk-admin-panel is-active" style="margin-top: 20px;">
				<h2><?php esc_html_e( 'Importador', 'skincare' ); ?></h2>
				<p><?php esc_html_e( 'Sube el JSON/ZIP, simula los cambios y aplica con un solo click.', 'skincare' ); ?></p>
				<form id="sk-migration-form" enctype="multipart/form-data">
					<input type="hidden" name="action" value="sk_migration_dry_run">
					<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
					<input type="file" name="migration_file" accept=".json,.zip" required>
					<label class="sk-admin-checkbox">
						<input type="checkbox" name="apply_changes" value="1">
						<?php esc_html_e( 'Aplicar cambios inmediatamente (sin simulación).', 'skincare' ); ?>
					</label>
					<div class="sk-admin-actions">
						<button type="submit" class="button"><?php esc_html_e( 'Simular cambios', 'skincare' ); ?></button>
						<button type="button" class="button button-primary" id="sk-migration-apply"><?php esc_html_e( 'Aplicar importación', 'skincare' ); ?></button>
					</div>
				</form>
				<div id="sk-migration-report" class="sk-admin-report" aria-live="polite"></div>
			</div>
		</div>
		<?php
	}

	public static function handle_export() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'No autorizado.', 'skincare' ) );
		}

		check_admin_referer( self::NONCE_ACTION );

		$exclude_sensitive = ! empty( $_POST['exclude_sensitive'] );
		$payload = self::build_export_payload( $exclude_sensitive );
		$filename = 'skincare-migration-' . gmdate( 'Ymd-His' ) . '.json';

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		echo wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
		exit;
	}

	public static function handle_dry_run() {
		self::handle_import_request( true );
	}

	public static function handle_apply() {
		self::handle_import_request( false );
	}

	private static function handle_import_request( $dry_run ) {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'No autorizado.', 'skincare' ) ] );
		}

		$file = isset( $_FILES['migration_file'] ) ? $_FILES['migration_file'] : null;
		if ( ! $file || empty( $file['name'] ) ) {
			wp_send_json_error( [ 'message' => __( 'Debes adjuntar un archivo JSON o ZIP.', 'skincare' ) ] );
		}

		$payload = self::read_payload_from_upload( $file );
		if ( is_wp_error( $payload ) ) {
			wp_send_json_error( [ 'message' => $payload->get_error_message() ] );
		}

		$report = self::build_import_report( $payload, $dry_run );

		if ( ! $dry_run ) {
			$apply_result = self::apply_import_payload( $payload );
			if ( is_wp_error( $apply_result ) ) {
				wp_send_json_error( [ 'message' => $apply_result->get_error_message(), 'report' => $report ] );
			}
			$report['applied'] = true;
		}

		wp_send_json_success( $report );
	}

	private static function build_export_payload( $exclude_sensitive ) {
		global $wp_version;

		$options = [];
		foreach ( self::OPTION_KEYS as $option_key ) {
			$options[ $option_key ] = get_option( $option_key );
		}

		if ( $exclude_sensitive && isset( $options['sk_notification_settings'] ) && is_array( $options['sk_notification_settings'] ) ) {
			foreach ( self::SENSITIVE_FIELDS as $field ) {
				if ( array_key_exists( $field, $options['sk_notification_settings'] ) ) {
					$options['sk_notification_settings'][ $field ] = '';
				}
			}
		}

		$woocommerce = [];
		foreach ( self::WC_OPTIONS as $option_key ) {
			$woocommerce[ $option_key ] = get_option( $option_key );
		}

		$pages = self::get_wc_pages_snapshot( $woocommerce );

		return [
			'manifest' => [
				'exported_at' => current_time( 'mysql' ),
				'php_version' => PHP_VERSION,
				'wp_version' => $wp_version,
				'wc_version' => defined( 'WC_VERSION' ) ? WC_VERSION : null,
				'theme' => wp_get_theme()->get( 'Name' ),
				'active_plugins' => get_option( 'active_plugins', [] ),
			],
			'options' => $options,
			'woocommerce' => $woocommerce,
			'gateways' => self::get_gateway_settings(),
			'shipping_zones' => self::get_shipping_zones(),
			'pages' => $pages,
		];
	}

	private static function get_wc_pages_snapshot( $woocommerce ) {
		$page_keys = [
			'woocommerce_shop_page_id' => 'shop',
			'woocommerce_cart_page_id' => 'cart',
			'woocommerce_checkout_page_id' => 'checkout',
			'woocommerce_myaccount_page_id' => 'myaccount',
		];

		$pages = [];
		foreach ( $page_keys as $option_key => $slug ) {
			$page_id = isset( $woocommerce[ $option_key ] ) ? absint( $woocommerce[ $option_key ] ) : 0;
			$page = $page_id ? get_post( $page_id ) : null;
			if ( $page ) {
				$pages[ $slug ] = [
					'title' => $page->post_title,
					'slug' => $page->post_name,
					'content' => $page->post_content,
				];
			}
		}

		return $pages;
	}

	private static function build_import_report( $payload, $dry_run ) {
		$report = [
			'dry_run' => $dry_run,
			'changes' => [],
			'plugins' => self::get_required_plugins_status(),
			'pages' => [],
			'warnings' => [],
		];

		if ( ! empty( $payload['options'] ) && is_array( $payload['options'] ) ) {
			foreach ( $payload['options'] as $key => $value ) {
				$current = get_option( $key );
				if ( $current !== $value ) {
					$report['changes'][] = sprintf( __( 'Actualizar opción %s', 'skincare' ), $key );
				}
			}
		}

		$required_pages = self::ensure_wc_pages( $payload, true );
		$report['pages'] = $required_pages;

		if ( empty( $report['changes'] ) ) {
			$report['changes'][] = __( 'No hay cambios detectados.', 'skincare' );
		}

		return $report;
	}

	private static function apply_import_payload( $payload ) {
		if ( empty( $payload['options'] ) || ! is_array( $payload['options'] ) ) {
			return new \WP_Error( 'sk_migration_invalid', __( 'Archivo inválido: opciones faltantes.', 'skincare' ) );
		}

		foreach ( $payload['options'] as $key => $value ) {
			update_option( $key, $value );
		}

		if ( ! empty( $payload['woocommerce'] ) && is_array( $payload['woocommerce'] ) ) {
			foreach ( $payload['woocommerce'] as $key => $value ) {
				update_option( $key, $value );
			}
		}

		if ( ! empty( $payload['gateways'] ) && is_array( $payload['gateways'] ) ) {
			self::apply_gateway_settings( $payload['gateways'] );
		}

		if ( ! empty( $payload['shipping_zones'] ) && is_array( $payload['shipping_zones'] ) ) {
			self::apply_shipping_zones( $payload['shipping_zones'] );
		}

		self::ensure_wc_pages( $payload, false );
		flush_rewrite_rules();
		return true;
	}

	private static function ensure_wc_pages( $payload, $dry_run ) {
		$pages = isset( $payload['pages'] ) && is_array( $payload['pages'] ) ? $payload['pages'] : [];
		$map = [
			'shop' => 'woocommerce_shop_page_id',
			'cart' => 'woocommerce_cart_page_id',
			'checkout' => 'woocommerce_checkout_page_id',
			'myaccount' => 'woocommerce_myaccount_page_id',
		];

		$results = [];
		foreach ( $map as $slug => $option_key ) {
			$page_id = absint( get_option( $option_key ) );
			if ( $page_id && get_post( $page_id ) ) {
				$results[] = sprintf( __( 'Página %s ya existe (ID %d).', 'skincare' ), $slug, $page_id );
				continue;
			}

			$page_data = isset( $pages[ $slug ] ) ? $pages[ $slug ] : [ 'title' => ucfirst( $slug ), 'slug' => $slug, 'content' => '' ];
			$results[] = sprintf( __( 'Crear página %s (%s).', 'skincare' ), $slug, $page_data['title'] );

			if ( ! $dry_run ) {
				$new_id = wp_insert_post( [
					'post_title' => $page_data['title'],
					'post_name' => $page_data['slug'],
					'post_content' => $page_data['content'],
					'post_status' => 'publish',
					'post_type' => 'page',
				] );
				if ( $new_id && ! is_wp_error( $new_id ) ) {
					update_option( $option_key, $new_id );
				}
			}
		}

		return $results;
	}

	private static function get_gateway_settings() {
		if ( ! function_exists( 'WC' ) ) {
			return [];
		}

		$gateways = WC()->payment_gateways() ? WC()->payment_gateways->payment_gateways() : [];
		$settings = [];
		foreach ( $gateways as $gateway ) {
			$settings[ $gateway->id ] = get_option( 'woocommerce_' . $gateway->id . '_settings', [] );
		}
		return $settings;
	}

	private static function apply_gateway_settings( $gateways ) {
		foreach ( $gateways as $gateway_id => $settings ) {
			update_option( 'woocommerce_' . sanitize_key( $gateway_id ) . '_settings', $settings );
		}
	}

	private static function get_shipping_zones() {
		if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
			return [];
		}

		$zones = [];
		foreach ( \WC_Shipping_Zones::get_zones() as $zone ) {
			$zones[] = [
				'name' => $zone['zone_name'],
				'order' => $zone['zone_order'],
				'locations' => $zone['zone_locations'],
				'methods' => self::normalize_shipping_methods( $zone['shipping_methods'] ),
			];
		}

		$default_zone = new \WC_Shipping_Zone( 0 );
		$zones[] = [
			'name' => $default_zone->get_zone_name(),
			'order' => $default_zone->get_zone_order(),
			'locations' => $default_zone->get_zone_locations(),
			'methods' => self::normalize_shipping_methods( $default_zone->get_shipping_methods() ),
		];

		return $zones;
	}

	private static function apply_shipping_zones( $zones ) {
		if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
			return;
		}

		foreach ( $zones as $zone_data ) {
			if ( empty( $zone_data['name'] ) ) {
				continue;
			}

			$existing_zone = self::find_zone_by_name( $zone_data['name'] );
			$zone = $existing_zone ? new \WC_Shipping_Zone( $existing_zone ) : new \WC_Shipping_Zone();
			$zone->set_zone_name( $zone_data['name'] );
			$zone->set_zone_order( (int) ( $zone_data['order'] ?? 0 ) );

			if ( ! empty( $zone_data['locations'] ) ) {
				$zone->set_zone_locations( $zone_data['locations'] );
			}

			$zone_id = $zone->save();
			if ( empty( $zone_data['methods'] ) ) {
				continue;
			}

			foreach ( $zone_data['methods'] as $method ) {
				if ( empty( $method['id'] ) ) {
					continue;
				}
				$instance_id = $zone->add_shipping_method( $method['id'] );
				if ( $instance_id ) {
					$zone->set_shipping_method_status( $instance_id, ! empty( $method['enabled'] ) ? 'yes' : 'no' );
					if ( ! empty( $method['settings'] ) ) {
						update_option( 'woocommerce_' . $method['id'] . '_' . $instance_id . '_settings', $method['settings'] );
					}
				}
			}
		}
	}

	private static function find_zone_by_name( $name ) {
		foreach ( \WC_Shipping_Zones::get_zones() as $zone ) {
			if ( $zone['zone_name'] === $name ) {
				return $zone['id'];
			}
		}
		return null;
	}

	private static function normalize_shipping_methods( $methods ) {
		$normalized = [];
		foreach ( $methods as $method ) {
			$normalized[] = [
				'id' => $method->id,
				'title' => $method->title,
				'enabled' => $method->enabled,
				'instance_id' => $method->instance_id,
				'settings' => $method->instance_settings,
			];
		}
		return $normalized;
	}

	private static function read_payload_from_upload( $file ) {
		$handled = wp_handle_upload( $file, [ 'test_form' => false ] );
		if ( ! empty( $handled['error'] ) ) {
			return new \WP_Error( 'sk_migration_upload', $handled['error'] );
		}

		$file_path = $handled['file'];
		$extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );
		$json_path = $file_path;

		if ( 'zip' === $extension ) {
			$temp_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'skincare-migration-' . wp_generate_uuid4();
			wp_mkdir_p( $temp_dir );
			$unzipped = unzip_file( $file_path, $temp_dir );
			if ( is_wp_error( $unzipped ) ) {
				return $unzipped;
			}
			$json_files = glob( $temp_dir . '/*.json' );
			if ( empty( $json_files ) ) {
				return new \WP_Error( 'sk_migration_zip', __( 'No se encontró un archivo JSON dentro del ZIP.', 'skincare' ) );
			}
			$json_path = $json_files[0];
		}

		$contents = file_get_contents( $json_path );
		if ( false === $contents ) {
			return new \WP_Error( 'sk_migration_read', __( 'No se pudo leer el archivo.', 'skincare' ) );
		}

		$payload = json_decode( $contents, true );
		if ( empty( $payload ) || ! is_array( $payload ) ) {
			return new \WP_Error( 'sk_migration_json', __( 'El JSON es inválido.', 'skincare' ) );
		}

		return $payload;
	}

	private static function get_required_plugins_status() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$statuses = [];
		$statuses[] = self::format_plugin_status( 'woocommerce/woocommerce.php', 'WooCommerce' );
		$statuses[] = self::format_plugin_status( 'elementor/elementor.php', 'Elementor' );
		$statuses[] = self::format_theme_status( 'woodmart', 'WoodMart' );

		return $statuses;
	}

	private static function format_plugin_status( $plugin, $label ) {
		$active = is_plugin_active( $plugin );
		return [
			'label' => $label,
			'status' => $active ? 'ok' : 'error',
			'message' => $active ? __( 'Activo', 'skincare' ) : __( 'No activo o no instalado.', 'skincare' ),
		];
	}

	private static function format_theme_status( $theme_slug, $label ) {
		$theme = wp_get_theme( $theme_slug );
		$exists = $theme->exists();
		return [
			'label' => $label,
			'status' => $exists ? 'ok' : 'error',
			'message' => $exists ? __( 'Instalado', 'skincare' ) : __( 'Tema no instalado.', 'skincare' ),
		];
	}

	private static function get_environment_checks() {
		global $wpdb;

		$checks = [];
		$checks[] = self::format_check( __( 'PHP >= 7.4', 'skincare' ), version_compare( PHP_VERSION, '7.4', '>=' ), PHP_VERSION );
		$checks[] = self::format_check( __( 'memory_limit >= 256MB', 'skincare' ), self::to_bytes( ini_get( 'memory_limit' ) ) >= 268435456, ini_get( 'memory_limit' ) );
		$checks[] = self::format_check( __( 'max_execution_time >= 300s', 'skincare' ), (int) ini_get( 'max_execution_time' ) >= 300, ini_get( 'max_execution_time' ) . 's' );
		$checks[] = self::format_check( __( 'upload_max_filesize >= 32MB', 'skincare' ), self::to_bytes( ini_get( 'upload_max_filesize' ) ) >= 33554432, ini_get( 'upload_max_filesize' ) );
		$db_ok = $wpdb && $wpdb->check_connection();
		$checks[] = self::format_check( __( 'Base de datos accesible', 'skincare' ), $db_ok, $db_ok ? __( 'Conectado', 'skincare' ) : __( 'Sin conexión', 'skincare' ) );

		return $checks;
	}

	private static function format_check( $label, $pass, $value ) {
		$status = $pass ? 'ok' : 'warn';
		if ( ! $pass ) {
			$status = 'error';
		}
		return [
			'label' => $label,
			'status' => $status,
			'message' => $value,
		];
	}

	private static function to_bytes( $value ) {
		$value = trim( (string) $value );
		$last = strtolower( substr( $value, -1 ) );
		$number = (int) $value;
		switch ( $last ) {
			case 'g':
				$number *= 1024;
			case 'm':
				$number *= 1024;
			case 'k':
				$number *= 1024;
		}
		return $number;
	}
}
