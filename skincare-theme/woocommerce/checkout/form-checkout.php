<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @package SkincareTheme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'skincare' ) ) );
	return;
}
?>

<section class="sk-woo-page sk-woo-checkout">
	<div class="sk-container">
		<h1 class="sk-woo-title"><?php esc_html_e( 'Checkout', 'skincare' ); ?></h1>
		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<div class="sk-woo-checkout-layout">
				<div class="sk-woo-checkout-details sk-woo-card">
					<?php if ( $checkout->get_checkout_fields() ) : ?>
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="woocommerce-billing-fields">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="woocommerce-shipping-fields">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					<?php endif; ?>
				</div>

				<aside class="sk-woo-checkout-summary sk-woo-card">
					<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
					<h2><?php esc_html_e( 'Order Summary', 'skincare' ); ?></h2>
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>
					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</aside>
			</div>
		</form>
	</div>
</section>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
