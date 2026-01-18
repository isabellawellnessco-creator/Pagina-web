<?php
namespace Skincare\SiteKit\Modules;

use Skincare\SiteKit\Interfaces\Tracking_Provider_Contract;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( Tracking_Provider_Contract::class ) && defined( 'SKINCARE_KIT_PATH' ) ) {
	require_once SKINCARE_KIT_PATH . 'inc/interfaces/interface-tracking-provider-contract.php';
}

/**
 * Class Native_Manual_Provider
 * Implements the tracking logic using manual metadata fields (_sk_tracking_number, etc).
 */
class Native_Manual_Provider implements Tracking_Provider_Contract {

	public function get_name() {
		return 'Manual (Nativo)';
	}

	public function get_tracking_details( $order ) {
		$order = wc_get_order( $order );
		if ( ! $order ) {
			return [];
		}

		return [
			'tracking_number' => $order->get_meta( '_sk_tracking_number', true ),
			'carrier'         => $order->get_meta( '_sk_carrier', true ),
			'tracking_url'    => $order->get_meta( '_sk_tracking_url', true ),
			'estimated_date'  => $order->get_meta( '_sk_estimated_delivery', true ), // Assuming this field might exist or be added
			'current_status'  => $order->get_status(),
			'status_label'    => wc_get_order_status_name( $order->get_status() ),
			'packing_status'  => $order->get_meta( '_sk_packing_status', true ), // Specific to manual
		];
	}

	public function get_current_step_index( $order ) {
		$order = wc_get_order( $order );
		if ( ! $order ) return 0;

		$status = $order->get_status();
		$packing_status = $order->get_meta( '_sk_packing_status', true );
		$tracking_number = $order->get_meta( '_sk_tracking_number', true );
		$tracking_url = $order->get_meta( '_sk_tracking_url', true );

		$steps = $this->default_tracking_steps();

		// Logic extracted from original Account_Dashboard
		// 3 = Delivered, 2 = Shipped, 1 = Packing, 0 = Confirmed

		$normalized_status = 'wc-' . $status;
		$statuses = $this->parse_step_statuses( $steps );

		// Delivered
		if ( in_array( $normalized_status, $statuses[3], true ) || 'completed' === $status ) {
			return 3;
		}
		// Shipped
		if ( $tracking_number || $tracking_url || in_array( $normalized_status, $statuses[2], true ) ) {
			return 2;
		}
		// Packing
		if ( $packing_status || in_array( $normalized_status, $statuses[1], true ) ) {
			return 1;
		}

		return 0;
	}

	public function get_steps( $order ) {
		$order = wc_get_order( $order );
		$steps = $this->default_tracking_steps();

		if ( ! $order ) return $steps;

		$details = $this->get_tracking_details( $order );

		// Hydrate dynamic values
		if ( ! empty( $details['packing_status'] ) ) {
			$steps[1]['desc'] = $details['packing_status'];
		}

		if ( ! empty( $details['tracking_number'] ) ) {
			$steps[2]['desc'] = __( 'Tu pedido va en camino.', 'skincare' );
		}

		return $steps;
	}

	private function default_tracking_steps() {
		// Could fetch from options if customizable, or hardcoded for native
		$tracking_settings = get_option( 'sk_tracking_settings', [] );
		$saved_steps = isset( $tracking_settings['steps'] ) && is_array( $tracking_settings['steps'] )
			? array_values( $tracking_settings['steps'] )
			: [];

		if ( count( $saved_steps ) >= 4 ) {
			return $saved_steps;
		}

		return [
			[
				'label' => __( 'Pedido confirmado', 'skincare' ),
				'desc' => __( 'Estamos preparando tu pedido.', 'skincare' ),
				'statuses' => 'wc-processing,wc-on-hold,wc-pending',
			],
			[
				'label' => __( 'Empaque', 'skincare' ),
				'desc' => __( 'Empaque en progreso.', 'skincare' ),
				'statuses' => 'wc-processing',
			],
			[
				'label' => __( 'En camino', 'skincare' ),
				'desc' => __( 'Tu pedido va en camino.', 'skincare' ),
				'statuses' => 'wc-sk-on-the-way',
			],
			[
				'label' => __( 'Entregado', 'skincare' ),
				'desc' => __( 'Pedido entregado.', 'skincare' ),
				'statuses' => 'wc-completed,wc-sk-delivered',
			],
		];
	}

	private function parse_step_statuses( $steps ) {
		$statuses = [];
		foreach ( $steps as $step ) {
			$raw = isset( $step['statuses'] ) ? $step['statuses'] : '';
			$list = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
			$statuses[] = $list;
		}
		return array_pad( $statuses, 4, [] );
	}
}
