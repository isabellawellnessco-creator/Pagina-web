<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Operations_Core {
	// Status Constants
	const STATUS_PENDING_CONFIRM = 'sk-pending-confirm';
	const STATUS_NO_RESPONSE_1   = 'sk-no-response-1';
	const STATUS_NO_RESPONSE_2   = 'sk-no-response-2';
	const STATUS_CANCELLED_NC    = 'sk-cancelled-nc'; // No confirmed
	const STATUS_CONFIRMED       = 'sk-confirmed';
	const STATUS_PICKING         = 'sk-picking';
	const STATUS_PACKED          = 'sk-packed';
	const STATUS_READY_DISPATCH  = 'sk-ready-dispatch'; // Lima
	const STATUS_DISPATCHED_LIMA = 'sk-dispatched-lima';
	const STATUS_DISPATCHED_PROV = 'sk-dispatched-prov';
	const STATUS_IN_TRANSIT      = 'sk-in-transit';
	const STATUS_READY_PICKUP    = 'sk-ready-pickup'; // Prov Shalom
	const STATUS_DELIVERED       = 'sk-delivered';
	const STATUS_INCIDENT        = 'sk-incident';

	// Meta Keys
	const META_STATUS       = '_sk_operational_status';
	const META_ZONE         = '_sk_zone'; // lima, provincia
	const META_PAYMENT_TYPE = '_sk_payment_type_ops'; // web, contraentrega
	const META_AGENCY       = '_sk_agency'; // urpi, shalom
	const META_TICKET       = '_sk_shalom_ticket';
	const META_PICKUP_CODE  = '_sk_pickup_code';
	const META_ADVANCE      = '_sk_advance_amount';
	const META_REAL_COST    = '_sk_real_shipping_cost';
	const META_URPI_ID      = '_sk_urpi_id';

	public static function get_operational_states() {
		return [
			self::STATUS_PENDING_CONFIRM => __( 'Pendiente confirmación', 'skincare' ),
			self::STATUS_NO_RESPONSE_1   => __( 'No responde (1er)', 'skincare' ),
			self::STATUS_NO_RESPONSE_2   => __( 'No responde (2do)', 'skincare' ),
			self::STATUS_CANCELLED_NC    => __( 'Cancelado (No Confir)', 'skincare' ),
			self::STATUS_CONFIRMED       => __( 'Confirmado', 'skincare' ),
			self::STATUS_PICKING         => __( 'Por preparar (Picking)', 'skincare' ),
			self::STATUS_PACKED          => __( 'Empacado', 'skincare' ),
			self::STATUS_READY_DISPATCH  => __( 'Listo para despacho', 'skincare' ),
			self::STATUS_DISPATCHED_LIMA => __( 'Despachado Lima', 'skincare' ),
			self::STATUS_DISPATCHED_PROV => __( 'Despachado Provincia', 'skincare' ),
			self::STATUS_IN_TRANSIT      => __( 'En tránsito', 'skincare' ),
			self::STATUS_READY_PICKUP    => __( 'Disponible recojo', 'skincare' ),
			self::STATUS_DELIVERED       => __( 'Entregado', 'skincare' ),
			self::STATUS_INCIDENT        => __( 'Incidencia', 'skincare' ),
		];
	}

	public static function get_agencies() {
		return [
			'urpi'   => __( 'Urpi (Lima)', 'skincare' ),
			'shalom' => __( 'Shalom (Provincia)', 'skincare' ),
		];
	}

	public static function get_zones() {
		return [
			'lima'      => __( 'Lima', 'skincare' ),
			'provincia' => __( 'Provincia', 'skincare' ),
		];
	}

	/**
	 * Main method to update status. Handles validation and WC sync.
	 */
	public static function update_operational_status( $order_id, $new_status, $force = false ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return new \WP_Error( 'invalid_order', 'Pedido no encontrado.' );
		}

		$current_status = $order->get_meta( self::META_STATUS, true );
		if ( $current_status === $new_status ) {
			return true;
		}

		// Validation
		if ( ! $force ) {
			$validation = self::validate_transition( $order, $new_status );
			if ( is_wp_error( $validation ) ) {
				return $validation;
			}
		}

		// Update Meta
		$order->update_meta_data( self::META_STATUS, $new_status );

		// Log History
		$order->add_order_note( sprintf(
			__( 'Cambio de Estado Operativo: %s -> %s', 'skincare' ),
			self::get_operational_states()[ $current_status ] ?? 'N/A',
			self::get_operational_states()[ $new_status ]
		) );

		// Sync WooCommerce Status
		self::sync_woocommerce_status( $order, $new_status );

		$order->save();

		return true;
	}

	/**
	 * Syncs the operational status to the native WC status.
	 */
	private static function sync_woocommerce_status( $order, $op_status ) {
		switch ( $op_status ) {
			case self::STATUS_PENDING_CONFIRM:
				// Ensure stock is reserved but not fully processed
				if ( $order->get_status() !== 'on-hold' ) {
					$order->set_status( 'on-hold', __( 'Esperando confirmación operativa.', 'skincare' ) );
				}
				break;

			case self::STATUS_CONFIRMED:
				// Move to processing to indicate we are working on it
				if ( $order->get_status() === 'on-hold' || $order->get_status() === 'pending' ) {
					$order->set_status( 'processing', __( 'Confirmado operativamente.', 'skincare' ) );
				}
				break;

			case self::STATUS_CANCELLED_NC:
				$order->set_status( 'cancelled', __( 'Cancelado por falta de confirmación.', 'skincare' ) );
				break;

			case self::STATUS_DELIVERED:
				// Finalize order
				if ( $order->get_status() !== 'completed' ) {
					$order->set_status( 'completed', __( 'Entregado operativamente.', 'skincare' ) );
				}
				break;
		}
	}

	/**
	 * Validates if the order has required fields to enter the new status.
	 */
	public static function validate_transition( $order, $new_status ) {
		$zone = $order->get_meta( self::META_ZONE, true );
		$agency = $order->get_meta( self::META_AGENCY, true );

		// 1. Despachado Provincia requires Ticket & Key (if Shalom)
		if ( $new_status === self::STATUS_DISPATCHED_PROV ) {
			if ( $agency === 'shalom' ) {
				if ( ! $order->get_meta( self::META_TICKET, true ) ) {
					return new \WP_Error( 'missing_ticket', __( 'Falta el Ticket/Guía de Shalom para despachar.', 'skincare' ) );
				}
				if ( ! $order->get_meta( self::META_PICKUP_CODE, true ) ) {
					return new \WP_Error( 'missing_code', __( 'Falta la Clave de Recojo de Shalom para despachar.', 'skincare' ) );
				}
			}
		}

		// 2. Despachado Lima requires Real Cost or Urpi ID (optional depending on strictness, user said "No marcar Lima despachado sin costo real")
		if ( $new_status === self::STATUS_DISPATCHED_LIMA ) {
			if ( ! $order->get_meta( self::META_REAL_COST, true ) && ! $order->get_meta( self::META_URPI_ID, true ) ) {
				// We enforce at least one evidence of dispatch
				return new \WP_Error( 'missing_dispatch_info', __( 'Debe ingresar el Costo Real o ID Urpi antes de marcar como despachado.', 'skincare' ) );
			}
		}

		// 3. Ready for Pickup (Provincia) requires Ticket
		if ( $new_status === self::STATUS_READY_PICKUP ) {
			if ( ! $order->get_meta( self::META_TICKET, true ) ) {
				return new \WP_Error( 'missing_ticket', __( 'No se puede avisar recojo sin número de Ticket.', 'skincare' ) );
			}
		}

		return true;
	}

	/**
	 * Initialize the class (register Ajax)
	 */
	public static function init() {
		add_action( 'wp_ajax_sk_update_op_status', [ __CLASS__, 'ajax_update_status' ] );
	}

	public static function ajax_update_status() {
		check_ajax_referer( 'sk_ops_action', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$order_id = absint( $_POST['order_id'] );
		$status   = sanitize_text_field( $_POST['status'] );

		$result = self::update_operational_status( $order_id, $status );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}

		wp_send_json_success( [
			'message' => __( 'Estado actualizado correctamente.', 'skincare' ),
			'new_label' => self::get_operational_states()[ $status ],
			'status_slug' => $status
		] );
	}
}
