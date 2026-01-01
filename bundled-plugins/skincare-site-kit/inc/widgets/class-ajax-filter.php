<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Ajax_Filter extends Widget_Base {
	public function get_name() { return 'sk_ajax_filter'; }
	public function get_title() { return __( 'SK Ajax Filter', 'skincare' ); }
	public function get_icon() { return 'eicon-filter'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		?>
		<div class="sk-sidebar-filters">
			<div class="sk-filter-group">
				<h4><?php _e( 'Brand', 'skincare' ); ?></h4>
				<!-- Static Demo -->
				<label><input type="checkbox" name="brand" value="cosrx"> COSRX</label>
				<label><input type="checkbox" name="brand" value="anua"> Anua</label>
			</div>
			<div class="sk-filter-group">
				<h4><?php _e( 'Price', 'skincare' ); ?></h4>
				<input type="range" min="0" max="100" />
			</div>
		</div>
		<?php
	}
}
