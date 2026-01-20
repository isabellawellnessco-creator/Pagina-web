<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class F8_ProductSteps extends F8_Widget_Base {
	public function get_name() { return 'f8_product_steps'; }
	public function get_title() { return __( 'F8 Product Steps', 'skincare' ); }
	public function get_icon() { return 'eicon-number'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Pasos' ] );

		$repeater = new Repeater();
		$repeater->add_control( 'title', [ 'label' => 'Título', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'desc', [ 'label' => 'Descripción', 'type' => Controls_Manager::TEXTAREA ] );
		$repeater->add_control( 'image', [ 'label' => 'Imagen', 'type' => Controls_Manager::MEDIA ] );

		$this->add_control(
			'steps',
			[
				'label' => 'Pasos',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="f8-steps ' . esc_attr( $settings['f8_custom_class'] ) . '">';
		foreach ( $settings['steps'] as $step ) {
			echo '<div class="f8-step-item">';
			if ( $step['image']['url'] ) echo '<img src="' . esc_url( $step['image']['url'] ) . '" alt="">';
			echo '<h3>' . esc_html( $step['title'] ) . '</h3>';
			echo '<p>' . esc_html( $step['desc'] ) . '</p>';
			echo '</div>';
		}
		echo '</div>';
	}
}

class F8_Embed extends F8_Widget_Base {
	public function get_name() { return 'f8_embed'; }
	public function get_title() { return __( 'F8 Embed', 'skincare' ); }
	public function get_icon() { return 'eicon-code'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'HTML' ] );
		$this->add_control( 'html', [ 'label' => 'Código Embed', 'type' => Controls_Manager::TEXTAREA ] );
		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="f8-embed ' . esc_attr( $settings['f8_custom_class'] ) . '">';
		echo $settings['html']; // Intentionally unescaped for embeds
		echo '</div>';
	}
}

class F8_Grid extends F8_Widget_Base {
	public function get_name() { return 'f8_grid'; }
	public function get_title() { return __( 'F8 Grid', 'skincare' ); }
	public function get_icon() { return 'eicon-gallery-grid'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Grid Items' ] );
		$repeater = new Repeater();
		$repeater->add_control( 'content', [ 'label' => 'Contenido', 'type' => Controls_Manager::WYSIWYG ] );
		$this->add_control( 'items', [ 'type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls() ] );
		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="f8-grid ' . esc_attr( $settings['f8_custom_class'] ) . '">';
		foreach ( $settings['items'] as $item ) {
			echo '<div class="f8-grid-item">' . $item['content'] . '</div>';
		}
		echo '</div>';
	}
}

class F8_Account_Dashboard extends F8_Widget_Base {
	public function get_name() { return 'f8_account_dashboard'; }
	public function get_title() { return __( 'F8 Account Dashboard', 'skincare' ); }
	public function get_icon() { return 'eicon-user'; }

	protected function render() {
		echo '<div class="f8-account-dashboard">';
		echo do_shortcode( '[woocommerce_my_account]' );
		echo '</div>';
	}
}
