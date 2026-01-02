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
		// Skincare\SiteKit\Widgets\Hero -> inc/widgets/class-hero.php
		// Skincare\SiteKit\Core\Loader -> inc/core/class-loader.php

		$parts = explode( '\\', $relative_class );
		$file_name = 'class-' . str_replace( '_', '-', strtolower( array_pop( $parts ) ) ) . '.php';

		$folder = strtolower( implode( '/', $parts ) );

		$path = self::$base_path . $folder . '/' . $file_name;

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
