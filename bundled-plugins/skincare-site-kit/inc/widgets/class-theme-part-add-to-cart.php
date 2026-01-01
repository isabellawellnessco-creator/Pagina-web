<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Theme_Part_Add_To_Cart extends Widget_Base {
	public function get_name() { return 'sk_theme_part_add_to_cart'; }
	public function get_title() { return __( 'SK Part Add to Cart', 'skincare' ); }
	public function get_icon() { return 'eicon-product-add-to-cart'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function render() {
		global $product;
		if ( $product ) {
			woocommerce_template_single_add_to_cart();
		} else {
			echo '<button class="single_add_to_cart_button button alt">' . __( 'Add to cart', 'woocommerce' ) . '</button>';
		}
	}
}
