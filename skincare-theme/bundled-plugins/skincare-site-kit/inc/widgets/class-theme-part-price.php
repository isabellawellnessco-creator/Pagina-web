<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Theme_Part_Price extends Shortcode_Renderer {
	public function get_name() { return 'sk_theme_part_price'; }
	public function get_title() { return __( 'Precio de plantilla', 'skincare' ); }
	public function get_icon() { return 'eicon-product-price'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function render() {
		global $product;
		if ( $product ) {
			echo '<p class="price">' . $product->get_price_html() . '</p>';
		} else {
			echo '<p class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>100.00</bdi></span></p>';
		}
	}
}
