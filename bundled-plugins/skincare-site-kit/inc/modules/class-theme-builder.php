<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Builder {

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );

		// Hook for Single Product
		add_filter( 'wc_get_template_part', [ __CLASS__, 'override_single_product' ], 10, 3 );
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

	public static function override_single_product( $template, $slug, $name ) {
		if ( $slug === 'content' && $name === 'single-product' ) {
			$template_id = self::get_location_id( 'single_product' );
			if ( $template_id ) {
				// We found a custom template.
				// Render it and return empty to stop default woo output?
				// wc_get_template_part expects a file path return or null.
				// If we echo here, it might appear before the container.
				// Better strategy: Create a wrapper file in the plugin or theme.

				// ACTUALLY: The filter 'wc_get_template_part' allows replacing the file path.
				// We can return a path to a proxy file that calls our render method.

				return SKINCARE_KIT_PATH . 'inc/templates/single-product-proxy.php';
			}
		}
		return $template;
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
