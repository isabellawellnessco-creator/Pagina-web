<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Shipping_Table extends Shortcode_Renderer {
	public function get_name() { return 'sk_shipping_table'; }
	public function get_title() { return __( 'Tabla de envíos', 'skincare' ); }
	public function get_icon() { return 'eicon-table'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		?>
		<div class="sk-shipping-table-wrapper">
			<table class="sk-shipping-table">
				<thead>
					<tr>
						<th><?php _e( 'Servicio', 'skincare' ); ?></th>
						<th><?php _e( 'Tiempo de Entrega', 'skincare' ); ?></th>
						<th><?php _e( 'Costo', 'skincare' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php _e( 'Estándar', 'skincare' ); ?></td>
						<td>2-4 <?php _e( 'Días Laborables', 'skincare' ); ?></td>
						<td>£3.99 (Gratis > £25)</td>
					</tr>
					<tr>
						<td><?php _e( 'Express', 'skincare' ); ?></td>
						<td>1-2 <?php _e( 'Días Laborables', 'skincare' ); ?></td>
						<td>£5.99</td>
					</tr>
					<tr>
						<td><?php _e( 'Internacional', 'skincare' ); ?></td>
						<td>5-10 <?php _e( 'Días Laborables', 'skincare' ); ?></td>
						<td>£15.00</td>
					</tr>
				</tbody>
			</table>
		</div>
		<style>
			.sk-shipping-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
			.sk-shipping-table th, .sk-shipping-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
			.sk-shipping-table th { background: #F8F5F1; color: #0F3062; }
		</style>
		<?php
	}
}
