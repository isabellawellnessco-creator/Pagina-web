<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;

class Store_Locator extends Widget_Base {
	public function get_name() { return 'sk_store_locator'; }
	public function get_title() { return __( 'SK Store Locator', 'skincare' ); }
	public function get_icon() { return 'eicon-google-maps'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		?>
		<div class="sk-store-locator">
			<div class="sk-map-placeholder" style="background:#eee; height:400px; display:flex; align-items:center; justify-content:center; border-radius:12px;">
				<p style="color:#666; font-weight:bold;"><?php _e( 'Mapa Interactivo (Placeholder)', 'skincare' ); ?></p>
			</div>
			<div class="sk-store-list" style="margin-top:30px;">
				<h3><?php _e( 'Nuestras Tiendas', 'skincare' ); ?></h3>
				<div class="sk-store-item" style="padding:15px; border:1px solid #eee; margin-bottom:10px; border-radius:8px;">
					<h4>London Flagship</h4>
					<p>123 Oxford Street, London, W1D 1LT</p>
					<p><strong>Tel:</strong> 020 7946 0123</p>
				</div>
			</div>
		</div>
		<?php
	}
}
