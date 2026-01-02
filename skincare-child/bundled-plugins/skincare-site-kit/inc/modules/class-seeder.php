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

			// Force Site Title
			update_option( 'blogname', 'Skin Cupid' );
			update_option( 'blogdescription', 'Korean Skincare & Beauty' );

			// Mark as seeded
			update_option( 'sk_content_seeded', 'yes' );

			// Add admin notice
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p>Contenido semilla creado exitosamente. Título del sitio actualizado a "Skin Cupid".</p></div>';
			} );
		}
	}

	private static function create_pages() {
		$pages = [
			'Inicio' => '[sk_marquee][sk_hero_slider][sk_icon_box_grid][sk_product_grid][sk_concern_grid][sk_brand_slider][sk_instagram_feed]',
			'Tienda' => '', // Managed by Archive Template
			'Sobre Nosotros' => '<h2>Sobre Skin Cupid</h2><p>Tu destino para K-Beauty.</p>',
			'Contacto' => '[sk_contact_section]',
			'Ayuda / FAQs' => '[sk_faq_accordion]',
			'Envíos' => '[sk_shipping_table]',
			'Política de Privacidad' => '<h2>Política de Privacidad</h2><p>Lorem ipsum...</p>',
			'Términos y Condiciones' => '<h2>Términos</h2><p>Lorem ipsum...</p>',
			'Wishlist' => '[sk_wishlist_grid]',
			'Rewards' => '[sk_rewards_castle][sk_rewards_earn_redeem][sk_rewards_dashboard]',
			'Mi Cuenta' => '[sk_account_dashboard]',
			'Localizador de Tiendas' => '[sk_store_locator]',
			'Skincare' => '',
			'Maquillaje' => '',
			'Korean Skincare' => ''
		];

		foreach ( $pages as $title => $content ) {
			$slug = sanitize_title( $title );
			if ( ! get_page_by_path( $slug ) ) {
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

				// Add attributes for Product Tabs
				update_post_meta( $post_id, 'ingredients', 'Water, Snail Secretion Filtrate, Betaine...' );
				update_post_meta( $post_id, 'how_to_use', 'After cleansing and toning, apply a small amount...' );

				$term = get_term_by( 'name', $p['cat'], 'product_cat' );
				if ( $term ) {
					wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );
				}
			}
		}
	}

	private static function create_theme_parts() {
		$parts = [
			'Header Principal' => 'global_header',
			'Footer Principal' => 'global_footer',
			'Ficha de Producto Estándar' => 'single_product',
			'Archivo de Tienda (Catálogo)' => 'shop_archive'
		];

		// Header Content
		$header_content = '
		<div class="sk-header-row">
			<div class="sk-logo"><h1>Skin Cupid</h1></div>
			<div class="sk-menu">[sk_widget name="nav_menu"]</div>
			<div class="sk-icons">
				<a href="#" class="sk-search-trigger"><i class="eicon-search"></i></a>
				<a href="/wishlist/"><i class="eicon-heart"></i></a>
				<a href="#cart-drawer" class="sk-cart-trigger"><i class="eicon-bag-medium"></i></a>
			</div>
		</div>';

		$footer_content = '<div class="sk-footer-content"><p>© 2023 Skin Cupid Replica. All rights reserved.</p></div>';

		// Archive Content
		$archive_content = '
		<div class="sk-archive-layout" style="display:flex; gap:30px;">
			<aside class="sk-sidebar" style="width:250px;">
				[sk_ajax_filter]
			</aside>
			<main class="sk-main-loop" style="flex:1;">
				[sk_product_grid posts_per_page="12"]
			</main>
		</div>
		';

		$settings = get_option( 'sk_theme_builder_settings', [] );

		foreach ( $parts as $title => $key ) {
			$existing = get_page_by_title( $title, OBJECT, 'sk_template' );
			$content = '';

			if ( $key === 'global_header' ) $content = $header_content;
			if ( $key === 'global_footer' ) $content = $footer_content;
			if ( $key === 'shop_archive' ) $content = $archive_content;

			if ( ! $existing ) {
				$post_id = wp_insert_post( [
					'post_type'   => 'sk_template',
					'post_title'  => $title,
					'post_content' => $content,
					'post_status' => 'publish',
				] );

				$settings[ $key ] = $post_id;
			} else {
				$settings[ $key ] = $existing->ID;
				// Update content if empty for existing parts
				if ( empty( $existing->post_content ) ) {
					wp_update_post( [ 'ID' => $existing->ID, 'post_content' => $content ] );
				}
			}
		}

		update_option( 'sk_theme_builder_settings', $settings );
	}

	private static function create_menus() {
		$primary = 'Primary Menu';
		$footer = 'Footer Menu';

		$primary_id = wp_create_nav_menu( $primary );
		$footer_id = wp_create_nav_menu( $footer );

		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations['primary'] = $primary_id;
		$locations['footer'] = $footer_id;
		set_theme_mod( 'nav_menu_locations', $locations );

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
