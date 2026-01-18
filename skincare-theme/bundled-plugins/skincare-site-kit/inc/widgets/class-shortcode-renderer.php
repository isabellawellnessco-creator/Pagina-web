<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Shortcode_Renderer extends \Elementor\Widget_Base {
	public function get_data( $key = null ) {
		if ( 'settings' === $key ) {
			if ( ! isset( $this->data['settings'] ) || ! is_array( $this->data['settings'] ) ) {
				$this->data['settings'] = [];
			}

			return $this->data['settings'];
		}

		return parent::get_data( $key );
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
