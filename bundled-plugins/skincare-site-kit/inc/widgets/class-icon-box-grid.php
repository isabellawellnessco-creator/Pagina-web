<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;

class Icon_Box_Grid extends Widget_Base {
	public function get_name() { return 'sk_icon_box_grid'; }
	public function get_title() { return __( 'SK Icon Box Grid', 'skincare' ); }
	public function get_icon() { return 'eicon-icon-box'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Items' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'icon', [ 'label' => 'Icon', 'type' => Controls_Manager::ICONS ] );
		$repeater->add_control( 'title', [ 'label' => 'Title', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'desc', [ 'label' => 'Description', 'type' => Controls_Manager::TEXTAREA ] );
		$this->add_control( 'items', [ 'label' => 'Items', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();

		// Box Style
		$this->start_controls_section( 'style_box', [ 'label' => 'Box', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'box_bg_color', [
			'label' => 'Background Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-icon-box' => 'background-color: {{VALUE}};' ],
		] );
		$this->add_responsive_control( 'box_padding', [
			'label' => 'Padding',
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [ '{{WRAPPER}} .sk-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .sk-icon-box',
			]
		);
		$this->add_control( 'box_radius', [
			'label' => 'Border Radius',
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [ '{{WRAPPER}} .sk-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );
		$this->end_controls_section();

		// Content Style
		$this->start_controls_section( 'style_content', [ 'label' => 'Content', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'icon_color', [
			'label' => 'Icon Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-ib-icon i' => 'color: {{VALUE}};' ],
		] );
		$this->add_control( 'title_color', [
			'label' => 'Title Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-icon-box h4' => 'color: {{VALUE}};' ],
		] );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .sk-icon-box h4',
			]
		);
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
