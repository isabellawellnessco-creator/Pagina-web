<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Concern_Grid extends Widget_Base {
	public function get_name() { return 'sk_concern_grid'; }
	public function get_title() { return __( 'SK Shop by Concern', 'skincare' ); }
	public function get_icon() { return 'eicon-gallery-grid'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Concerns' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'image', [ 'label' => 'Image', 'type' => Controls_Manager::MEDIA ] );
		$repeater->add_control( 'title', [ 'label' => 'Concern', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'link', [ 'label' => 'Link', 'type' => Controls_Manager::URL ] );
		$this->add_control( 'items', [ 'label' => 'Items', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-concern-grid">';
		foreach ( $settings['items'] as $item ) {
			echo '<a href="' . esc_url( $item['link']['url'] ) . '" class="sk-concern-item">';
			echo '<div class="sk-concern-image" style="background-image: url(' . esc_url( $item['image']['url'] ) . ')"></div>';
			echo '<span class="sk-concern-title">' . esc_html( $item['title'] ) . '</span>';
			echo '</a>';
		}
		echo '</div>';
	}
}
