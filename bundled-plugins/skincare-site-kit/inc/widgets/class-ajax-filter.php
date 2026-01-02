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
		// Fetch Terms
		$brands = get_terms( [
			'taxonomy'   => 'product_brand', // Assumes 'product_brand' or uses fallback
			'hide_empty' => true,
		] );

		if ( is_wp_error( $brands ) || empty( $brands ) ) {
			// Fallback if brand taxonomy doesn't exist yet (using attributes)
			$brands = get_terms( [
				'taxonomy'   => 'pa_brand',
				'hide_empty' => true,
			] );
		}

		// Fallback categories if no brands
		$categories = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0
		] );

		?>
		<div class="sk-sidebar-filters">
			<form id="sk-filter-form">

				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
					<div class="sk-filter-group collapsible open">
						<h4 class="sk-filter-header"><?php _e( 'Category', 'skincare' ); ?> <i class="fas fa-chevron-down"></i></h4>
						<div class="sk-filter-content">
							<?php foreach ( $categories as $cat ) : ?>
								<label>
									<input type="checkbox" name="category[]" value="<?php echo esc_attr( $cat->slug ); ?>">
									<?php echo esc_html( $cat->name ); ?>
									<span class="count">(<?php echo $cat->count; ?>)</span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
					<div class="sk-filter-group collapsible open">
						<h4 class="sk-filter-header"><?php _e( 'Brand', 'skincare' ); ?> <i class="fas fa-chevron-down"></i></h4>
						<div class="sk-filter-content">
							<?php foreach ( $brands as $brand ) : ?>
								<label>
									<input type="checkbox" name="brand[]" value="<?php echo esc_attr( $brand->slug ); ?>">
									<?php echo esc_html( $brand->name ); ?>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="sk-filter-group collapsible open">
					<h4 class="sk-filter-header"><?php _e( 'Price', 'skincare' ); ?> <i class="fas fa-chevron-down"></i></h4>
					<div class="sk-filter-content sk-price-filter">
						<input type="range" name="max_price" min="0" max="200" value="200" oninput="this.nextElementSibling.value = '£' + this.value">
						<output>£200</output>
					</div>
				</div>

				<input type="hidden" name="action" value="sk_filter_products">
			</form>
		</div>

		<style>
			.sk-filter-group { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; }
			.sk-filter-header { cursor: pointer; display: flex; justify-content: space-between; align-items: center; margin: 0; padding: 10px 0; color: #0F3062; font-weight: bold; }
			.sk-filter-content { display: block; }
			.sk-filter-group.collapsed .sk-filter-content { display: none; }
			.sk-filter-header i { transition: transform 0.3s; }
			.sk-filter-group.collapsed .sk-filter-header i { transform: rotate(-90deg); }
			.sk-filter-content label { display: block; margin-bottom: 8px; font-size: 14px; color: #333; cursor: pointer; }
			.sk-filter-content label:hover { color: #E5757E; }
			.sk-filter-content .count { color: #aaa; font-size: 12px; margin-left: 5px; }
		</style>

		<script>
		jQuery(document).ready(function($) {
			// Accordion Logic
			$('.sk-filter-header').click(function() {
				$(this).parent('.sk-filter-group').toggleClass('collapsed');
			});

			// Filter Logic
			$('#sk-filter-form input').on('change input', function() {
				// Debounce for range input
				if ($(this).attr('type') === 'range') {
					// Use a small timeout if needed, or let it fire
				}

				var data = $('#sk-filter-form').serialize();

				$('.sk-product-grid').css('opacity', 0.5);

				$.post(sk_vars.ajax_url, data, function(res) {
					$('.sk-product-grid').css('opacity', 1);
					if(res.success) {
						var $grid = $('.sk-product-grid');
						if ($grid.length) {
							$grid.replaceWith(res.data.html);
						} else {
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
