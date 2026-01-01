<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Rewards_Castle extends Widget_Base {

	public function get_name() { return 'sk_rewards_castle'; }
	public function get_title() { return __( 'SK Rewards Castle', 'skincare' ); }
	public function get_icon() { return 'eicon-image-rollover'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content_section', [ 'label' => __( 'Tiers', 'skincare' ), 'tab' => Controls_Manager::TAB_CONTENT ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'title', [ 'label' => 'Tier Name', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'points', [ 'label' => 'Points Required', 'type' => Controls_Manager::NUMBER ] );
		$repeater->add_control( 'benefits', [ 'label' => 'Benefits', 'type' => Controls_Manager::TEXTAREA ] );
		$this->add_control( 'tiers', [ 'label' => 'Tiers', 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();

		// Styles
		$this->start_controls_section( 'style_section', [ 'label' => 'Colors', 'tab' => Controls_Manager::TAB_STYLE ] );
		$this->add_control( 'active_color', [ 'label' => 'Active Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .sk-castle-tier.active .tier-icon' => 'background-color: {{VALUE}};', '{{WRAPPER}} .progress-bar' => 'background-color: {{VALUE}};' ] ] );
		$this->add_control( 'inactive_color', [ 'label' => 'Inactive Color', 'type' => Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .tier-icon' => 'background-color: {{VALUE}};' ] ] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$user_points = is_user_logged_in() ? intval( get_user_meta( get_current_user_id(), '_sk_rewards_points', true ) ) : 0;

		echo '<div class="sk-rewards-castle-container"><div class="sk-castle-path">';
		foreach ( $settings['tiers'] as $index => $tier ) {
			$is_active = $user_points >= $tier['points'] ? 'active' : '';
			echo '<div class="sk-castle-tier ' . esc_attr( $is_active ) . '">';
			echo '<div class="tier-icon"><i class="eicon-crown"></i></div>';
			echo '<div class="tier-info"><h3 class="tier-title">' . esc_html( $tier['title'] ) . '</h3><span class="tier-points">' . esc_html( $tier['points'] ) . '+</span></div>';
			echo '</div>';
			if ( $index < count( $settings['tiers'] ) - 1 ) {
				echo '<div class="sk-castle-connector"><div class="progress-bar" style="width: ' . ($is_active ? '100%' : '0%') . '"></div></div>';
			}
		}
		echo '</div></div>';
	}
}
