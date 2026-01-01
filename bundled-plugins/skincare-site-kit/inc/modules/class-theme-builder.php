<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Builder {

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
	}

	public static function register_cpt() {
		$args = [
			'label'               => __( 'Theme Parts', 'skincare' ),
			'description'         => __( 'Elementor Templates for Header/Footer/Single', 'skincare' ),
			'labels'              => [
				'name'          => __( 'Theme Parts', 'skincare' ),
				'singular_name' => __( 'Theme Part', 'skincare' ),
				'add_new_item'  => __( 'Add New Theme Part', 'skincare' ),
				'edit_item'     => __( 'Edit Theme Part', 'skincare' ),
			],
			'supports'            => [ 'title', 'editor', 'elementor' ],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'skincare-site-kit',
			'rewrite'             => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => is_user_logged_in(),
		];
		register_post_type( 'sk_template', $args );
	}

	public static function render_elementor_content( $post_id ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}
		echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
	}

	public static function get_location_id( $location ) {
		$settings = get_option( 'sk_theme_builder_settings', [] );
		return isset( $settings[ $location ] ) ? $settings[ $location ] : false;
	}
}
