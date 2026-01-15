<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Instagram_Feed extends Shortcode_Renderer {
	public function get_name() { return 'sk_instagram_feed'; }
	public function get_title() { return __( 'SK Instagram Feed', 'skincare' ); }
	public function get_icon() { return 'eicon-instagram-gallery'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$default_images = [
			$theme_assets . 'placeholder-instagram.svg',
			$theme_assets . 'placeholder-instagram.svg',
			$theme_assets . 'placeholder-instagram.svg',
			$theme_assets . 'placeholder-instagram.svg',
			$theme_assets . 'placeholder-instagram.svg',
			$theme_assets . 'placeholder-instagram.svg',
		];

		$this->start_controls_section( 'content', [ 'label' => 'Imágenes' ] );
		$this->add_control(
			'gallery',
			[
				'label' => 'Añadir imágenes',
				'type' => Controls_Manager::GALLERY,
				'description' => __( 'Recomendado: imágenes cuadradas 800x800.', 'skincare' ),
				'default' => [
					[ 'url' => $default_images[0] ],
					[ 'url' => $default_images[1] ],
					[ 'url' => $default_images[2] ],
					[ 'url' => $default_images[3] ],
					[ 'url' => $default_images[4] ],
					[ 'url' => $default_images[5] ],
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$fallback_image = get_stylesheet_directory_uri() . '/assets/images/placeholder-instagram.svg';
		echo '<div class="sk-insta-feed">';
		echo '<h3>' . __( 'Síguenos en @skincupid', 'skincare' ) . '</h3>';
		echo '<div class="sk-insta-grid">';
		foreach ( $settings['gallery'] as $image ) {
			$image_url = ! empty( $image['url'] ) ? $image['url'] : $fallback_image;
			echo '<div class="sk-insta-item" style="background-image: url(' . esc_url( $image_url ) . ')"></div>';
		}
		echo '</div></div>';
	}
}
