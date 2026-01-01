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
				<!-- Filter Form -->
				<form id="sk-filter-form">
					<label><input type="checkbox" name="brand[]" value="cosrx"> COSRX</label>
					<label><input type="checkbox" name="brand[]" value="anua"> Anua</label>
					<label><input type="checkbox" name="brand[]" value="beauty-of-joseon"> Beauty of Joseon</label>

					<div class="sk-price-filter">
						<h4><?php _e( 'Price', 'skincare' ); ?></h4>
						<input type="range" name="max_price" min="0" max="100" value="100" oninput="this.nextElementSibling.value = '£' + this.value">
						<output>£100</output>
					</div>
				</form>
			</div>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$('#sk-filter-form input').on('change', function() {
				// Collect data
				var data = $('#sk-filter-form').serialize();

				// In a real WP instance, we would fire an AJAX request to 'sk_filter_products'
				// and replace the .products grid.
				console.log('Filter change:', data);

				// Simulate loading
				$('.sk-product-grid').css('opacity', 0.5);
				setTimeout(function() {
					$('.sk-product-grid').css('opacity', 1);
					// Here we would replace HTML
				}, 500);
			});
		});
		</script>
		<?php
	}
}
