<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Brand_Slider extends Widget_Base {
	public function get_name() { return 'sk_brand_slider'; }
	public function get_title() { return __( 'Carrusel de marcas', 'skincare' ); }
	public function get_icon() { return 'eicon-carousel'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$default_logos = [
			$theme_assets . 'placeholder-brand.svg',
			$theme_assets . 'placeholder-brand.svg',
			$theme_assets . 'placeholder-brand.svg',
			$theme_assets . 'placeholder-brand.svg',
			$theme_assets . 'placeholder-brand.svg',
		];

		$this->start_controls_section( 'content', [ 'label' => 'Marcas' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'logo',
			[
				'label' => 'Logo',
				'type' => Controls_Manager::MEDIA,
				'description' => __( 'Recomendado: 320x180 PNG/SVG con fondo transparente.', 'skincare' ),
				'default' => [
					'url' => $default_logos[0],
				],
			]
		);
		$repeater->add_control( 'link', [ 'label' => 'Enlace', 'type' => Controls_Manager::URL ] );
		$this->add_control(
			'brands',
			[
				'label' => 'Marcas',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'logo' => [ 'url' => $default_logos[0] ], 'link' => [ 'url' => '#' ] ],
					[ 'logo' => [ 'url' => $default_logos[1] ], 'link' => [ 'url' => '#' ] ],
					[ 'logo' => [ 'url' => $default_logos[2] ], 'link' => [ 'url' => '#' ] ],
					[ 'logo' => [ 'url' => $default_logos[3] ], 'link' => [ 'url' => '#' ] ],
					[ 'logo' => [ 'url' => $default_logos[4] ], 'link' => [ 'url' => '#' ] ],
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$fallback_logo = get_stylesheet_directory_uri() . '/assets/images/placeholder-brand.svg';
		echo '<div class="sk-brand-slider">';
		foreach ( $settings['brands'] as $brand ) {
			$logo_url = ! empty( $brand['logo']['url'] ) ? $brand['logo']['url'] : $fallback_logo;
			$link_url = ! empty( $brand['link']['url'] ) ? $brand['link']['url'] : '#';
			echo '<div class="sk-brand-item">';
			echo '<a href="' . esc_url( $link_url ) . '">';
			echo '<img src="' . esc_url( $logo_url ) . '" alt="Marca">';
			echo '</a>';
			echo '</div>';
		}
		echo '</div>';
	}
}
