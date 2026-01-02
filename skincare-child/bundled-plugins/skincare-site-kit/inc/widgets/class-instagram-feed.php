<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Instagram_Feed extends Widget_Base {
	public function get_name() { return 'sk_instagram_feed'; }
	public function get_title() { return __( 'SK Instagram Feed', 'skincare' ); }
	public function get_icon() { return 'eicon-instagram-gallery'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Images' ] );
		$this->add_control( 'gallery', [ 'label' => 'Add Images', 'type' => Controls_Manager::GALLERY ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-insta-feed">';
		echo '<h3>' . __( 'Follow us @skincupid', 'skincare' ) . '</h3>';
		echo '<div class="sk-insta-grid">';
		foreach ( $settings['gallery'] as $image ) {
			echo '<div class="sk-insta-item" style="background-image: url(' . esc_url( $image['url'] ) . ')"></div>';
		}
		echo '</div></div>';
	}
}
