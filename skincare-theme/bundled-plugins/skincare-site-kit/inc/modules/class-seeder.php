<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seeder {

	const SEED_VERSION = 5; // Increment this to force re-seeding logic
	const OPTION_NAME  = 'sk_content_seeded_version';

	public static function init() {
		// Hook into admin init to run seeder if triggered
		add_action( 'admin_init', [ __CLASS__, 'run_seeder' ] );
	}

	public static function run_seeder() {
		$should_run = false;

		$settings = get_option( 'sk_theme_builder_settings', [] );
		$header_id = isset( $settings['global_header'] ) ? (int) $settings['global_header'] : 0;
		$footer_id = isset( $settings['global_footer'] ) ? (int) $settings['global_footer'] : 0;
		$missing_theme_parts = ! $header_id || ! get_post( $header_id ) || ! $footer_id || ! get_post( $footer_id );

		// Manual trigger
		if ( isset( $_GET['sk_seed_content'] ) && $_GET['sk_seed_content'] == 'true' && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		// Auto trigger based on version
		$current_version = (int) get_option( self::OPTION_NAME, 0 );
		if ( $current_version < self::SEED_VERSION && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		if ( $missing_theme_parts && current_user_can( 'manage_options' ) ) {
			$should_run = true;
		}

		if ( $should_run ) {
			// Force Site Title and Tagline immediately
			update_option( 'blogname', 'Skin Cupid' );
			update_option( 'blogdescription', 'Skincare coreana y belleza' );

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

	public static function create_pages() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		$about_content = '
		<section class="sk-about-hero">
			<img src="' . esc_url( $theme_assets . 'placeholder-hero-2.svg' ) . '" alt="Skin Cupid hero placeholder">
			<div class="sk-about-copy">
				<h2>Sobre Skin Cupid</h2>
				<p>Seleccionamos skincare coreano, esenciales de K-Beauty y nuevos lanzamientos para que tu rutina siempre se sienta inspirada.</p>
			</div>
		</section>
		<section class="sk-about-grid">
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="Ritual K-Beauty">
				<h3>Rutinas seleccionadas</h3>
				<p>Sigue el ritual de 10 pasos con texturas confiables y fórmulas calmantes.</p>
			</div>
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="Ingredientes">
				<h3>Historias de ingredientes</h3>
				<p>Descubre ingredientes estrella como Centella, Arroz y Mucina de caracol.</p>
			</div>
			<div class="sk-about-card">
				<img src="' . esc_url( $theme_assets . 'placeholder-card.svg' ) . '" alt="Comunidad">
				<h3>Aprobado por la comunidad</h3>
				<p>Compra best sellers amados por la comunidad de Skin Cupid.</p>
			</div>
		</section>
		';

		$contact_content = '[sk_contact_section]';
		$faqs_content = '
		<section class="sk-page-intro">
			<h2>Preguntas frecuentes</h2>
			<p>Resuelve dudas sobre compras, envíos y devoluciones antes de escribirnos.</p>
		</section>
		[sk_faq_accordion]
		';
		$shipping_content = '
		<section class="sk-page-intro">
			<h2>Envíos y devoluciones</h2>
			<p>Conoce los tiempos de entrega y nuestras políticas de devolución.</p>
		</section>
		[sk_shipping_table]
		<section class="sk-page-details">
			<h3>Devoluciones sencillas</h3>
			<ul>
				<li>Solicita devoluciones dentro de los 30 días posteriores a la compra.</li>
				<li>Los productos deben estar sin abrir y en su empaque original.</li>
				<li>Te notificaremos cuando el reembolso sea procesado.</li>
			</ul>
		</section>
		';
		$rewards_content = '[sk_rewards_castle][sk_rewards_earn_redeem][sk_rewards_dashboard]';
		$wishlist_content = '[sk_wishlist_grid]';
		$account_content = '[sk_account_dashboard]';
		$store_locator_content = '[sk_store_locator]';
		$learn_content = '
		<section class="sk-learn-hero">
			<h2>Aprende sobre K-Beauty</h2>
			<p>Guías prácticas, ingredientes clave y rutinas diseñadas para cada tipo de piel.</p>
		</section>
		<section class="sk-learn-grid">
			<div class="sk-learn-card">
				<h3>Rutina de 10 pasos</h3>
				<p>Conoce el orden correcto y cómo combinar productos para potenciar resultados.</p>
			</div>
			<div class="sk-learn-card">
				<h3>Ingredientes estrella</h3>
				<p>Centella, niacinamida y mucina de caracol explicados de forma simple.</p>
			</div>
			<div class="sk-learn-card">
				<h3>Consejos por tipo de piel</h3>
				<p>Selecciona fórmulas calmantes, hidratantes o iluminadoras según tu necesidad.</p>
			</div>
		</section>
		';
		$korean_content = '
		<section class="sk-page-intro">
			<h2>Skincare coreano para cada rutina</h2>
			<p>Explora los pasos esenciales y las marcas favoritas de la comunidad Skin Cupid.</p>
			<a class="btn sk-btn" href="/shop/">Comprar en la tienda</a>
		</section>
		';
		$makeup_content = '
		<section class="sk-page-intro">
			<h2>Maquillaje que cuida tu piel</h2>
			<p>Formulaciones ligeras con acabado natural y activos hidratantes.</p>
		</section>
		';
		$vegan_content = '
		<section class="sk-page-intro">
			<h2>Belleza vegana y cruelty-free</h2>
			<p>Productos conscientes y efectivos para rutinas responsables.</p>
		</section>
		';
		$care_content = '
		<section class="sk-page-intro">
			<h2>Trabaja con nosotros</h2>
			<p>Únete a un equipo apasionado por el cuidado de la piel y la innovación.</p>
		</section>
		';
		$press_content = '
		<section class="sk-page-intro">
			<h2>Prensa</h2>
			<p>Notas, colaboraciones y novedades para medios.</p>
		</section>
		';

		$pages = [
			[
				'title' => 'Inicio',
				'slug' => 'home',
				'content' => '[sk_marquee][sk_hero_slider][sk_icon_box_grid][sk_product_grid][sk_concern_grid][sk_brand_slider][sk_instagram_feed]',
				'template' => 'template-landing.php',
				'shortcode_check' => 'sk_hero_slider',
			],
			[
				'title' => 'Tienda',
				'slug' => 'shop',
				'content' => '[sk_product_grid posts_per_page="12"]',
				'template' => 'template-full-width.php',
			],
			[
				'title' => 'Sobre Skin Cupid',
				'slug' => 'about',
				'content' => $about_content,
				'template' => 'template-full-width.php',
			],
			[ 'title' => 'Contacto', 'slug' => 'contact', 'content' => $contact_content ],
			[ 'title' => 'Preguntas frecuentes', 'slug' => 'faqs', 'content' => $faqs_content ],
			[ 'title' => 'Envíos', 'slug' => 'shipping', 'content' => $shipping_content ],
			[
				'title' => 'Política de privacidad',
				'slug' => 'privacy-policy',
				'content' => '
				<section class="sk-page-intro">
					<h2>Política de privacidad</h2>
					<p>Tu información está protegida. Conoce cómo recopilamos y usamos tus datos.</p>
				</section>
				<section class="sk-page-details">
					<h3>Qué datos recopilamos</h3>
					<ul>
						<li>Información de contacto y envío.</li>
						<li>Historial de pedidos y preferencias.</li>
						<li>Comunicaciones con el equipo de soporte.</li>
					</ul>
					<h3>Cómo utilizamos la información</h3>
					<p>Usamos tus datos para procesar pedidos, mejorar la experiencia y enviar novedades si lo autorizas.</p>
				</section>
				',
				'template' => 'page-privacy.php',
			],
			[
				'title' => 'Términos y condiciones',
				'slug' => 'terms',
				'content' => '
				<section class="sk-page-intro">
					<h2>Términos y condiciones</h2>
					<p>Revisa los términos de compra, pagos y disponibilidad de productos.</p>
				</section>
				<section class="sk-page-details">
					<h3>Pagos</h3>
					<p>Aceptamos métodos de pago seguros disponibles al finalizar la compra.</p>
					<h3>Disponibilidad</h3>
					<p>Los productos están sujetos a disponibilidad y pueden variar según inventario.</p>
				</section>
				',
			],
			[ 'title' => 'Lista de deseos', 'slug' => 'wishlist', 'content' => $wishlist_content ],
			[ 'title' => 'Recompensas', 'slug' => 'rewards', 'content' => $rewards_content ],
			[ 'title' => 'Mi cuenta', 'slug' => 'account', 'content' => $account_content ],
			[ 'title' => 'Iniciar sesión', 'slug' => 'login', 'content' => '[woocommerce_my_account]' ],
			[ 'title' => 'Localizador de tiendas', 'slug' => 'store-locator', 'content' => $store_locator_content ],
			[ 'title' => 'Trabaja con nosotros', 'slug' => 'care', 'content' => $care_content ],
			[ 'title' => 'Prensa', 'slug' => 'skin', 'content' => $press_content ],
			[ 'title' => 'Skincare coreano', 'slug' => 'korean', 'content' => $korean_content ],
			[ 'title' => 'Maquillaje', 'slug' => 'makeup', 'content' => $makeup_content ],
			[ 'title' => 'Belleza vegana', 'slug' => 'vegan', 'content' => $vegan_content ],
			[ 'title' => 'Aprender', 'slug' => 'learn', 'content' => $learn_content ],
		];

		$page_ids = [];
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

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				$page_ids[ $page['slug'] ] = $post_id;
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

	public static function create_categories() {
		$groups = [
			[
				'name' => 'Limpieza',
				'children' => [
					'Limpiador de aceite',
					'Limpiador a base de agua',
				],
			],
			[
				'name' => 'Tratamiento',
				'children' => [
					'Exfoliante',
					'Tónico',
					'Esencia',
					'Sérum/Ampolla',
					'Mascarilla',
					'Mascarillas de tela',
				],
			],
			[
				'name' => 'Hidratación y cuidado',
				'children' => [
					'Cuidado de ojos',
					'Hidratante',
					'Protector solar',
				],
			],
			[
				'name' => 'Maquillaje',
				'children' => [
					'Rostro',
					'Ojos',
					'Labios',
					'Herramientas de maquillaje',
					'Desmaquillante',
				],
			],
			[
				'name' => 'Colecciones',
				'children' => [
					'Sets y regalos',
					'Dispositivos',
					'Mini tallas',
					'Vegano y cruelty-free',
				],
			],
		];

		foreach ( $groups as $group ) {
			$parent_id = 0;
			$parent_term = term_exists( $group['name'], 'product_cat' );
			if ( ! $parent_term ) {
				$created_parent = wp_insert_term( $group['name'], 'product_cat' );
				if ( ! is_wp_error( $created_parent ) ) {
					$parent_id = $created_parent['term_id'];
				}
			} else {
				$parent_id = is_array( $parent_term ) ? $parent_term['term_id'] : $parent_term;
			}

			foreach ( $group['children'] as $child_name ) {
				$child_term = term_exists( $child_name, 'product_cat' );
				if ( ! $child_term ) {
					wp_insert_term( $child_name, 'product_cat', [ 'parent' => $parent_id ] );
				} elseif ( $parent_id ) {
					$child_id = is_array( $child_term ) ? $child_term['term_id'] : $child_term;
					wp_update_term( $child_id, 'product_cat', [ 'parent' => $parent_id ] );
				}
			}
		}
	}

	public static function upload_placeholder_image() {
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

	public static function create_products() {
		// We want to ensure demo products exist and have images.
		// If they already exist, we will try to attach the image if missing.

		$placeholder_id = self::upload_placeholder_image();

		$demo_products = [
			[ 'name' => 'HaruHaru Wonder - Aceite limpiador Black Rice', 'price' => '19.00', 'cat' => 'Limpiador de aceite' ],
			[ 'name' => 'Round Lab - Limpiador 1025 Dokdo', 'price' => '14.00', 'cat' => 'Limpiador a base de agua' ],
			[ 'name' => 'Heimish - Bálsamo limpiador All Clean', 'price' => '16.00', 'cat' => 'Limpiador de aceite' ],
			[ 'name' => 'Some By Mi - Tónico AHA BHA PHA 30 Days', 'price' => '18.00', 'cat' => 'Tónico' ],
			[ 'name' => 'COSRX - Esencia Advanced Snail 96 Mucin', 'price' => '21.00', 'cat' => 'Esencia' ],
			[ 'name' => 'Anua - Ampolla calmante Heartleaf 80%', 'price' => '24.00', 'cat' => 'Sérum/Ampolla' ],
			[ 'name' => 'Beauty of Joseon - Sérum Glow Deep Rice + Arbutin', 'price' => '17.00', 'cat' => 'Sérum/Ampolla' ],
			[ 'name' => 'Beauty of Joseon - Mascarilla Red Bean Refreshing', 'price' => '18.00', 'cat' => 'Mascarilla' ],
			[ 'name' => 'Mediheal - Mascarillas de tela Tea Tree', 'price' => '12.00', 'cat' => 'Mascarillas de tela' ],
			[ 'name' => 'Klairs - Crema de ojos Fundamental Eye Butter', 'price' => '23.00', 'cat' => 'Cuidado de ojos' ],
			[ 'name' => 'Isntree - Crema hidratante Hyaluronic Acid', 'price' => '20.00', 'cat' => 'Hidratante' ],
			[ 'name' => 'Laneige - Lip Sleeping Mask Berry', 'price' => '19.00', 'cat' => 'Labios' ],
			[ 'name' => 'Rom&nd - Juicy Lasting Tint', 'price' => '13.00', 'cat' => 'Labios' ],
			[ 'name' => 'Etude - Double Lasting Foundation', 'price' => '22.00', 'cat' => 'Rostro' ],
			[ 'name' => 'Dear Dahlia - Sombra de ojos Blooming', 'price' => '25.00', 'cat' => 'Ojos' ],
			[ 'name' => 'Beauty of Joseon - Protector solar Relief Sun: Rice + Probiotics', 'price' => '16.00', 'cat' => 'Protector solar' ],
			[ 'name' => 'Mixsoon - Esencia Bean', 'price' => '28.00', 'cat' => 'Esencia' ],
			[ 'name' => 'Beauty of Joseon - Set de viaje Glow', 'price' => '30.00', 'cat' => 'Sets y regalos' ],
		];

		foreach ( $demo_products as $p ) {
			$post_id = 0;
			$existing = get_page_by_title( $p['name'], OBJECT, 'product' );

			if ( ! $existing ) {
				$post_id = wp_insert_post( [
					'post_type'    => 'product',
					'post_title'   => $p['name'],
					'post_content' => 'Descripción del producto ' . $p['name'] . '. Ideal para todo tipo de piel, con textura ligera y resultados visibles.',
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
				update_post_meta( $post_id, 'ingredients', 'Agua, glicerina, extractos botánicos y activos calmantes.' );
				update_post_meta( $post_id, 'how_to_use', 'Después de limpiar y tonificar, aplica una pequeña cantidad en rostro y cuello.' );

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

	public static function create_theme_parts() {
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
		$footer_content = '<div class="sk-footer-content"><p>© ' . date('Y') . ' Skin Cupid. Todos los derechos reservados.</p></div>';

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

	public static function create_menus() {
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
					'menu-item-title' => 'Inicio',
					'menu-item-url' => home_url( '/' ),
					'menu-item-status' => 'publish'
				] );

				$primary_pages = [
					[ 'slug' => 'shop', 'title' => 'Tienda' ],
					[ 'slug' => 'korean', 'title' => 'Skincare coreano' ],
					[ 'slug' => 'makeup', 'title' => 'Maquillaje' ],
					[ 'slug' => 'vegan', 'title' => 'Belleza vegana' ],
					[ 'slug' => 'learn', 'title' => 'Aprender' ],
					[ 'slug' => 'rewards', 'title' => 'Recompensas' ],
					[ 'slug' => 'about', 'title' => 'Sobre Skin Cupid' ],
					[ 'slug' => 'contact', 'title' => 'Contacto' ],
				];

				foreach ( $primary_pages as $page ) {
					$page_obj = get_page_by_path( $page['slug'] );
					if ( ! $page_obj ) {
						continue;
					}

					wp_update_nav_menu_item( $primary_id, 0, [
						'menu-item-title' => $page['title'],
						'menu-item-object-id' => $page_obj->ID,
						'menu-item-object' => 'page',
						'menu-item-type' => 'post_type',
						'menu-item-status' => 'publish'
					] );
				}
			}
		}

		if ( ! is_wp_error( $footer_id ) ) {
			$items = wp_get_nav_menu_items( $footer_id );
			if ( empty( $items ) ) {
				$footer_pages = [
					[ 'slug' => 'faqs', 'title' => 'Preguntas frecuentes' ],
					[ 'slug' => 'shipping', 'title' => 'Envíos' ],
					[ 'slug' => 'privacy-policy', 'title' => 'Política de privacidad' ],
					[ 'slug' => 'terms', 'title' => 'Términos y condiciones' ],
					[ 'slug' => 'rewards', 'title' => 'Recompensas' ],
					[ 'slug' => 'store-locator', 'title' => 'Localizador de tiendas' ],
					[ 'slug' => 'contact', 'title' => 'Contacto' ],
				];

				foreach ( $footer_pages as $page ) {
					$page_obj = get_page_by_path( $page['slug'] );
					if ( ! $page_obj ) {
						continue;
					}

					wp_update_nav_menu_item( $footer_id, 0, [
						'menu-item-title' => $page['title'],
						'menu-item-object-id' => $page_obj->ID,
						'menu-item-object' => 'page',
						'menu-item-type' => 'post_type',
						'menu-item-status' => 'publish'
					] );
				}
			}
		}
	}
}
