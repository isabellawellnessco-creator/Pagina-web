<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Skincare\SiteKit\Modules\Wishlist;

class Sk_Wishlist_Grid_Section extends Widget_Base {
	public function get_name() { return 'sk_wishlist_grid_section'; }
	public function get_title() { return __( 'Wishlist: Grid Section', 'skincare' ); }
	public function get_icon() { return 'eicon-heart'; }
	public function get_categories() { return [ 'skincare-wishlist' ]; }

	protected function render() {
		// Get actual wishlist items
		$wishlist_items = Wishlist::get_wishlist_items();

		if ( ! empty( $wishlist_items ) ) {
			$args = [
				'post_type'      => 'product',
				'post__in'       => $wishlist_items,
				'posts_per_page' => -1,
				'orderby'        => 'post__in',
			];
			$products = new \WP_Query( $args );
		} else {
			$products = false;
		}

		?>
		<div class="sk-wishlist-section">
			<div class="sk-wishlist-header">
				<h1 class="sk-section-title"><?php esc_html_e( 'My Wishlist', 'skincare' ); ?></h1>
				<div class="sk-wishlist-actions">
					<button class="sk-btn sk-btn--outline sk-share-wishlist">
						<i class="eicon-share-arrow"></i> <?php esc_html_e( 'Share', 'skincare' ); ?>
					</button>
				</div>
			</div>

			<div class="sk-wishlist-grid">
				<?php if ( $products && $products->have_posts() ) : ?>
					<?php while ( $products->have_posts() ) : $products->the_post(); ?>
						<?php global $product; ?>
						<div class="sk-wishlist-item sk-product-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
							<div class="sk-product-image">
								<?php echo $product->get_image(); ?>
								<button class="sk-remove-from-wishlist" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Remove', 'skincare' ); ?>">&times;</button>
							</div>
							<div class="sk-product-details">
								<h3 class="sk-product-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="sk-product-price"><?php echo $product->get_price_html(); ?></div>

								<?php if ( $product->is_in_stock() ) : ?>
									<button class="sk-btn sk-btn--primary sk-add-to-cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
										<?php esc_html_e( 'Add to Cart', 'skincare' ); ?>
									</button>
								<?php else : ?>
									<button class="sk-btn sk-btn--disabled" disabled>
										<?php esc_html_e( 'Out of Stock', 'skincare' ); ?>
									</button>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="sk-empty-state">
						<p><?php esc_html_e( 'Your wishlist is empty.', 'skincare' ); ?></p>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="sk-btn sk-btn--primary"><?php esc_html_e( 'Go Shopping', 'skincare' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			// Handle removal
			$('.sk-remove-from-wishlist').on('click', function(e) {
				e.preventDefault();
				var btn = $(this);
				var productId = btn.data('product-id');
				var item = btn.closest('.sk-wishlist-item');

				$.ajax({
					url: sk_vars.ajax_url,
					type: 'POST',
					data: {
						action: 'sk_remove_from_wishlist',
						product_id: productId,
						nonce: sk_vars.nonce
					},
					beforeSend: function() {
						item.css('opacity', '0.5');
					},
					success: function(response) {
						if (response.success) {
							item.fadeOut(function() {
								$(this).remove();
								if ($('.sk-wishlist-item').length === 0) {
									location.reload(); // Reload to show empty state
								}
							});
						} else {
							alert(response.data.message);
							item.css('opacity', '1');
						}
					}
				});
			});

			// Handle add to cart
			$('.sk-add-to-cart').on('click', function(e) {
				e.preventDefault();
				var btn = $(this);
				var productId = btn.data('product_id');

				btn.addClass('is-loading');

				$.ajax({
					url: sk_vars.ajax_url,
					type: 'POST',
					data: {
						action: 'woocommerce_ajax_add_to_cart',
						product_id: productId,
						quantity: 1
					},
					success: function(response) {
						btn.removeClass('is-loading');
						$(document.body).trigger('wc_fragment_refresh');
						// Optional: Show success message or open cart drawer
						if (typeof skOpenCartDrawer === 'function') {
							skOpenCartDrawer();
						}
					},
					error: function() {
						btn.removeClass('is-loading');
					}
				});
			});
		});
		</script>
		<?php
	}
}
