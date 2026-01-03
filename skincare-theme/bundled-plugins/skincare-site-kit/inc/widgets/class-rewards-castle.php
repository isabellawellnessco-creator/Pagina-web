<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Rewards_Castle extends Widget_Base {

	public function get_name() {
		return 'sk_rewards_castle';
	}

	public function get_title() {
		return __( 'Castillo de recompensas', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Niveles', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Nombre del nivel', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Nombre del nivel', 'skincare' ),
			]
		);

		$repeater->add_control(
			'points',
			[
				'label' => __( 'Puntos requeridos', 'skincare' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$repeater->add_control(
			'benefits',
			[
				'label' => __( 'Beneficios', 'skincare' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Beneficio 1, Beneficio 2', 'skincare' ),
			]
		);

		$this->add_control(
			'tiers',
			[
				'label' => __( 'Niveles', 'skincare' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'title' => 'Cupids', 'points' => 0, 'benefits' => '1 punto por £1' ],
					[ 'title' => 'Cherubs', 'points' => 200, 'benefits' => '1.25 puntos por £1, regalo de cumpleaños' ],
					[ 'title' => 'Angels', 'points' => 500, 'benefits' => '1.5 puntos por £1, envío gratis' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get User Points
		$user_points = 0;
		if ( is_user_logged_in() ) {
			$user_points = get_user_meta( get_current_user_id(), '_sk_rewards_points', true );
			$user_points = intval( $user_points );
		}

		echo '<div class="sk-rewards-castle-container">';
		echo '<div class="sk-castle-path">';

		foreach ( $settings['tiers'] as $index => $tier ) {
			$is_active = $user_points >= $tier['points'] ? 'active' : '';
			$next_tier_points = isset( $settings['tiers'][$index+1] ) ? $settings['tiers'][$index+1]['points'] : 10000;

			// Calculate progress to next tier
			$width = 0;
			if ( $is_active ) {
				$width = 100; // Full width if passed
			}

			echo '<div class="sk-castle-tier ' . esc_attr( $is_active ) . '">';
			echo '<div class="tier-icon"><i class="eicon-crown"></i></div>';
			echo '<div class="tier-info">';
			echo '<h3 class="tier-title">' . esc_html( $tier['title'] ) . '</h3>';
			echo '<span class="tier-points">' . esc_html( $tier['points'] ) . '+ ' . __( 'Puntos', 'skincare' ) . '</span>';
			echo '<div class="tier-benefits-tooltip">';
			echo '<strong>' . __( 'Beneficios:', 'skincare' ) . '</strong>';
			echo '<p>' . esc_html( $tier['benefits'] ) . '</p>';
			echo '</div>';
			echo '</div>'; // info
			echo '</div>'; // tier

			// Connector Line
			if ( $index < count( $settings['tiers'] ) - 1 ) {
				echo '<div class="sk-castle-connector">';
				echo '<div class="progress-bar" style="width: ' . ($is_active ? '100%' : '0%') . '"></div>';
				echo '</div>';
			}
		}

		echo '</div>'; // path
		echo '</div>'; // container
	}
}
