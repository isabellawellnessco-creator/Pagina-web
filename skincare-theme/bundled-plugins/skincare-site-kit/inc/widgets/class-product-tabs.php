<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Product_Tabs extends Shortcode_Renderer {
	public function get_name() { return 'sk_product_tabs'; }
	public function get_title() { return __( 'SK Product Tabs', 'skincare' ); }
	public function get_icon() { return 'eicon-tabs'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function render() {
		global $product;
		if ( ! $product ) return;

		$description = $product->get_description();
		// In a real scenario, we'd use Custom Fields for Ingredients/How to Use
		// For now, we simulate this data or use attributes
		$ingredients = $product->get_attribute('ingredients') ?: 'Aqua, Glycerin...';
		$how_to_use = $product->get_attribute('how_to_use') ?: 'Aplicar sobre la piel limpia...';

		$tabs = [
			'description' => [ 'title' => __( 'Descripción', 'skincare' ), 'content' => $description ],
			'ingredients' => [ 'title' => __( 'Ingredientes', 'skincare' ), 'content' => $ingredients ],
			'how_to_use'  => [ 'title' => __( 'Modo de uso', 'skincare' ), 'content' => $how_to_use ],
		];

		echo '<div class="sk-product-tabs">';
		foreach ( $tabs as $key => $tab ) {
			echo '<div class="sk-tab-item">';
			echo '<button class="sk-tab-toggle">' . esc_html( $tab['title'] ) . '<i class="eicon-plus"></i></button>';
			echo '<div class="sk-tab-content">' . wp_kses_post( $tab['content'] ) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}
