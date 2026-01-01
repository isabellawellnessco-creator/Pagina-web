<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;

class Theme_Part_Add_To_Cart extends Widget_Base {
	public function get_name() { return 'sk_theme_part_add_to_cart'; }
	public function get_title() { return __( 'SK Part Add to Cart', 'skincare' ); }
	public function get_icon() { return 'eicon-product-add-to-cart'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'style_swatches', [ 'label' => 'Swatches', 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'swatch_size', [
			'label' => 'Swatch Size',
			'type' => Controls_Manager::SLIDER,
			'range' => [ 'px' => [ 'min' => 20, 'max' => 60 ] ],
			'selectors' => [ '{{WRAPPER}} .sk-swatch-visual' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'swatch_radius', [
			'label' => 'Border Radius',
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [ '{{WRAPPER}} .sk-swatch-visual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'swatch_border',
				'selector' => '{{WRAPPER}} .sk-swatch-visual',
			]
		);

		$this->add_control( 'selected_border_color', [
			'label' => 'Selected Border Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-swatch-label input:checked + .sk-swatch-visual' => 'border-color: {{VALUE}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		global $product;
		if ( $product ) {
			woocommerce_template_single_add_to_cart();
		} else {
			echo '<button class="single_add_to_cart_button button alt">' . __( 'Add to cart', 'woocommerce' ) . '</button>';
		}
	}
}
