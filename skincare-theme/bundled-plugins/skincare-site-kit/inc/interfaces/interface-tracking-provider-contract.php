<?php
namespace Skincare\SiteKit\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Tracking_Provider_Contract
 * Defines the contract for any shipping tracking provider integration.
 */
interface Tracking_Provider_Contract {

	/**
	 * Get the provider name (e.g., 'Manual', 'ShipStation').
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Get tracking details for a specific order.
	 *
	 * @param int|\WC_Order $order The order object or ID.
	 * @return array Normalized tracking data:
	 *               [
	 *                 'tracking_number' => string,
	 *                 'carrier'         => string,
	 *                 'tracking_url'    => string,
	 *                 'estimated_date'  => string,
	 *                 'current_status'  => string (normalized status key),
	 *                 'status_label'    => string,
	 *               ]
	 */
	public function get_tracking_details( $order );

	/**
	 * Determine the current step index (0-3 usually) for the visual stepper.
	 *
	 * @param int|\WC_Order $order
	 * @return int Step index (0 = Confirmed, 1 = Packing, 2 = Shipped, 3 = Delivered)
	 */
	public function get_current_step_index( $order );

	/**
	 * Get the steps definition for the visual stepper.
	 *
	 * @param int|\WC_Order $order
	 * @return array List of steps with labels and descriptions.
	 */
	public function get_steps( $order );
}
