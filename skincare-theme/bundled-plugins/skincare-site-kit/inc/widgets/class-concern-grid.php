<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Concern_Grid extends Widget_Base {
	public function get_name() { return 'sk_concern_grid'; }
	public function get_title() { return __( 'Compra por necesidad', 'skincare' ); }
	public function get_icon() { return 'eicon-gallery-grid'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$default_images = [
			$theme_assets . 'placeholder-concern.svg',
			$theme_assets . 'placeholder-concern.svg',
			$theme_assets . 'placeholder-concern.svg',
			$theme_assets . 'placeholder-concern.svg',
		];

		$this->start_controls_section( 'content', [ 'label' => 'Necesidades' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image',
			[
				'label' => 'Imagen',
				'type' => Controls_Manager::MEDIA,
				'description' => __( 'Recomendado: 600x750 (vertical).', 'skincare' ),
				'default' => [
					'url' => $default_images[0],
				],
			]
		);
		$repeater->add_control( 'title', [ 'label' => 'Necesidad', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'link', [ 'label' => 'Enlace', 'type' => Controls_Manager::URL ] );
		$this->add_control(
			'items',
			[
				'label' => 'Elementos',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => [ 'url' => $default_images[0] ],
						'title' => __( 'Hidratación', 'skincare' ),
						'link' => [ 'url' => '/tienda/' ],
					],
					[
						'image' => [ 'url' => $default_images[1] ],
						'title' => __( 'Acné', 'skincare' ),
						'link' => [ 'url' => '/tienda/' ],
					],
					[
						'image' => [ 'url' => $default_images[2] ],
						'title' => __( 'Manchas', 'skincare' ),
						'link' => [ 'url' => '/tienda/' ],
					],
					[
						'image' => [ 'url' => $default_images[3] ],
						'title' => __( 'Sensibilidad', 'skincare' ),
						'link' => [ 'url' => '/tienda/' ],
					],
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$fallback_image = get_stylesheet_directory_uri() . '/assets/images/placeholder-concern.svg';
		echo '<div class="sk-concern-grid">';
		foreach ( $settings['items'] as $item ) {
			$image_url = ! empty( $item['image']['url'] ) ? $item['image']['url'] : $fallback_image;
			$link_url = ! empty( $item['link']['url'] ) ? $item['link']['url'] : '#';
			echo '<a href="' . esc_url( $link_url ) . '" class="sk-concern-item">';
			echo '<div class="sk-concern-image" style="background-image: url(' . esc_url( $image_url ) . ')"></div>';
			echo '<span class="sk-concern-title">' . esc_html( $item['title'] ) . '</span>';
			echo '</a>';
		}
		echo '</div>';
	}
}
