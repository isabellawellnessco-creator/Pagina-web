<?php
namespace Skincare\SiteKit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Autoloader {

	private static $namespace_root = 'Skincare\\SiteKit\\';
	private static $base_path = '';

	public static function init() {
		self::$base_path = plugin_dir_path( dirname( __DIR__ ) ) . 'inc/';
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	public static function autoload( $class ) {
		// Check if class belongs to our namespace
		if ( 0 !== strpos( $class, self::$namespace_root ) ) {
			return;
		}

		// Remove namespace root
		$relative_class = substr( $class, strlen( self::$namespace_root ) );

		// Map Namespace to Folder Structure
		// Skincare\SiteKit\Modules\MegaMenu -> inc/modules/class-mega-menu.php
		// Skincare\SiteKit\Widgets\Hero -> inc/widgets/class-hero.php (sections)
		// Skincare\SiteKit\Core\Loader -> inc/core/class-loader.php

		$parts = explode( '\\', $relative_class );
		$file_stub = str_replace( '_', '-', strtolower( array_pop( $parts ) ) );
		$folder = strtolower( implode( '/', $parts ) );
		$base_path = self::$base_path . $folder . '/';

		$prefixes = [ 'class-', 'interface-', 'trait-' ];

		foreach ( $prefixes as $prefix ) {
			$path = $base_path . $prefix . $file_stub . '.php';
			if ( file_exists( $path ) ) {
				require_once $path;
				break;
			}
		}
	}
}
