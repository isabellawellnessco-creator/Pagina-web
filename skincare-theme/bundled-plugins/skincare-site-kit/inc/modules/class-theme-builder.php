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

		// Hook for Archives (Shop/Category)
		add_filter( 'template_include', [ __CLASS__, 'override_archive_template' ], 99 );
	}

	public static function register_cpt() {
		$args = [
			'label'               => __( 'Partes del tema', 'skincare' ),
			'description'         => __( 'Plantillas de Elementor para header/footer/producto/archivo', 'skincare' ),
			'labels'              => [
				'name'          => __( 'Partes del tema', 'skincare' ),
				'singular_name' => __( 'Parte del tema', 'skincare' ),
				'add_new_item'  => __( 'Añadir nueva parte del tema', 'skincare' ),
				'edit_item'     => __( 'Editar parte del tema', 'skincare' ),
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
				return SKINCARE_KIT_PATH . 'inc/templates/single-product-proxy.php';
			}
		}
		return $template;
	}

	public static function override_archive_template( $template ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$template_id = self::get_location_id( 'shop_archive' );
			if ( $template_id ) {
				return SKINCARE_KIT_PATH . 'inc/templates/archive-product-proxy.php';
			}
		}
		return $template;
	}

	public static function render_elementor_content( $post_id ) {
		$rendered = false;

		if ( did_action( 'elementor/loaded' ) ) {
			$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
			if ( ! empty( $content ) ) {
				echo $content;
				$rendered = true;
			}
		}

		// Fallback for seeded content or non-Elementor posts
		if ( ! $rendered ) {
			$post = get_post( $post_id );
			if ( $post ) {
				echo do_shortcode( $post->post_content );
			}
		}
	}

	public static function get_location_id( $location ) {
		$settings = get_option( 'sk_theme_builder_settings', [] );
		if ( isset( $settings[ $location ] ) && $settings[ $location ] ) {
			return $settings[ $location ];
		}

		$seed_id = 'sk_template_' . $location;
		$seeded = get_posts( [
			'post_type'      => 'sk_template',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_query'     => [
				[
					'key'   => \Skincare\SiteKit\Modules\Seeder::META_SEED_ID,
					'value' => $seed_id,
				],
			],
		] );

		if ( ! empty( $seeded ) ) {
			$settings[ $location ] = $seeded[0]->ID;
			update_option( 'sk_theme_builder_settings', $settings );
			return $seeded[0]->ID;
		}

		return false;
	}
}
