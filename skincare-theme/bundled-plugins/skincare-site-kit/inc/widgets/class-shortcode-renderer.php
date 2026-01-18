<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Shortcode_Renderer extends \Elementor\Widget_Base {
	public function get_data( $key = null ) {
		$data = parent::get_data( $key );

		if ( 'settings' === $key && ! is_array( $data ) ) {
			return [];
		}

		return $data;
	}

	public function get_init_settings() {
		$settings = parent::get_init_settings();

		return is_array( $settings ) ? $settings : [];
	}

	public function render_shortcode( $settings = [] ) {
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		if ( method_exists( $this, 'set_settings' ) ) {
			$this->set_settings( $settings );
		}

		$this->render();
	}
}
