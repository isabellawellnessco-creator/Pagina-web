<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seeder {

	const SEED_VERSION = 4; // Increment this to force re-seeding logic
	const OPTION_NAME  = 'sk_content_seeded_version';

	public static function init() {
		// Hook into admin init to run seeder if triggered
		add_action( 'admin_init', [ __CLASS__, 'run_seeder' ] );
	}

	public static function run_seeder() {
		$should_run = false;

		// Manual trigger
		if ( isset( $_GET['sk_seed_content'] ) && $_GET['sk_seed_content'] == 'true' && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		// Auto trigger based on version
		$current_version = (int) get_option( self::OPTION_NAME, 0 );
		if ( $current_version < self::SEED_VERSION && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		if ( $should_run ) {
			// Force Site Title and Tagline immediately
			update_option( 'blogname', 'Skin Cupid' );
			update_option( 'blogdescription', 'Korean Skincare & Beauty' );

			self::create_pages();
			self::create_categories();
			self::create_products();
			self::create_theme_parts();
			self::create_menus();

			// Set homepage
			$home = get_page_by_path( 'home' );
			if ( $home ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home->ID );
			}

			// Mark as seeded with new version
			update_option( self::OPTION_NAME, self::SEED_VERSION );
			// Keep legacy option for compatibility if needed, or update it too
			update_option( 'sk_content_seeded', 'yes' );

			// Add admin notice
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p>Skin Cupid Kit: Contenido semilla actualizado correctamente (v' . self::SEED_VERSION . '). Identidad del sitio actualizada.</p></div>';
			} );
		}
	}

	private static function create_pages() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$about_content = '
		<section class="sk-about-hero">
			<img src="' . esc_url( $theme_assets . 'placeholder-hero-2.svg' ) . '" alt="Skin Cupid hero placeholder">
			<div class="sk-about-copy">
				<h2>About Skin Cupid</h2>
				<p>We curate Korean skincare, K-Beauty essentials, and new launches so your routine always feels inspired.</p>
			</div>
		</section>
		<section class="sk-about-grid">
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="K-Beauty ritual">
				<h3>Curated routines</h3>
				<p>Follow the 10-step ritual with trusted textures and soothing formulas.</p>
			</div>
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="Ingredient focus">
				<h3>Ingredient stories</h3>
				<p>Discover hero ingredients like Centella, Rice, and Snail mucin.</p>
			</div>
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="Community love">
				<h3>Community-approved</h3>
				<p>Shop best sellers loved by the Skin Cupid community.</p>
			</div>
		</section>
		';

		$pages = [
			[
				'title' => 'Home',
				'slug' => 'home',
				'content' => '[sk_marquee][sk_hero_slider][sk_icon_box_grid][sk_product_grid][sk_concern_grid][sk_brand_slider][sk_instagram_feed]',
				'template' => 'template-landing.php',
				'shortcode_check' => 'sk_hero_slider',
			],
			[
				'title' => 'Shop',
				'slug' => 'shop',
				'content' => '[sk_product_grid posts_per_page="12"]',
				'template' => 'template-full-width.php',
			],
			[
				'title' => 'About Skin Cupid',
				'slug' => 'about',
				'content' => $about_content,
				'template' => 'template-full-width.php',
			],
			[ 'title' => 'Contact', 'slug' => 'contact', 'content' => '' ],
			[ 'title' => 'FAQs', 'slug' => 'faqs', 'content' => '' ],
			[ 'title' => 'Shipping', 'slug' => 'shipping', 'content' => '' ],
			[ 'title' => 'Privacy Policy', 'slug' => 'privacy', 'content' => '' ],
			[ 'title' => 'Terms & Conditions', 'slug' => 'terms', 'content' => '' ],
			[ 'title' => 'Wishlist', 'slug' => 'wishlist', 'content' => '' ],
			[ 'title' => 'Rewards', 'slug' => 'rewards', 'content' => '' ],
			[ 'title' => 'My Account', 'slug' => 'account', 'content' => '' ],
			[ 'title' => 'Login', 'slug' => 'login', 'content' => '' ],
			[ 'title' => 'Store Locator', 'slug' => 'store-locator', 'content' => '' ],
			[ 'title' => 'Careers', 'slug' => 'care', 'content' => '' ],
			[ 'title' => 'Press', 'slug' => 'skin', 'content' => '' ],
			[ 'title' => 'Korean Skincare', 'slug' => 'korean', 'content' => '' ],
			[ 'title' => 'Make Up', 'slug' => 'makeup', 'content' => '' ],
			[ 'title' => 'Vegan Beauty', 'slug' => 'vegan', 'content' => '' ],
			[ 'title' => 'Learn', 'slug' => 'learn', 'content' => '' ],
		];

		foreach ( $pages as $page ) {
			$existing_page = get_page_by_path( $page['slug'] );

			$post_data = [
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_content' => $page['content'],
				'post_status'  => 'publish',
			];

			if ( ! $existing_page ) {
				$post_id = wp_insert_post( $post_data );
			} else {
				$post_id = $existing_page->ID;
				$current_content = $existing_page->post_content;
				$needs_update = false;

				if ( stripos( $current_content, 'homad' ) !== false ) {
					$needs_update = true;
				}

				if ( ! empty( $page['shortcode_check'] ) && strpos( $current_content, $page['shortcode_check'] ) === false ) {
					$needs_update = true;
				}

				if ( empty( $current_content ) && ! empty( $page['content'] ) ) {
					$needs_update = true;
				}

				if ( $needs_update || $existing_page->post_title !== $page['title'] ) {
					wp_update_post( array_merge( $post_data, [ 'ID' => $post_id ] ) );
				}
			}

			if ( ! empty( $page['template'] ) && $post_id ) {
				$current_template = get_post_meta( $post_id, '_wp_page_template', true );
				if ( $current_template !== $page['template'] ) {
					update_post_meta( $post_id, '_wp_page_template', $page['template'] );
				}
			}

			if ( ! empty( $page['template'] ) && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $page['template'] );
			}

			if ( ! is_wp_error( $page_id ) ) {
				$page_ids[ $slug ] = $page_id;
			}
		}

		foreach ( $pages as $page ) {
			if ( empty( $page['parent'] ) ) {
				continue;
			}

			$child_id  = $page_ids[ $page['slug'] ] ?? 0;
			$parent_id = $page_ids[ $page['parent'] ] ?? 0;

			if ( $child_id && $parent_id ) {
				wp_update_post( [
					'ID'          => $child_id,
					'post_parent' => $parent_id,
				] );
			}
		}
	}

	private static function create_categories() {
		$cats = [
			'Oil Cleanser',
			'Water-Based Cleanser',
			'Exfoliator',
			'Toner',
			'Essence',
			'Serum/Ampoule',
			'Mask',
			'Eye care',
			'Moisturiser',
			'Suncream',
			'Sheet Masks',
			'Sets & Gifts',
			'Devices',
			'Minis',
			'Vegan & Cruelty-Free',
			'Face',
			'Eyes',
			'Lips',
			'Make Up Tools',
			'Make Up Remover',
		];

		foreach ( $cats as $cat ) {
			if ( ! term_exists( $cat, 'product_cat' ) ) {
				wp_insert_term( $cat, 'product_cat' );
			}
		}
	}

	private static function upload_placeholder_image() {
		// Check if placeholder already exists in media library by title/name
		// We use a specific title to find it easily
		$image_title = 'SkinCupid Placeholder';
		$image_name  = 'skin-cupid-placeholder.svg';

		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment'", $image_title ) );

		if ( ! empty( $attachment ) ) {
			return $attachment[0];
		}

		// URL of the placeholder image
		$image_url = SKINCARE_KIT_URL . 'assets/images/placeholder-product.svg';

		// Download logic
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		// Download the image to temp dir
		$tmp = download_url( $image_url );

		if ( is_wp_error( $tmp ) ) {
			return false;
		}

		$file_array = [
			'name'     => $image_name,
			'tmp_name' => $tmp,
		];

		// Upload to media library
		$id = media_handle_sideload( $file_array, 0 );

		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return false;
		}

		// Update title so we can find it next time
		wp_update_post( [
			'ID'         => $id,
			'post_title' => $image_title,
		] );

		return $id;
	}

	private static function create_products() {
		// We want to ensure demo products exist and have images.
		// If they already exist, we will try to attach the image if missing.

		$placeholder_id = self::upload_placeholder_image();

		$demo_products = [
			[ 'name' => 'HaruHaru Wonder Black Rice Oil Cleanser', 'price' => '19.00', 'cat' => 'Oil Cleanser' ],
			[ 'name' => 'Round Lab 1025 Dokdo Cleanser', 'price' => '14.00', 'cat' => 'Water-Based Cleanser' ],
			[ 'name' => 'Some By Mi AHA BHA PHA 30 Days Toner', 'price' => '18.00', 'cat' => 'Toner' ],
			[ 'name' => 'COSRX Advanced Snail 96 Mucin Power Essence', 'price' => '21.00', 'cat' => 'Essence' ],
			[ 'name' => 'Anua Heartleaf 80% Soothing Ampoule', 'price' => '24.00', 'cat' => 'Serum/Ampoule' ],
			[ 'name' => 'Beauty of Joseon Red Bean Refreshing Mask', 'price' => '18.00', 'cat' => 'Mask' ],
			[ 'name' => 'Klairs Fundamental Eye Butter', 'price' => '23.00', 'cat' => 'Eye care' ],
			[ 'name' => 'Isntree Hyaluronic Acid Moist Cream', 'price' => '20.00', 'cat' => 'Moisturiser' ],
			[ 'name' => 'Beauty of Joseon Relief Sun: Rice + Probiotics', 'price' => '16.00', 'cat' => 'Suncream' ],
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

				// Add attributes for Product Tabs
				update_post_meta( $post_id, 'ingredients', 'Water, Snail Secretion Filtrate, Betaine...' );
				update_post_meta( $post_id, 'how_to_use', 'After cleansing and toning, apply a small amount...' );

				// Assign Category
				$term = get_term_by( 'name', $p['cat'], 'product_cat' );
				if ( $term ) {
					wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );
				}

				// Assign Image if missing and we have a placeholder
				if ( $placeholder_id && ! has_post_thumbnail( $post_id ) ) {
					set_post_thumbnail( $post_id, $placeholder_id );
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

		// UPDATED: Footer content with Skin Cupid text
		$footer_content = '<div class="sk-footer-content"><p>© ' . date('Y') . ' Skin Cupid. All rights reserved.</p></div>';

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
				// Update content if empty for existing parts or if we suspect it's wrong (optional, keep safe for now)
				// Or if it contains old "Replica" text in footer
				$current_content = $existing->post_content;
				$should_update = false;

				if ( empty( $current_content ) ) $should_update = true;
				if ( $key === 'global_footer' && strpos($current_content, 'Replica') !== false ) $should_update = true;

				if ( $should_update ) {
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
			// Check if menu is empty before adding items, or clearing it?
			// For now, assume if we created it or it exists, we ensure items.
			// WordPress doesn't easily let us check "is empty" without fetching items.
			// We'll proceed with upserting logic if needed, but for simplicity:

			// Only add if no items exist to avoid duplicates on re-run
			$items = wp_get_nav_menu_items( $primary_id );
			if ( empty( $items ) ) {
				wp_update_nav_menu_item( $primary_id, 0, [
					'menu-item-title' => 'Home',
					'menu-item-url' => home_url( '/' ),
					'menu-item-status' => 'publish'
				] );

				$shop = get_page_by_path( 'shop' );
				if ( $shop ) {
					wp_update_nav_menu_item( $primary_id, 0, [
						'menu-item-title' => 'Shop',
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
}
