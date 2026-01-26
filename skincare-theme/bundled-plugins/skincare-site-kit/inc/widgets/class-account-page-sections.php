<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Sk_Account_Dashboard_Section extends Widget_Base {
	public function get_name() { return 'sk_account_dashboard_section'; }
	public function get_title() { return __( 'Account: Dashboard Section', 'skincare' ); }
	public function get_icon() { return 'eicon-person'; }
	public function get_categories() { return [ 'skincare-account' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<p>' . __( 'Please log in to view your account.', 'skincare' ) . '</p>';
			return;
		}

		$current_user = wp_get_current_user();

		// Get recent orders
		$customer_orders = wc_get_orders( [
			'customer' => $current_user->ID,
			'limit'    => 5,
			'orderby'  => 'date',
			'order'    => 'DESC',
		] );

		?>
		<div class="sk-account-section">
			<div class="sk-account-header">
				<h2 class="sk-section-title"><?php printf( __( 'Hello %s', 'skincare' ), esc_html( $current_user->display_name ) ); ?></h2>
				<p class="sk-account-subtitle"><?php esc_html_e( 'Welcome back to your account dashboard.', 'skincare' ); ?></p>
				<a href="<?php echo esc_url( wc_logout_url() ); ?>" class="sk-link-logout"><?php esc_html_e( 'Log out', 'skincare' ); ?></a>
			</div>

			<div class="sk-account-grid">
				<!-- Order History -->
				<div class="sk-account-block sk-orders-block">
					<h3 class="sk-block-title"><?php esc_html_e( 'Order History', 'skincare' ); ?></h3>
					<?php if ( $customer_orders ) : ?>
						<div class="sk-orders-list">
							<?php foreach ( $customer_orders as $order ) : ?>
								<div class="sk-order-item">
									<div class="sk-order-info">
										<span class="sk-order-number">#<?php echo esc_html( $order->get_order_number() ); ?></span>
										<span class="sk-order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
									</div>
									<div class="sk-order-status">
										<span class="sk-badge sk-badge--<?php echo esc_attr( $order->get_status() ); ?>">
											<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
										</span>
									</div>
									<div class="sk-order-total">
										<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
									</div>
									<div class="sk-order-actions">
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="sk-btn sk-btn--small sk-btn--outline">
											<?php esc_html_e( 'View', 'skincare' ); ?>
										</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<p><?php esc_html_e( 'You haven\'t placed any orders yet.', 'skincare' ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Account Details -->
				<div class="sk-account-block sk-details-block">
					<h3 class="sk-block-title"><?php esc_html_e( 'Account Details', 'skincare' ); ?></h3>
					<div class="sk-details-content">
						<p><strong><?php esc_html_e( 'Name:', 'skincare' ); ?></strong> <?php echo esc_html( $current_user->display_name ); ?></p>
						<p><strong><?php esc_html_e( 'Email:', 'skincare' ); ?></strong> <?php echo esc_html( $current_user->user_email ); ?></p>

						<div class="sk-addresses-preview">
							<h4><?php esc_html_e( 'Default Address', 'skincare' ); ?></h4>
							<address>
								<?php
									$address = get_user_meta( $current_user->ID, 'billing_address_1', true );
									$city = get_user_meta( $current_user->ID, 'billing_city', true );
									$postcode = get_user_meta( $current_user->ID, 'billing_postcode', true );
									if ( $address ) {
										echo esc_html( $address ) . '<br>' . esc_html( $city ) . ' ' . esc_html( $postcode );
									} else {
										esc_html_e( 'No address set.', 'skincare' );
									}
								?>
							</address>
						</div>

						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="sk-btn sk-btn--text"><?php esc_html_e( 'Edit details', 'skincare' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
