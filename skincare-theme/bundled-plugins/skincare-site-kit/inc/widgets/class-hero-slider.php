<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Hero_Slider extends Shortcode_Renderer {

	public function get_name() {
		return 'sk_hero_slider';
	}

	public function get_title() {
		return __( 'Slider principal', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$default_images = [
			$theme_assets . 'placeholder-hero-1.svg',
			$theme_assets . 'placeholder-hero-2.svg',
			$theme_assets . 'placeholder-hero-3.svg',
		];

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Diapositivas', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Imagen', 'skincare' ),
				'type' => Controls_Manager::MEDIA,
				'description' => __( 'Recomendado: 1600x700 (desktop), 900x1200 (mobile).', 'skincare' ),
				'default' => [
					'url' => $default_images[0],
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Título', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Título del slide', 'skincare' ),
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => __( 'Subtítulo', 'skincare' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Texto del botón', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Comprar ahora', 'skincare' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Enlace', 'skincare' ),
				'type' => Controls_Manager::URL,
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => __( 'Diapositivas', 'skincare' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => [ 'url' => $default_images[0] ],
						'title' => __( 'Rutina esencial', 'skincare' ),
						'subtitle' => __( 'Piel radiante en 3 pasos', 'skincare' ),
						'button_text' => __( 'Comprar ahora', 'skincare' ),
						'link' => [ 'url' => '/shop/' ],
					],
					[
						'image' => [ 'url' => $default_images[1] ],
						'title' => __( 'Favoritos del mes', 'skincare' ),
						'subtitle' => __( 'Top ventas seleccionadas', 'skincare' ),
						'button_text' => __( 'Ver productos', 'skincare' ),
						'link' => [ 'url' => '/shop/' ],
					],
					[
						'image' => [ 'url' => $default_images[2] ],
						'title' => __( 'Nuevas marcas', 'skincare' ),
						'subtitle' => __( 'Descubre lo último en cuidado', 'skincare' ),
						'button_text' => __( 'Explorar', 'skincare' ),
						'link' => [ 'url' => '/shop/' ],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'] ?? [];
		$fallback_image = get_stylesheet_directory_uri() . '/assets/images/placeholder-hero-1.svg';

		// Simple HTML structure for a slider (would need JS init in assets/js/site-kit.js)
		echo '<div class="sk-hero-slider">';
		if ( ! is_array( $slides ) ) {
			$slides = [];
		}
		foreach ( $slides as $slide ) {
			$image_url = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : $fallback_image;
			$link_url = ! empty( $slide['link']['url'] ) ? $slide['link']['url'] : '#';
			echo '<div class="sk-slide" style="background-image: url(' . esc_url( $image_url ) . ')">';
			echo '<div class="sk-slide-content">';
			if ( $slide['subtitle'] ) echo '<span class="sk-subtitle">' . esc_html( $slide['subtitle'] ) . '</span>';
			echo '<h2 class="sk-title">' . esc_html( $slide['title'] ) . '</h2>';
			echo '<a href="' . esc_url( $link_url ) . '" class="btn sk-btn">' . esc_html( $slide['button_text'] ) . '</a>';
			echo '</div>'; // content
			echo '</div>'; // slide
		}
		echo '</div>';
	}
}
