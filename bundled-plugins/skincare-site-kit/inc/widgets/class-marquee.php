<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;

class Marquee extends Widget_Base {
	public function get_name() { return 'sk_marquee'; }
	public function get_title() { return __( 'SK Marquee', 'skincare' ); }
	public function get_icon() { return 'eicon-text-area'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		// Content
		$this->start_controls_section( 'content_section', [ 'label' => 'Content' ] );
		$this->add_control( 'text', [
			'label' => 'Text',
			'type' => Controls_Manager::TEXT,
			'default' => 'Free Delivery on UK orders over £25 🚚 • 10% OFF first order with code: HELLO10 ✨',
		] );
		$this->end_controls_section();

		// Style
		$this->start_controls_section( 'style_section', [ 'label' => 'Style', 'tab' => Controls_Manager::TAB_STYLE ] );

		$this->add_control( 'text_color', [
			'label' => 'Text Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-marquee-container' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'bg_color', [
			'label' => 'Background Color',
			'type' => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .sk-marquee-container' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .sk-marquee-container',
			]
		);

		$this->add_control( 'padding', [
			'label' => 'Padding',
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [ '{{WRAPPER}} .sk-marquee-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-marquee-container"><div class="sk-marquee-content">';
		for($i=0; $i<10; $i++) {
			echo '<span>' . esc_html( $settings['text'] ) . '</span>';
		}
		echo '</div></div>';
	}
}
