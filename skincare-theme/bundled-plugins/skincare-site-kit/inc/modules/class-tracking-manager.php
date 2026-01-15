<?php
namespace Skincare\SiteKit\Modules;

use Skincare\SiteKit\Interfaces\Tracking_Provider_Contract;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tracking_Manager
 * Central service to handle tracking logic, delegating to the active provider.
 */
class Tracking_Manager {

	private static $provider = null;

	public static function init() {
		// Initialize default provider
		// In the future, this could be hookable to swap providers
		self::$provider = new Native_Manual_Provider();

		// Allow other plugins to override the provider
		add_action( 'init', [ __CLASS__, 'resolve_provider' ], 20 );
	}

	public static function resolve_provider() {
		$custom_provider = apply_filters( 'sk_tracking_provider', null );
		if ( $custom_provider instanceof Tracking_Provider_Contract ) {
			self::$provider = $custom_provider;
		}
	}

	/**
	 * @return Tracking_Provider_Contract
	 */
	public static function get_provider() {
		if ( ! self::$provider ) {
			self::$provider = new Native_Manual_Provider();
		}
		return self::$provider;
	}

	public static function get_tracking_details( $order ) {
		return self::get_provider()->get_tracking_details( $order );
	}

	public static function get_steps_data( $order ) {
		$provider = self::get_provider();
		return [
			'current_step' => $provider->get_current_step_index( $order ),
			'steps'        => $provider->get_steps( $order ),
		];
	}
}
