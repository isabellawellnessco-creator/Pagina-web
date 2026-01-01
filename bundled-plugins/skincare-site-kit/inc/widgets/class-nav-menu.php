<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Nav_Menu extends Widget_Base {
	public function get_name() { return 'sk_nav_menu'; }
	public function get_title() { return __( 'SK Nav Menu', 'skincare' ); }
	public function get_icon() { return 'eicon-nav-menu'; }
	public function get_categories() { return [ 'theme-elements' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Menu' ] );
		$menus = wp_get_nav_menus();
		$options = [];
		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}
		$this->add_control( 'menu', [
			'label' => 'Select Menu',
			'type' => Controls_Manager::SELECT,
			'options' => $options,
		] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['menu'] ) ) {
			wp_nav_menu( [ 'menu' => $settings['menu'], 'container_class' => 'sk-nav-menu' ] );
		} else {
			wp_nav_menu( [ 'theme_location' => 'primary', 'container_class' => 'sk-nav-menu' ] );
		}
	}
}
