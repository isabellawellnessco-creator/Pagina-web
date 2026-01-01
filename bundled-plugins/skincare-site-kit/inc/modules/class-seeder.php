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
		// Map page titles to Elementor shortcodes
		$pages = [
			'Inicio' => '[elementor-template id="HOME_ID"]',
			'Tienda' => '', // Woo handles this
			'Sobre Nosotros' => '<h2>Sobre Skin Cupid</h2><p>Tu destino para K-Beauty.</p>',
			'Contacto' => '[elementor-template id="SK_CONTACT_WIDGET"]', // Fallback, using direct widget code below
			'Ayuda / FAQs' => '',
			'Envíos' => '',
			'Política de Privacidad' => '<h2>Política de Privacidad</h2><p>Lorem ipsum...</p>',
			'Términos y Condiciones' => '<h2>Términos</h2><p>Lorem ipsum...</p>',
			'Wishlist' => '',
			'Rewards' => '',
			'Mi Cuenta' => '',
			'Localizador de Tiendas' => '',
			'Skincare' => '', // Category archive usually
			'Maquillaje' => '',
			'Korean Skincare' => ''
		];

		// Define specific widget shortcodes for key pages
		$widget_content = [
			'Contacto' => '[elementor-widget name="sk_contact_section"]', // This is pseudo-code, Elementor doesn't allow direct widget shortcodes easily without a template.
			// Instead, we will insert text that instructs the user to use the widget, or insert a placeholder div that our JS could target (complex).
			// BETTER APPROACH: Insert comment placeholders.
		];

		foreach ( $pages as $title => $content ) {
			$slug = sanitize_title( $title );
			if ( ! get_page_by_path( $slug ) ) {

				// Customize content for known widgets
				if ( $title === 'Rewards' ) $content = '<!-- SK Widget --> [elementor-template id="0"] <div class="sk-widget-placeholder" data-widget="sk_rewards_castle"></div><div class="sk-widget-placeholder" data-widget="sk_rewards_dashboard"></div>';
				if ( $title === 'Contacto' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_contact_section"></div>';
				if ( $title === 'Ayuda / FAQs' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_faq_accordion"></div>';
				if ( $title === 'Envíos' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_shipping_table"></div>';
				if ( $title === 'Localizador de Tiendas' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_store_locator"></div>';
				if ( $title === 'Mi Cuenta' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_account_dashboard"></div>';
				if ( $title === 'Wishlist' ) $content = '<div class="sk-widget-placeholder" data-widget="sk_wishlist_grid"></div>';

				wp_insert_post( [
					'post_type'    => 'page',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $content, // In a real Elementor setup, we would insert the JSON data into _elementor_data meta
					'post_status'  => 'publish',
				] );
			}
		}
	}

	private static function create_categories() {
		$cats = [
			'Limpiadores', 'Exfoliantes', 'Tónicos', 'Esencias',
			'Serums', 'Mascarillas', 'Cremas Solares', 'Maquillaje', 'Sets',
			'K-Beauty', 'J-Beauty'
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
			[ 'name' => 'COSRX Advanced Snail 96 Mucin Power Essence', 'price' => '21.00', 'cat' => 'Esencias' ],
			[ 'name' => 'Beauty of Joseon Relief Sun: Rice + Probiotics', 'price' => '16.00', 'cat' => 'Cremas Solares' ],
			[ 'name' => 'Anua Heartleaf 77% Soothing Toner', 'price' => '24.00', 'cat' => 'Tónicos' ],
			[ 'name' => 'Laneige Lip Sleeping Mask Berry', 'price' => '19.00', 'cat' => 'Mascarillas' ],
			[ 'name' => 'Round Lab 1025 Dokdo Cleanser', 'price' => '14.00', 'cat' => 'Limpiadores' ],
			[ 'name' => 'Skin1004 Madagascar Centella Ampoule', 'price' => '18.00', 'cat' => 'Serums' ],
			[ 'name' => 'Etude House SoonJung 2x Barrier Cream', 'price' => '22.00', 'cat' => 'Cremas' ],
			[ 'name' => 'Rom&nd Juicy Lasting Tint', 'price' => '11.00', 'cat' => 'Maquillaje' ],
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

			// Add Rewards
			$rewards = get_page_by_path( 'rewards' );
			if ( $rewards ) {
				wp_update_nav_menu_item( $primary_id, 0, [
					'menu-item-title' => 'Rewards',
					'menu-item-object-id' => $rewards->ID,
					'menu-item-object' => 'page',
					'menu-item-type' => 'post_type',
					'menu-item-status' => 'publish'
				] );
			}
		}
	}
}
