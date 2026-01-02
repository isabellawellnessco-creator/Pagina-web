<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Theme_Part_Title extends Widget_Base {
	public function get_name() { return 'sk_theme_part_title'; }
	public function get_title() { return __( 'SK Part Title', 'skincare' ); }
	public function get_icon() { return 'eicon-post-title'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function render() {
		if ( is_singular( 'product' ) ) {
			the_title( '<h1 class="product_title entry-title">', '</h1>' );
		} else {
			echo '<h2>' . __( 'Product Title Example', 'skincare' ) . '</h2>';
		}
	}
}
