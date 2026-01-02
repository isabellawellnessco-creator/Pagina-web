<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Product_Grid extends Widget_Base {

	public function get_name() {
		return 'sk_product_grid';
	}

	public function get_title() {
		return __( 'SK Product Grid', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Query', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Limit', 'skincare' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'skincare' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_product_categories(),
				'multiple' => true,
			]
		);

		$this->end_controls_section();
	}

	private function get_product_categories() {
		$terms = get_terms( 'product_cat', [ 'hide_empty' => false ] );
		$options = [];
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}
		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'post_type' => 'product',
			'posts_per_page' => $settings['posts_per_page'],
		];

		if ( ! empty( $settings['category'] ) ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $settings['category'],
				]
			];
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			echo '<ul class="products columns-4 sk-product-grid">';
			while ( $query->have_posts() ) {
				$query->the_post();
				global $product;

				// Standard WC Loop Item
				?>
				<li <?php wc_product_class( '', $product ); ?>>
					<?php
					/**
					 * Hook: woocommerce_before_shop_loop_item.
					 *
					 * @hooked woocommerce_template_loop_product_link_open - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item' );

					/**
					 * Hook: woocommerce_before_shop_loop_item_title.
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 * @hooked woocommerce_template_loop_product_thumbnail - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item_title' );

					/**
					 * Hook: woocommerce_shop_loop_item_title.
					 *
					 * @hooked woocommerce_template_loop_product_title - 10
					 */
					do_action( 'woocommerce_shop_loop_item_title' );

					/**
					 * Hook: woocommerce_after_shop_loop_item_title.
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );

					// Custom: Grid Swatches
					if ( $product->is_type( 'variable' ) ) {
						$variations = $product->get_available_variations();
						// Find color attribute
						$color_attr = null;
						foreach ( $product->get_variation_attributes() as $attr_name => $options ) {
							if ( strpos( $attr_name, 'color' ) !== false || strpos( $attr_name, 'shade' ) !== false ) {
								$color_attr = $attr_name;
								break;
							}
						}

						if ( $color_attr ) {
							echo '<div class="sk-grid-swatches">';
							$count = 0;
							foreach ( $variations as $variation ) {
								if ( $count >= 4 ) break; // Limit
								$attr_val = $variation['attributes'][ 'attribute_' . $color_attr ];
								// Map val to hex/image (simplified for demo: random color or standard map)
								// In real usage, you'd pull term meta. Here we assume term name is color or use placeholder.
								$color_code = '#ccc'; // Default
								if ( strpos( strtolower( $attr_val ), 'red' ) !== false ) $color_code = '#e5757e';
								if ( strpos( strtolower( $attr_val ), 'pink' ) !== false ) $color_code = 'pink';
								if ( strpos( strtolower( $attr_val ), 'blue' ) !== false ) $color_code = '#0F3062';

								$img_src = $variation['image']['src'];

								echo '<span class="sk-grid-swatch" style="background-color:' . esc_attr( $color_code ) . ';" data-src="' . esc_url( $img_src ) . '" title="' . esc_attr( $attr_val ) . '"></span>';
								$count++;
							}
							if ( count( $variations ) > 4 ) {
								echo '<span class="sk-more-swatches">+' . ( count( $variations ) - 4 ) . '</span>';
							}
							echo '</div>';
						}
					}

					/**
					 * Hook: woocommerce_after_shop_loop_item.
					 *
					 * @hooked woocommerce_template_loop_product_link_close - 5
					 * @hooked woocommerce_template_loop_add_to_cart - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' );
					?>
				</li>
				<?php
			}
			echo '</ul>';
			wp_reset_postdata();
		}
	}
}
