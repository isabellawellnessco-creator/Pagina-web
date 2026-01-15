<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Icon_Box_Grid extends Shortcode_Renderer {
	public function get_name() { return 'sk_icon_box_grid'; }
	public function get_title() { return __( 'SK Icon Box Grid', 'skincare' ); }
	public function get_icon() { return 'eicon-icon-box'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Elementos' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'icon', [ 'label' => 'Icono', 'type' => Controls_Manager::ICONS ] );
		$repeater->add_control( 'title', [ 'label' => 'Título', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'desc', [ 'label' => 'Descripción', 'type' => Controls_Manager::TEXTAREA ] );
		$this->add_control( 'items', [ 'label' => 'Elementos', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-icon-box-grid">';
		foreach ( $settings['items'] as $item ) {
			echo '<div class="sk-icon-box">';
			echo '<div class="sk-ib-icon">';
			\Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
			echo '</div>';
			echo '<h4>' . esc_html( $item['title'] ) . '</h4>';
			echo '<p>' . esc_html( $item['desc'] ) . '</p>';
			echo '</div>';
		}
		echo '</div>';
	}
}
