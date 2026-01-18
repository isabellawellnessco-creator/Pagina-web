<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Shortcode_Renderer extends \Elementor\Widget_Base {
	public function render_shortcode( $settings = [] ) {
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		if ( method_exists( $this, 'set_data' ) ) {
			$this->set_data( 'settings', $settings );
		}

		$this->render();
	}
}
