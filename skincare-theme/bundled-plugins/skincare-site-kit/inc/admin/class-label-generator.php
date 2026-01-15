<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Label_Generator {

	public static function init() {
		add_action( 'admin_action_sk_print_label', [ __CLASS__, 'handle_print_action' ] );
		// Add button to Order Management (we can hook into the actions block in Order_Management if we want, or rely on the links already there).
		// Note: The existing Order_Management code had links to `page=sk-shipping-labels`. I will make this action handle that or similar.
	}

	public static function handle_print_action() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'Unauthorized' );
		}

		$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
		if ( ! $order_id ) {
			wp_die( 'No Order ID' );
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			wp_die( 'Invalid Order' );
		}

		self::render_label_html( $order );
		exit;
	}

	private static function render_label_html( $order ) {
		$zone = $order->get_meta( Operations_Core::META_ZONE, true );
		$agency = $order->get_meta( Operations_Core::META_AGENCY, true );
		$ticket = $order->get_meta( Operations_Core::META_TICKET, true );
		$code = $order->get_meta( Operations_Core::META_PICKUP_CODE, true );

		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Rótulo #<?php echo $order->get_order_number(); ?></title>
			<style>
				body { font-family: sans-serif; padding: 20px; }
				.label-box {
					border: 2px solid #000;
					width: 100mm;
					height: 150mm;
					padding: 10px;
					box-sizing: border-box;
					position: relative;
				}
				.header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
				.logo { font-size: 24px; font-weight: bold; }
				.row { margin-bottom: 15px; }
				.label { font-size: 12px; color: #555; text-transform: uppercase; }
				.value { font-size: 16px; font-weight: bold; }
				.big-value { font-size: 24px; font-weight: bold; }
				.footer { position: absolute; bottom: 10px; left: 10px; width: 100%; font-size: 10px; }
				@media print {
					@page { size: 100mm 150mm; margin: 0; }
					body { margin: 0; padding: 0; }
					.label-box { border: none; width: 100%; height: 100%; }
				}
			</style>
		</head>
		<body onload="window.print()">
			<div class="label-box">
				<div class="header">
					<div class="logo">SKIN CUPID</div>
					<div><?php echo esc_html( ucfirst( $agency ) ); ?> - <?php echo esc_html( ucfirst( $zone ) ); ?></div>
				</div>

				<div class="row">
					<div class="label">Destinatario</div>
					<div class="value"><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></div>
					<div class="value"><?php echo esc_html( $order->get_billing_phone() ); ?></div>
				</div>

				<div class="row">
					<div class="label">Dirección</div>
					<div class="value">
						<?php echo esc_html( $order->get_billing_address_1() ); ?>
						<br>
						<?php echo esc_html( $order->get_billing_city() ); ?>
						<?php if ( $order->get_billing_state() ) echo ', ' . esc_html( $order->get_billing_state() ); ?>
					</div>
				</div>

				<div class="row">
					<div class="label">Referencia</div>
					<div class="value"><?php echo esc_html( $order->get_meta( '_billing_reference', true ) ); ?></div>
				</div>

				<div class="row" style="margin-top: 30px; border: 2px solid #000; padding: 10px; text-align: center;">
					<div class="label">PEDIDO</div>
					<div class="big-value">#<?php echo $order->get_order_number(); ?></div>
				</div>

				<?php if ( $ticket ) : ?>
					<div class="row" style="margin-top: 10px;">
						<div class="label">Ticket / Guía</div>
						<div class="value"><?php echo esc_html( $ticket ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $code ) : ?>
					<div class="row">
						<div class="label">Clave Recojo</div>
						<div class="value"><?php echo esc_html( $code ); ?></div>
					</div>
				<?php endif; ?>

				<div class="footer">
					Remitente: Skin Cupid Peru<br>
					Fecha: <?php echo date( 'd/m/Y' ); ?>
				</div>
			</div>
		</body>
		</html>
		<?php
	}
}
