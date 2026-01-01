<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;

class Account_Dashboard extends Widget_Base {
	public function get_name() { return 'sk_account_dashboard'; }
	public function get_title() { return __( 'SK Account Dashboard', 'skincare' ); }
	public function get_icon() { return 'eicon-person'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<div class="sk-login-prompt">';
			echo '<h3>' . __( 'Bienvenido', 'skincare' ) . '</h3>';
			echo '<p>' . __( 'Por favor inicia sesión para ver tu cuenta.', 'skincare' ) . '</p>';
			wp_login_form();
			echo '</div>';
			return;
		}

		$current_user = wp_get_current_user();

		// Get Orders
		$orders = wc_get_orders( [ 'customer' => $current_user->ID, 'limit' => 5 ] );

		// Get Address
		$address = wc_get_account_formatted_address( 'billing' );

		?>
		<div class="sk-account-dashboard">
			<div class="sk-account-header">
				<h2><?php printf( __( 'Hola, %s', 'skincare' ), esc_html( $current_user->display_name ) ); ?></h2>
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="sk-logout-link"><?php _e( 'Cerrar Sesión', 'skincare' ); ?></a>
			</div>

			<div class="sk-account-tabs-wrapper">
				<div class="sk-account-tabs">
					<button class="sk-tab-btn active" data-target="#tab-orders"><?php _e( 'Mis Pedidos', 'skincare' ); ?></button>
					<button class="sk-tab-btn" data-target="#tab-address"><?php _e( 'Direcciones', 'skincare' ); ?></button>
				</div>

				<div id="tab-orders" class="sk-tab-pane active">
					<?php if ( empty( $orders ) ) : ?>
						<p><?php _e( 'No has realizado pedidos aún.', 'skincare' ); ?></p>
						<a href="/tienda/" class="btn sk-btn"><?php _e( 'Ir a la Tienda', 'skincare' ); ?></a>
					<?php else : ?>
						<table class="sk-orders-table">
							<thead>
								<tr>
									<th><?php _e( 'Pedido', 'skincare' ); ?></th>
									<th><?php _e( 'Fecha', 'skincare' ); ?></th>
									<th><?php _e( 'Estado', 'skincare' ); ?></th>
									<th><?php _e( 'Total', 'skincare' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $orders as $order ) : ?>
									<tr>
										<td>#<?php echo $order->get_order_number(); ?></td>
										<td><?php echo wc_format_datetime( $order->get_date_created() ); ?></td>
										<td><?php echo wc_get_order_status_name( $order->get_status() ); ?></td>
										<td><?php echo $order->get_formatted_order_total(); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>

				<div id="tab-address" class="sk-tab-pane">
					<h4><?php _e( 'Dirección de Facturación', 'skincare' ); ?></h4>
					<address><?php echo $address ? wp_kses_post( $address ) : __( 'No has configurado una dirección.', 'skincare' ); ?></address>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="btn sk-btn-small"><?php _e( 'Editar', 'skincare' ); ?></a>
				</div>
			</div>
		</div>

		<style>
			.sk-account-dashboard { max-width: 800px; margin: 0 auto; }
			.sk-account-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
			.sk-account-tabs { display: flex; gap: 15px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
			.sk-tab-btn { background: none; border: none; padding: 10px 0; font-weight: bold; color: #aaa; cursor: pointer; border-bottom: 2px solid transparent; }
			.sk-tab-btn.active { color: #0F3062; border-color: #0F3062; }
			.sk-tab-pane { display: none; }
			.sk-tab-pane.active { display: block; animation: fadeIn 0.3s; }
			.sk-orders-table { width: 100%; border-collapse: collapse; }
			.sk-orders-table th, .sk-orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
		</style>

		<script>
		jQuery(document).ready(function($){
			$('.sk-tab-btn').click(function(){
				$('.sk-tab-btn').removeClass('active');
				$('.sk-tab-pane').removeClass('active');
				$(this).addClass('active');
				$($(this).data('target')).addClass('active');
			});
		});
		</script>
		<?php
	}
}
