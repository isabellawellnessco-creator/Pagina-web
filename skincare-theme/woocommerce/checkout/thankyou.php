<?php
/**
 * Thankyou page
 *
 * @package WooCommerce/Templates
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="sk-page sk-woo-thankyou">
	<?php if ( $order ) : ?>
		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<div class="woocommerce-order">
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
					<?php esc_html_e( 'Lamentablemente tu pedido no pudo ser procesado. Inténtalo nuevamente.', 'woocommerce' ); ?>
				</p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-primary">
						<?php esc_html_e( 'Pagar', 'woocommerce' ); ?>
					</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-secondary">
							<?php esc_html_e( 'Mi cuenta', 'woocommerce' ); ?>
						</a>
					<?php endif; ?>
				</p>
			</div>
		<?php else : ?>
			<div class="woocommerce-order">
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
					<?php echo wp_kses_post( apply_filters( 'woocommerce_thankyou_order_received_text', __( '¡Gracias! Tu pedido ha sido recibido.', 'woocommerce' ), $order ) ); ?>
				</p>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
					<li class="woocommerce-order-overview__order order">
						<?php esc_html_e( 'Pedido:', 'woocommerce' ); ?>
						<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
					</li>
					<li class="woocommerce-order-overview__date date">
						<?php esc_html_e( 'Fecha:', 'woocommerce' ); ?>
						<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
					</li>
					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
							<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
						</li>
					<?php endif; ?>
					<li class="woocommerce-order-overview__total total">
						<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
					</li>
					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<?php esc_html_e( 'Método de pago:', 'woocommerce' ); ?>
							<strong><?php echo esc_html( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>
				</ul>

				<div class="sk-section">
					<a class="btn btn-primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php esc_html_e( 'Seguir comprando', 'woocommerce' ); ?>
					</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a class="btn btn-secondary" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
							<?php esc_html_e( 'Ver mis pedidos', 'woocommerce' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
	<?php else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
			<?php echo wp_kses_post( apply_filters( 'woocommerce_thankyou_order_received_text', __( '¡Gracias! Tu pedido ha sido recibido.', 'woocommerce' ), null ) ); ?>
		</p>
	<?php endif; ?>
</div>
