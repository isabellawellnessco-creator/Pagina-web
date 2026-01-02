<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Marquee extends Widget_Base {
	public function get_name() { return 'sk_marquee'; }
	public function get_title() { return __( 'SK Marquee', 'skincare' ); }
	public function get_icon() { return 'eicon-text-area'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Content' ] );
		$this->add_control( 'text', [
			'label' => 'Text',
			'type' => Controls_Manager::TEXT,
			'default' => 'Free Delivery on UK orders over £25 🚚 • 10% OFF first order with code: HELLO10 ✨',
		] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-marquee-container"><div class="sk-marquee-content">';
		// Repeat text enough times to fill screen
		for($i=0; $i<10; $i++) {
			echo '<span>' . esc_html( $settings['text'] ) . '</span>';
		}
		echo '</div></div>';
	}
}
