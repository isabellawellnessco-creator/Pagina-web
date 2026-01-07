<?php
/**
 * Plugin setup and dependency handling.
 *
 * @package SkincareTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

function skincare_is_plugin_installed( $plugin_file ) {
	return file_exists( WP_PLUGIN_DIR . '/' . $plugin_file );
}

function skincare_activate_plugin( $plugin_file ) {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( is_plugin_active( $plugin_file ) ) {
		return;
	}

	$activation = activate_plugin( $plugin_file );
	if ( is_wp_error( $activation ) ) {
		set_transient( 'skincare_plugin_activation_error', $activation->get_error_message(), 30 );
	}
}

function skincare_maybe_install_wporg_plugin( $plugin ) {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$api = plugins_api( 'plugin_information', [
		'slug'   => $plugin['slug'],
		'fields' => [
			'short_description' => false,
			'sections'          => false,
			'versions'          => false,
		],
	] );

	if ( is_wp_error( $api ) || empty( $api->download_link ) ) {
		set_transient( 'skincare_plugin_install_error', sprintf( 'No se pudo obtener %s desde WordPress.org.', $plugin['name'] ), 30 );
		return;
	}

	$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
	$result = $upgrader->install( $api->download_link );
	if ( is_wp_error( $result ) ) {
		set_transient( 'skincare_plugin_install_error', $result->get_error_message(), 30 );
		return;
	}
}

function skincare_maybe_install_bundled_plugin( $plugin ) {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	$source_dir = trailingslashit( get_stylesheet_directory() ) . 'bundled-plugins/' . $plugin['slug'];
	$target_dir = trailingslashit( WP_PLUGIN_DIR ) . $plugin['slug'];

	if ( ! is_dir( $source_dir ) ) {
		set_transient( 'skincare_plugin_install_error', sprintf( 'No se encontró el paquete local de %s.', $plugin['name'] ), 30 );
		return;
	}

	if ( is_dir( $target_dir ) ) {
		return;
	}

	WP_Filesystem();

	$result = copy_dir( $source_dir, $target_dir );
	if ( is_wp_error( $result ) ) {
		set_transient( 'skincare_plugin_install_error', $result->get_error_message(), 30 );
	}
}

function skincare_handle_required_plugins() {
	if ( ! is_admin() ) {
		return;
	}

	$plugins = skincare_required_plugins();
	foreach ( $plugins as $plugin ) {
		if ( ! $plugin['required'] ) {
			continue;
		}

		$installed = skincare_is_plugin_installed( $plugin['file'] );

		switch ( $plugin['source'] ) {
			case 'wporg':
				if ( ! $installed ) {
					skincare_maybe_install_wporg_plugin( $plugin );
				}
				if ( skincare_is_plugin_installed( $plugin['file'] ) ) {
					skincare_activate_plugin( $plugin['file'] );
				}
				break;
			case 'bundled':
				if ( ! $installed ) {
					skincare_maybe_install_bundled_plugin( $plugin );
				}
				if ( skincare_is_plugin_installed( $plugin['file'] ) ) {
					skincare_activate_plugin( $plugin['file'] );
				}
				break;
			default:
				break;
		}
	}
}
add_action( 'admin_init', 'skincare_handle_required_plugins' );

function skincare_required_plugins_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	$errors = [];
	$install_error = get_transient( 'skincare_plugin_install_error' );
	$activation_error = get_transient( 'skincare_plugin_activation_error' );

	if ( $install_error ) {
		$errors[] = $install_error;
		delete_transient( 'skincare_plugin_install_error' );
	}

	if ( $activation_error ) {
		$errors[] = $activation_error;
		delete_transient( 'skincare_plugin_activation_error' );
	}

	$plugins = skincare_required_plugins();
	$missing_external = [];
	foreach ( $plugins as $plugin ) {
		if ( 'external' !== $plugin['source'] ) {
			continue;
		}

		if ( ! skincare_is_plugin_installed( $plugin['file'] ) ) {
			$missing_external[] = $plugin;
		}
	}

	if ( empty( $errors ) && empty( $missing_external ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p>';
	if ( ! empty( $errors ) ) {
		echo esc_html( implode( ' ', $errors ) ) . ' ';
	}

	if ( ! empty( $missing_external ) ) {
		$links = [];
		foreach ( $missing_external as $plugin ) {
			$links[] = sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
				esc_url( $plugin['external_url'] ),
				esc_html( $plugin['name'] )
			);
		}

		echo wp_kses_post( 'Descarga requerida: ' . implode( ', ', $links ) . '.' );
	}
	echo '</p></div>';
}
add_action( 'admin_notices', 'skincare_required_plugins_notice' );
