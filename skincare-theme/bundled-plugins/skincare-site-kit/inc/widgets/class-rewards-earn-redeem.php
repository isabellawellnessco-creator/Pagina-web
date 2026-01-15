<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Rewards_Earn_Redeem extends Shortcode_Renderer {
	public function get_name() { return 'sk_rewards_earn_redeem'; }
	public function get_title() { return __( 'Recompensas: gana/canjea', 'skincare' ); }
	public function get_icon() { return 'eicon-price-list'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'earn', [ 'label' => 'Formas de ganar' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'icon', [ 'label' => 'Icono', 'type' => Controls_Manager::ICONS ] );
		$repeater->add_control( 'title', [ 'label' => 'Título', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'points', [ 'label' => 'Puntos', 'type' => Controls_Manager::TEXT ] );
		$this->add_control( 'earn_items', [ 'label' => 'Elementos', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-rewards-grid">';
		foreach ( $settings['earn_items'] as $item ) {
			echo '<div class="sk-reward-card">';
			\Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
			echo '<h4>' . esc_html( $item['title'] ) . '</h4>';
			echo '<span class="points">' . esc_html( $item['points'] ) . '</span>';
			echo '</div>';
		}
		echo '</div>';
	}
}
