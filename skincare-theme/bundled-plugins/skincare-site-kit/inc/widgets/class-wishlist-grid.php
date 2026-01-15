<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Wishlist_Grid extends Widget_Base {
	public function get_name() { return 'sk_wishlist_grid'; }
	public function get_title() { return __( 'Lista de deseos', 'skincare' ); }
	public function get_icon() { return 'eicon-heart'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! class_exists( '\Skincare\SiteKit\Modules\Wishlist' ) ) return;

		$items = \Skincare\SiteKit\Modules\Wishlist::get_wishlist_items();

		if ( empty( $items ) ) {
			echo '<p>' . __( 'Tu lista de deseos está vacía.', 'skincare' ) . '</p>';
			return;
		}

		$limit = min( 50, count( $items ) );
		$args = [
			'post_type' => 'product',
			'post__in' => $items,
			'posts_per_page' => $limit ? $limit : 1,
		];

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			echo '<ul class="products columns-4 sk-wishlist-grid">';
			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			}
			echo '</ul>';
			wp_reset_postdata();
		}
	}
}
