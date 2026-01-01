<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Product_Gallery extends Widget_Base {
	public function get_name() { return 'sk_product_gallery'; }
	public function get_title() { return __( 'SK Product Gallery', 'skincare' ); }
	public function get_icon() { return 'eicon-gallery-grid'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function render() {
		global $product;
		if ( ! $product ) return;

		$attachment_ids = $product->get_gallery_image_ids();
		$main_image = $product->get_image_id();

		array_unshift( $attachment_ids, $main_image );
		$attachment_ids = array_unique( $attachment_ids );

		?>
		<div class="sk-product-gallery-wrapper">
			<div class="sk-main-slider">
				<?php foreach ( $attachment_ids as $id ) : ?>
					<div class="sk-gallery-item">
						<?php echo wp_get_attachment_image( $id, 'large' ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="sk-thumb-slider">
				<?php foreach ( $attachment_ids as $id ) : ?>
					<div class="sk-thumb-item">
						<?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<style>
			/* Basic styles to simulate slider layout if JS fails */
			.sk-main-slider img { width: 100%; height: auto; border-radius: 12px; }
			.sk-thumb-slider { display: flex; gap: 10px; margin-top: 10px; }
			.sk-thumb-item img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent; }
			.sk-thumb-item:hover img { border-color: #E5757E; }
		</style>

		<script>
		jQuery(document).ready(function($){
			// Simple thumbnail click switch for demo
			$('.sk-thumb-item').click(function(){
				var index = $(this).index();
				// In a real implementation with Swiper, we'd call slideTo(index)
				var src = $(this).find('img').attr('src').replace('-150x150', ''); // Hacky URL replacement
				$('.sk-main-slider .sk-gallery-item').hide().eq(index).fadeIn();
			});
			// Show first
			$('.sk-main-slider .sk-gallery-item').hide().first().show();
		});
		</script>
		<?php
	}
}
