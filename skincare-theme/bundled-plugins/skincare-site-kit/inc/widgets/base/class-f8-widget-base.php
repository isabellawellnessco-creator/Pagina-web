<?php
namespace Skincare\SiteKit\Widgets\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class F8_Widget_Base extends \Elementor\Widget_Base {

	public function get_categories() {
		return [ 'f8-sections' ];
	}

	public function get_style_depends() {
		return [ 'sk-f8-bundle' ];
	}

	public function get_script_depends() {
		return [ 'sk-f8-bundle' ];
	}

	protected function register_common_controls() {
		$this->start_controls_section(
			'section_style_common',
			[
				'label' => __( 'Estilos F8', 'skincare' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'f8_custom_class',
			[
				'label' => __( 'Clase CSS Adicional', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();
	}
}
