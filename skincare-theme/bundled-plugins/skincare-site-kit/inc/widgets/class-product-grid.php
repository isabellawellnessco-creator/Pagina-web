<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Product_Grid extends Shortcode_Renderer {

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
				wc_get_template_part( 'content', 'product' );
			}
			echo '</ul>';
			wp_reset_postdata();
		}
	}
}
