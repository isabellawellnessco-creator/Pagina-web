<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seeder {

	const SEED_VERSION = 6; // v6: The "Clean Slate" Final Flush
	const OPTION_NAME  = 'sk_content_seeded_version';

	public static function init() {
		// Runs on every page load to ensure immediate fix
		add_action( 'init', [ __CLASS__, 'run_seeder' ] );
	}

	public static function run_seeder() {
		$should_run = false;

		// Manual trigger
		if ( isset( $_GET['sk_seed_content'] ) && $_GET['sk_seed_content'] == 'true' && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		// Auto trigger based on version check (No capability check for immediate deployment fix)
		$current_version = (int) get_option( self::OPTION_NAME, 0 );
		if ( $current_version < self::SEED_VERSION ) {
			$should_run = true;
		}

		if ( $should_run ) {
			// 1. FINAL CLEANUP: Remove any legacy Homad markers
			delete_option( 'homad_seeded' );
			delete_option( 'homad_splash_subtitle' );

			// 2. Force Site Identity
			update_option( 'blogname', 'Skin Cupid' );
			update_option( 'blogdescription', 'Korean Skincare & Beauty' );
			remove_theme_mod( 'custom_logo' );

			// 3. Rebuild Content
			self::create_pages();
			self::create_categories();
			self::create_products();
			self::create_theme_parts();
			self::create_menus();

			// 4. Set Homepage
			$home = get_page_by_path( 'inicio' );
			if ( $home ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home->ID );
			}

			// 5. Flush Permalinks (Fixes 404s from theme switch)
			flush_rewrite_rules();

			// Mark as seeded
			update_option( self::OPTION_NAME, self::SEED_VERSION );
			update_option( 'sk_content_seeded', 'yes' );
		}
	}

	private static function create_pages() {
		$pages = [
			'Inicio' => '[sk_marquee][sk_hero_slider][sk_icon_box_grid][sk_product_grid][sk_concern_grid][sk_brand_slider][sk_instagram_feed]',
			'Tienda' => '',
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
			$existing_page = get_page_by_path( $slug );

			if ( ! $existing_page ) {
				wp_insert_post( [
					'post_type'    => 'page',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $content,
					'post_status'  => 'publish',
				] );
			} else {
				$current_content = $existing_page->post_content;
				$needs_update = false;

				if ( stripos( $current_content, 'homad' ) !== false ) {
					$needs_update = true;
				}
				if ( $title === 'Inicio' && strpos( $current_content, 'sk_hero_slider' ) === false ) {
					$needs_update = true;
				}
				if ( $title === 'Rewards' && strpos( $current_content, 'sk_rewards_castle' ) === false ) {
					$needs_update = true;
				}

				if ( $needs_update ) {
					wp_update_post( [
						'ID'           => $existing_page->ID,
						'post_content' => $content,
					] );
				}
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

	private static function upload_placeholder_image() {
		$image_title = 'SkinCupid Placeholder';
		$image_name  = 'skin-cupid-placeholder.png';

		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment'", $image_title ) );

		if ( ! empty( $attachment ) ) {
			return $attachment[0];
		}

		$image_url = 'https://placehold.co/600x600/F8F5F1/0F3062.png?text=Skin+Cupid';

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$tmp = download_url( $image_url );

		if ( is_wp_error( $tmp ) ) {
			return false;
		}

		$file_array = [
			'name'     => $image_name,
			'tmp_name' => $tmp,
		];

		$id = media_handle_sideload( $file_array, 0 );

		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return false;
		}

		wp_update_post( [
			'ID'         => $id,
			'post_title' => $image_title,
		] );

		return $id;
	}

	private static function create_products() {
		$placeholder_id = self::upload_placeholder_image();

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
			$post_id = 0;
			$existing = get_page_by_title( $p['name'], OBJECT, 'product' );

			if ( ! $existing ) {
				$post_id = wp_insert_post( [
					'post_type'    => 'product',
					'post_title'   => $p['name'],
					'post_content' => 'Descripción del producto ' . $p['name'] . '. Ideal para todo tipo de piel.',
					'post_status'  => 'publish',
				] );
			} else {
				$post_id = $existing->ID;
			}

			if ( $post_id ) {
				update_post_meta( $post_id, '_price', $p['price'] );
				update_post_meta( $post_id, '_regular_price', $p['price'] );
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock' );

				update_post_meta( $post_id, 'ingredients', 'Water, Snail Secretion Filtrate, Betaine...' );
				update_post_meta( $post_id, 'how_to_use', 'After cleansing and toning, apply a small amount...' );

				$term = get_term_by( 'name', $p['cat'], 'product_cat' );
				if ( $term ) {
					wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );
				}

				if ( $placeholder_id && ! has_post_thumbnail( $post_id ) ) {
					set_post_thumbnail( $post_id, $placeholder_id );
				}
			}
		}
	}

	private static function create_theme_parts() {
		// AGGRESSIVE RESET: Force overwrite content to fix visual issues

		$parts = [
			'Header Principal' => 'global_header',
			'Footer Principal' => 'global_footer',
			'Ficha de Producto Estándar' => 'single_product',
			'Archivo de Tienda (Catálogo)' => 'shop_archive'
		];

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

		$footer_content = '<div class="sk-footer-content"><p>© ' . date('Y') . ' Skin Cupid. All rights reserved.</p></div>';

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
				// FORCE OVERWRITE to fix broken templates
				wp_update_post( [ 'ID' => $existing->ID, 'post_content' => $content ] );
			}
		}

		update_option( 'sk_theme_builder_settings', $settings );
	}

	private static function create_menus() {
		$primary = 'Primary Menu';
		$footer = 'Footer Menu';

		// AGGRESSIVE MENU CLEANUP
		$existing_primary = wp_get_nav_menu_object( $primary );
		if ( $existing_primary ) {
			$items = wp_get_nav_menu_items( $existing_primary->term_id );
			if ( ! empty( $items ) && is_array( $items ) ) {
				foreach ( $items as $item ) {
					wp_delete_post( $item->ID, true );
				}
			}
		}

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
