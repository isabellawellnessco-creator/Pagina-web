<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seeder {

	public static function init() {
		// Hook into admin init to run seeder if triggered
		add_action( 'admin_init', [ __CLASS__, 'run_seeder' ] );
	}

	public static function run_seeder() {
		if ( isset( $_GET['sk_seed_content'] ) && $_GET['sk_seed_content'] == 'true' && current_user_can( 'manage_options' ) ) {
			self::create_pages();
			self::create_categories();
			self::create_products();
			self::create_theme_parts();
			self::create_menus();

			// Set homepage
			$home = get_page_by_path( 'inicio' );
			if ( $home ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home->ID );
			}

			// Add admin notice
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p>Contenido semilla creado exitosamente.</p></div>';
			} );
		}
	}

	private static function create_pages() {
		$pages = [
			'Inicio' => '2026.html', // Based on home
			'Tienda' => '',
			'Sobre Nosotros' => '',
			'Contacto' => 'Cont.html',
			'Ayuda / FAQs' => 'FAQs.html',
			'Envíos' => 'Shi.html',
			'Política de Privacidad' => 'Priv.html',
			'Términos y Condiciones' => 'Ter.html',
			'Wishlist' => 'Wis.html',
			'Rewards' => 'Rewa.html',
			'Mi Cuenta' => 'Acc.html',
			'Localizador de Tiendas' => 'loct.html',
			'Skincare' => 'Skin.html',
			'Maquillaje' => 'Mak.html',
			'Korean Skincare' => 'Korean.html'
		];

		foreach ( $pages as $title => $ref ) {
			$slug = sanitize_title( $title );
			if ( ! get_page_by_path( $slug ) ) {
				$content = '';
				// Basic template injection could happen here if we parsed the HTMLs
				// For now, we create blank slate pages ready for Elementor
				if ( $ref ) {
					$content = '<!-- Referencia: ' . $ref . ' --> [elementor-template id="XXXX"]';
				}

				wp_insert_post( [
					'post_type'    => 'page',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $content,
					'post_status'  => 'publish',
				] );
			}
		}
	}

	private static function create_categories() {
		$cats = [
			'Limpiadores', 'Exfoliantes', 'Tónicos', 'Esencias',
			'Serums', 'Mascarillas', 'Cremas Solares', 'Maquillaje', 'Sets'
		];

		foreach ( $cats as $cat ) {
			if ( ! term_exists( $cat, 'product_cat' ) ) {
				wp_insert_term( $cat, 'product_cat' );
			}
		}
	}

	private static function create_products() {
		// Check if we have products
		$existing = get_posts( [ 'post_type' => 'product', 'posts_per_page' => 1 ] );
		if ( ! empty( $existing ) ) return;

		$demo_products = [
			[ 'name' => 'Limpiador Espumoso Suave', 'price' => '25.00', 'cat' => 'Limpiadores' ],
			[ 'name' => 'Tónico Hidratante Profundo', 'price' => '30.00', 'cat' => 'Tónicos' ],
			[ 'name' => 'Serum Vitamina C Iluminador', 'price' => '45.00', 'cat' => 'Serums' ],
			[ 'name' => 'Crema Solar SPF 50+', 'price' => '22.00', 'cat' => 'Cremas Solares' ],
			[ 'name' => 'Mascarilla de Arcilla Purificante', 'price' => '18.00', 'cat' => 'Mascarillas' ],
			[ 'name' => 'Set Rutina Completa Piel Grasa', 'price' => '110.00', 'cat' => 'Sets' ],
		];

		foreach ( $demo_products as $p ) {
			$post_id = wp_insert_post( [
				'post_type'   => 'product',
				'post_title'  => $p['name'],
				'post_content' => 'Descripción del producto ' . $p['name'] . '. Ideal para todo tipo de piel.',
				'post_status' => 'publish',
			] );

			if ( $post_id ) {
				update_post_meta( $post_id, '_price', $p['price'] );
				update_post_meta( $post_id, '_regular_price', $p['price'] );
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock' );

				$term = get_term_by( 'name', $p['cat'], 'product_cat' );
				if ( $term ) {
					wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );
				}
			}
		}
	}

	private static function create_theme_parts() {
		// Create Header, Footer, Single Product templates
		$parts = [
			'Header Principal' => 'global_header',
			'Footer Principal' => 'global_footer',
			'Ficha de Producto Estándar' => 'single_product'
		];

		$settings = get_option( 'sk_theme_builder_settings', [] );

		foreach ( $parts as $title => $key ) {
			$existing = get_page_by_title( $title, OBJECT, 'sk_template' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( [
					'post_type'   => 'sk_template',
					'post_title'  => $title,
					'post_content' => '', // Empty for Elementor
					'post_status' => 'publish',
				] );

				// Assign to settings
				$settings[ $key ] = $post_id;
			} else {
				$settings[ $key ] = $existing->ID;
			}
		}

		update_option( 'sk_theme_builder_settings', $settings );
	}

	private static function create_menus() {
		$primary = 'Primary Menu';
		$footer = 'Footer Menu';

		$primary_id = wp_create_nav_menu( $primary );
		$footer_id = wp_create_nav_menu( $footer );

		// Assign locations
		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations['primary'] = $primary_id;
		$locations['footer'] = $footer_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		// Add items to Primary
		if ( ! is_wp_error( $primary_id ) ) {
			wp_update_nav_menu_item( $primary_id, 0, [
				'menu-item-title' => 'Inicio',
				'menu-item-url' => home_url( '/' ),
				'menu-item-status' => 'publish'
			] );

			$shop = get_page_by_path( 'tienda' );
			if ( $shop ) {
				wp_update_nav_menu_item( $primary_id, 0, [
					'menu-item-title' => 'Tienda',
					'menu-item-object-id' => $shop->ID,
					'menu-item-object' => 'page',
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish'
				] );
			}
		}
	}
}
