<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Hero_Slider extends Widget_Base {

	public function get_name() {
		return 'sk_hero_slider';
	}

	public function get_title() {
		return __( 'SK Hero Slider', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'skincare' ),
				'type' => Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Slide Title', 'skincare' ),
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'skincare' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Shop Now', 'skincare' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'skincare' ),
				'type' => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'skincare' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Simple HTML structure for a slider (would need JS init in assets/js/site-kit.js)
		echo '<div class="sk-hero-slider">';
		foreach ( $settings['slides'] as $slide ) {
			echo '<div class="sk-slide" style="background-image: url(' . esc_url( $slide['image']['url'] ) . ')">';
			echo '<div class="sk-slide-content">';
			if ( $slide['subtitle'] ) echo '<span class="sk-subtitle">' . esc_html( $slide['subtitle'] ) . '</span>';
			echo '<h2 class="sk-title">' . esc_html( $slide['title'] ) . '</h2>';
			echo '<a href="' . esc_url( $slide['link']['url'] ) . '" class="btn sk-btn">' . esc_html( $slide['button_text'] ) . '</a>';
			echo '</div>'; // content
			echo '</div>'; // slide
		}
		echo '</div>';
	}
}
