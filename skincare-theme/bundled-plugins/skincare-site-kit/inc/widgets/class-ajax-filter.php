<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Ajax_Filter extends Widget_Base {
	public function get_name() { return 'sk_ajax_filter'; }
	public function get_title() { return __( 'Filtro AJAX', 'skincare' ); }
	public function get_icon() { return 'eicon-filter'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		?>
		<div class="sk-sidebar-filters">
			<div class="sk-filter-group">
				<h4><?php _e( 'Marca', 'skincare' ); ?></h4>
				<!-- Filter Form -->
				<form id="sk-filter-form">
					<label><input type="checkbox" name="brand[]" value="cosrx"> COSRX</label>
					<label><input type="checkbox" name="brand[]" value="anua"> Anua</label>
					<label><input type="checkbox" name="brand[]" value="beauty-of-joseon"> Beauty of Joseon</label>

					<div class="sk-price-filter">
						<h4><?php _e( 'Precio', 'skincare' ); ?></h4>
						<input type="range" name="max_price" min="0" max="100" value="100" oninput="this.nextElementSibling.value = '£' + this.value">
						<output>£100</output>
					</div>
					<input type="hidden" name="action" value="sk_filter_products">
				</form>
			</div>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$('#sk-filter-form input').on('change', function() {
				// Collect data
				var data = $('#sk-filter-form').serialize();

				$('.sk-product-grid').css('opacity', 0.5);

				$.post(sk_vars.ajax_url, data, function(res) {
					$('.sk-product-grid').css('opacity', 1);
					if(res.success) {
						// Replace grid
						// Note: This targets the container. The PHP returns the UL.
						// We assume the widget container wraps it. If not, we might replace content.
						// For safety, we look for the grid or the main loop container.
						var $grid = $('.sk-product-grid');
						if ($grid.length) {
							$grid.replaceWith(res.data.html);
						} else {
							// Try finding archive main loop
							$('.sk-main-loop').html(res.data.html);
						}
					}
				});
			});
		});
		</script>
		<?php
	}
}
