<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Brand_Slider extends Widget_Base {
	public function get_name() { return 'sk_brand_slider'; }
	public function get_title() { return __( 'SK Brand Slider', 'skincare' ); }
	public function get_icon() { return 'eicon-carousel'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Brands' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'logo', [ 'label' => 'Logo', 'type' => Controls_Manager::MEDIA ] );
		$repeater->add_control( 'link', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
		$this->add_control( 'brands', [ 'label' => 'Brands', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-brand-slider">';
		foreach ( $settings['brands'] as $brand ) {
			echo '<div class="sk-brand-item">';
			echo '<a href="' . esc_url( $brand['link']['url'] ) . '">';
			echo '<img src="' . esc_url( $brand['logo']['url'] ) . '" alt="Brand">';
			echo '</a>';
			echo '</div>';
		}
		echo '</div>';
	}
}
