<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seeder {

	const SEED_VERSION = 5;
	const OPTION_NAME  = 'sk_content_seeded_version';
	const LOG_OPTION   = 'sk_seeder_logs';
	const OPTION_VERSION = 'sk_seed_version';
	const OPTION_COMPLETED = 'sk_seed_completed';
	const OPTION_LAST_RUN = 'sk_seed_last_run_at';
	const OPTION_LAST_ERROR = 'sk_seed_last_error';
	const META_SEED_ID = '_sk_seed_id';

	public static function init() {
		// Removed auto-trigger from admin_init to avoid invisible execution.
		// Use Smart Checks to notify admin instead.
		add_action( 'admin_init', [ __CLASS__, 'check_status' ] );
	}

	public static function check_status() {
		// Only run checks for admins
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Don't run on AJAX or during the wizard itself
		if ( wp_doing_ajax() || ( isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'sk-onboarding', 'sk-theme-setup' ] ) ) ) {
			return;
		}

		$results = self::run_smart_check();

		if ( $results['status'] === 'issue' ) {
			add_action( 'admin_notices', function() use ( $results ) {
				$msg = __( 'Skin Cupid Kit: Se detectaron componentes faltantes o desactualizados.', 'skincare' );
				$action_url = wp_nonce_url( admin_url( 'admin.php?page=sk-onboarding&mode=repair' ), 'sk_repair_link' );
				echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html( $msg ) . ' <a href="' . esc_url( $action_url ) . '" class="button button-small">' . __( 'Reparar ahora', 'skincare' ) . '</a></p></div>';
			} );
		}
	}

	public static function run_smart_check( $force = false ) {
		if ( ! $force ) {
			$cached = get_transient( 'sk_smart_check_results' );
			if ( $cached ) {
				return $cached;
			}
		}

		$issues = [];
		$legacy_version = (int) get_option( self::OPTION_NAME, 0 );
		$seeded_version = (int) get_option( self::OPTION_VERSION, $legacy_version );
		$seed_completed = (bool) get_option( self::OPTION_COMPLETED, $legacy_version ? true : false );

		if ( ! $seed_completed || $seeded_version < self::SEED_VERSION ) {
			$issues[] = 'Versión de contenido desactualizada.';
		}

		$settings = get_option( 'sk_theme_builder_settings', [] );
		if ( empty( $settings['global_header'] ) || empty( $settings['global_footer'] ) ) {
			$issues[] = 'Theme Builder (Header/Footer) no configurado.';
		}

		// Check critical pages
		$critical_pages = [ 'home', 'shop', 'rewards', 'account', 'politicas' ];
		foreach ( $critical_pages as $slug ) {
			if ( ! get_page_by_path( $slug ) ) {
				$issues[] = "Página faltante: $slug";
			}
		}

		// Check products (at least one)
		if ( wp_count_posts( 'product' )->publish === 0 ) {
			$issues[] = "No hay productos en la tienda.";
		}

		$result = [
			'status' => empty( $issues ) ? 'ok' : 'issue',
			'issues' => $issues,
			'timestamp' => time()
		];

		set_transient( 'sk_smart_check_results', $result, 15 * MINUTE_IN_SECONDS );
		return $result;
	}

	public static function log_event( $action, $step, $status, $message ) {
		$log_entry = [
			'timestamp' => time(),
			'user_id' => get_current_user_id(),
			'action' => $action,
			'step' => $step,
			'status' => $status,
			'message' => substr( $message, 0, 200 ), // Truncate
			'version' => self::SEED_VERSION
		];

		$logs = get_option( self::LOG_OPTION, [] );
		if ( ! is_array( $logs ) ) $logs = [];

		array_unshift( $logs, $log_entry );
		$logs = array_slice( $logs, 0, 5 ); // Keep last 5

		update_option( self::LOG_OPTION, $logs );
	}

	private static function get_seeded_post( $post_type, $seed_id, $fallback = [] ) {
		$existing = get_posts( [
			'post_type' => $post_type,
			'posts_per_page' => 1,
			'post_status' => 'any',
			'meta_query' => [
				[
					'key' => self::META_SEED_ID,
					'value' => $seed_id,
				],
			],
		] );

		if ( ! empty( $existing ) ) {
			return $existing[0];
		}

		if ( ! empty( $fallback['slug'] ) ) {
			return get_page_by_path( $fallback['slug'] );
		}

		if ( ! empty( $fallback['title'] ) ) {
			return get_page_by_title( $fallback['title'], OBJECT, $post_type );
		}

		return null;
	}

	private static function mark_seeded_post( $post_id, $seed_id ) {
		if ( $post_id && $seed_id ) {
			update_post_meta( $post_id, self::META_SEED_ID, $seed_id );
		}
	}

	private static function mark_seeded_term( $term_id, $seed_id ) {
		if ( $term_id && $seed_id ) {
			update_term_meta( $term_id, self::META_SEED_ID, $seed_id );
		}
	}

	private static function build_elementor_section( array $element ) {
		$section_id = wp_generate_password( 7, false, false );
		$column_id = wp_generate_password( 7, false, false );
		$widget_id = wp_generate_password( 7, false, false );

		$widget = [
			'id' => $widget_id,
			'elType' => 'widget',
			'widgetType' => $element['widget_type'],
			'settings' => $element['settings'] ?? [],
			'elements' => [],
		];

		$column = [
			'id' => $column_id,
			'elType' => 'column',
			'settings' => [
				'_column_size' => 100,
			],
			'elements' => [ $widget ],
		];

		return [
			'id' => $section_id,
			'elType' => 'section',
			'settings' => [],
			'elements' => [ $column ],
		];
	}

	private static function build_elementor_columns_section( array $columns ) {
		$section_id = wp_generate_password( 7, false, false );
		$elements = [];

		foreach ( $columns as $column ) {
			$column_id = wp_generate_password( 7, false, false );
			$widgets = [];
			foreach ( $column['widgets'] as $widget ) {
				$widgets[] = [
					'id' => wp_generate_password( 7, false, false ),
					'elType' => 'widget',
					'widgetType' => $widget['widget_type'],
					'settings' => $widget['settings'] ?? [],
					'elements' => [],
				];
			}

			$elements[] = [
				'id' => $column_id,
				'elType' => 'column',
				'settings' => [
					'_column_size' => $column['size'] ?? 100,
				],
				'elements' => $widgets,
			];
		}

		return [
			'id' => $section_id,
			'elType' => 'section',
			'settings' => [],
			'elements' => $elements,
		];
	}

	private static function build_elementor_data( array $elements ) {
		$sections = [];
		foreach ( $elements as $element ) {
			$sections[] = self::build_elementor_section( $element );
		}

		return $sections;
	}

	private static function should_refresh_elementor_data( $post_id ) {
		$current_data = get_post_meta( $post_id, '_elementor_data', true );
		if ( empty( $current_data ) ) {
			return true;
		}

		$post = get_post( $post_id );
		if ( $post && strpos( $post->post_content, '[' ) !== false && strpos( $post->post_content, 'sk_' ) !== false ) {
			return true;
		}

		return false;
	}

	private static function seed_elementor_data( $post_id, array $elementor_data ) {
		if ( empty( $elementor_data ) ) {
			return;
		}

		if ( self::should_refresh_elementor_data( $post_id ) ) {
			update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $post_id, '_elementor_version', '3.18.0' );
			update_post_meta( $post_id, '_elementor_data', wp_json_encode( $elementor_data ) );
			wp_update_post( [
				'ID' => $post_id,
				'post_content' => '',
			] );
		}
	}

	public static function create_pages() {
		$theme_assets = get_stylesheet_directory_uri() . '/assets/images/';
		// Content definitions
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

		$faqs_content = '
		<section class="sk-page-intro">
			<h2>Preguntas frecuentes</h2>
			<p>Resuelve dudas sobre compras, envíos y devoluciones antes de escribirnos.</p>
		</section>
		';
		$shipping_content = '
		<section class="sk-page-intro">
			<h2>Envíos y devoluciones</h2>
			<p>Conoce los tiempos de entrega y nuestras políticas de devolución.</p>
		</section>
		<section class="sk-page-details">
			<h3>Devoluciones sencillas</h3>
			<ul>
				<li>Solicita devoluciones dentro de los 30 días posteriores a la compra.</li>
				<li>Los productos deben estar sin abrir y en su empaque original.</li>
				<li>Te notificaremos cuando el reembolso sea procesado.</li>
			</ul>
		</section>
		';
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
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'sk_marquee' ],
					[ 'widget_type' => 'sk_hero_slider' ],
					[ 'widget_type' => 'sk_icon_box_grid' ],
					[ 'widget_type' => 'sk_product_grid' ],
					[ 'widget_type' => 'sk_concern_grid' ],
					[ 'widget_type' => 'sk_brand_slider' ],
					[ 'widget_type' => 'sk_instagram_feed' ],
				],
				'template' => 'template-landing.php',
				'seed_id' => 'page_home',
			],
			[
				'title' => 'Tienda',
				'slug' => 'shop',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'sk_product_grid',
						'settings' => [
							'posts_per_page' => 12,
						],
					],
				],
				'template' => 'template-full-width.php',
				'seed_id' => 'page_shop',
			],
			[
				'title' => 'Sobre Skin Cupid',
				'slug' => 'about',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => $about_content,
						],
					],
				],
				'template' => 'template-full-width.php',
				'seed_id' => 'page_about',
			],
			[
				'title' => 'Contacto',
				'slug' => 'contact',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'f8_hero',
						'settings' => [ 'title' => 'Contáctanos', 'subtitle' => 'Estamos aquí para ayudarte.' ]
					],
					[ 'widget_type' => 'f8_contact_form' ],
					[ 'widget_type' => 'f8_faq' ],
				],
				'seed_id' => 'page_contact'
			],
			[
				'title' => 'Preguntas frecuentes',
				'slug' => 'faqs',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'f8_hero',
						'settings' => [ 'title' => 'Preguntas Frecuentes', 'subtitle' => 'Respuestas a tus dudas.' ]
					],
					[ 'widget_type' => 'f8_faq' ],
				],
				'seed_id' => 'page_faqs'
			],
			[
				'title' => 'Envíos',
				'slug' => 'shipping',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'f8_hero',
						'settings' => [ 'title' => 'Envíos y Devoluciones', 'subtitle' => 'Información sobre entregas.' ]
					],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $shipping_content ] ],
				],
				'seed_id' => 'page_shipping'
			],
			[
				'title' => 'Política de privacidad',
				'slug' => 'privacy-policy',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => '
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
						],
					],
				],
				'template' => 'page-privacy.php',
				'seed_id' => 'page_privacy',
			],
			[
				'title' => 'Términos y condiciones',
				'slug' => 'terms',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => '
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
					],
				],
				'seed_id' => 'page_terms',
			],
			[
				'title' => 'Políticas',
				'slug' => 'politicas',
				'content' => '',
				'elementor' => [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => '
							<section class="sk-page-intro">
								<h2>Políticas de Compra y Devoluciones</h2>
								<p>Información esencial para tu experiencia de compra.</p>
							</section>
							<section class="sk-page-details">
								<h3>Aceptación de Políticas</h3>
								<p>Al comprar en Skin Cupid, aceptas nuestros términos de servicio y política de privacidad.</p>
								<ul>
									<li><a href="/terms/">Términos y Condiciones</a></li>
									<li><a href="/privacy-policy/">Política de Privacidad</a></li>
									<li><a href="/shipping/">Envíos y Devoluciones</a></li>
								</ul>
							</section>
							',
						],
					],
				],
				'seed_id' => 'page_politicas',
			],
			[
				'title' => 'Lista de deseos',
				'slug' => 'wishlist',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Tu Wishlist' ] ],
					[ 'widget_type' => 'sk_wishlist_grid' ], // Keeping logic widget, maybe wrap in F8 container if needed
				],
				'seed_id' => 'page_wishlist'
			],
			[
				'title' => 'Recompensas',
				'slug' => 'rewards',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Recompensas' ] ],
					[ 'widget_type' => 'f8_rewards_dashboard' ],
					[ 'widget_type' => 'f8_product_steps', 'settings' => [ 'steps' => [
						[ 'title' => 'Paso 1', 'desc' => 'Únete' ],
						[ 'title' => 'Paso 2', 'desc' => 'Gana puntos' ],
						[ 'title' => 'Paso 3', 'desc' => 'Canjea' ]
					] ] ],
					[ 'widget_type' => 'f8_faq' ],
				],
				'seed_id' => 'page_rewards'
			],
			[
				'title' => 'Mi cuenta',
				'slug' => 'account',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_account_dashboard' ],
				],
				'seed_id' => 'page_account'
			],
			[
				'title' => 'Iniciar sesión',
				'slug' => 'login',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_account_dashboard' ],
				],
				'seed_id' => 'page_login'
			],
			[
				'title' => 'Localizador de tiendas',
				'slug' => 'store-locator',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Nuestras Tiendas' ] ],
					[ 'widget_type' => 'f8_store_locator' ],
				],
				'seed_id' => 'page_store_locator'
			],
			[
				'title' => 'Trabaja con nosotros',
				'slug' => 'care',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Carreras' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $care_content ] ],
				],
				'seed_id' => 'page_care'
			],
			[
				'title' => 'Prensa',
				'slug' => 'skin',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Prensa' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $press_content ] ],
				],
				'seed_id' => 'page_press'
			],
			[
				'title' => 'Skincare coreano',
				'slug' => 'korean',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'K-Beauty' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $korean_content ] ],
				],
				'seed_id' => 'page_korean'
			],
			[
				'title' => 'Maquillaje',
				'slug' => 'makeup',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Maquillaje' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $makeup_content ] ],
				],
				'seed_id' => 'page_makeup'
			],
			[
				'title' => 'Belleza vegana',
				'slug' => 'vegan',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Vegano' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $vegan_content ] ],
				],
				'seed_id' => 'page_vegan'
			],
			[
				'title' => 'Aprender',
				'slug' => 'learn',
				'content' => '',
				'elementor' => [
					[ 'widget_type' => 'f8_hero', 'settings' => [ 'title' => 'Aprende' ] ],
					[ 'widget_type' => 'f8_embed', 'settings' => [ 'html' => $learn_content ] ],
				],
				'seed_id' => 'page_learn'
			],
		];

		$page_ids = [];
		foreach ( $pages as $page ) {
			$existing_page = self::get_seeded_post( 'page', $page['seed_id'], [ 'slug' => $page['slug'] ] );

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
				// Smart update: only if placeholder or empty or explicitly asking for repair
				// But we want to be safe, so we mainly focus on creation.
				// If we want to force update, we could check a flag, but for now we assume existence is enough
				// unless the content is obviously broken/empty.
				$current_content = $existing_page->post_content;

				if ( empty( $current_content ) && ! empty( $page['content'] ) ) {
					wp_update_post( [ 'ID' => $post_id, 'post_content' => $page['content'] ] );
				}
			}

			if ( ! empty( $page['template'] ) && $post_id ) {
				$current_template = get_post_meta( $post_id, '_wp_page_template', true );
				if ( $current_template !== $page['template'] ) {
					update_post_meta( $post_id, '_wp_page_template', $page['template'] );
				}
			}

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				if ( ! empty( $page['elementor'] ) ) {
					self::seed_elementor_data( $post_id, self::build_elementor_data( $page['elementor'] ) );
				}
				self::mark_seeded_post( $post_id, $page['seed_id'] );
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
				wp_update_post( [ 'ID' => $child_id, 'post_parent' => $parent_id ] );
			}
		}
	}

	public static function create_categories() {
		$groups = [
			[
				'name' => 'Limpieza',
				'children' => [ 'Limpiador de aceite', 'Limpiador a base de agua' ],
			],
			[
				'name' => 'Tratamiento',
				'children' => [ 'Exfoliante', 'Tónico', 'Esencia', 'Sérum/Ampolla', 'Mascarilla', 'Mascarillas de tela' ],
			],
			[
				'name' => 'Hidratación y cuidado',
				'children' => [ 'Cuidado de ojos', 'Hidratante', 'Protector solar' ],
			],
			[
				'name' => 'Maquillaje',
				'children' => [ 'Rostro', 'Ojos', 'Labios', 'Herramientas de maquillaje', 'Desmaquillante' ],
			],
			[
				'name' => 'Colecciones',
				'children' => [ 'Sets y regalos', 'Dispositivos', 'Mini tallas', 'Vegano y cruelty-free' ],
			],
		];

		foreach ( $groups as $group ) {
			$parent_id = 0;
			$parent_seed_id = 'product_cat_' . sanitize_title( $group['name'] );
			$parent_term = term_exists( $group['name'], 'product_cat' );
			if ( ! $parent_term ) {
				$created_parent = wp_insert_term( $group['name'], 'product_cat' );
				if ( ! is_wp_error( $created_parent ) ) {
					$parent_id = $created_parent['term_id'];
					self::mark_seeded_term( $parent_id, $parent_seed_id );
				}
			} else {
				$parent_id = is_array( $parent_term ) ? $parent_term['term_id'] : $parent_term;
				self::mark_seeded_term( $parent_id, $parent_seed_id );
			}

			foreach ( $group['children'] as $child_name ) {
				$child_seed_id = 'product_cat_' . sanitize_title( $child_name );
				$child_term = term_exists( $child_name, 'product_cat' );
				if ( ! $child_term ) {
					$created_child = wp_insert_term( $child_name, 'product_cat', [ 'parent' => $parent_id ] );
					if ( ! is_wp_error( $created_child ) ) {
						self::mark_seeded_term( $created_child['term_id'], $child_seed_id );
					}
				} elseif ( $parent_id ) {
					// Ensure hierarchy
					$child_id = is_array( $child_term ) ? $child_term['term_id'] : $child_term;
					$child_obj = get_term( $child_id, 'product_cat' );
					if ( $child_obj && $child_obj->parent == 0 ) {
						wp_update_term( $child_id, 'product_cat', [ 'parent' => $parent_id ] );
					}
					self::mark_seeded_term( $child_id, $child_seed_id );
				}
			}
		}
	}

	public static function upload_placeholder_image() {
		$image_title = 'SkinCupid Placeholder';
		$image_name  = 'skin-cupid-placeholder.svg';

		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment'", $image_title ) );

		if ( ! empty( $attachment ) ) {
			return $attachment[0];
		}

		$image_url = SKINCARE_KIT_URL . 'assets/images/placeholder-product.svg';

		// Load WP libs
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		$tmp = download_url( $image_url );
		if ( is_wp_error( $tmp ) ) return false;

		$file_array = [ 'name' => $image_name, 'tmp_name' => $tmp ];
		$id = media_handle_sideload( $file_array, 0 );

		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return false;
		}

		wp_update_post( [ 'ID' => $id, 'post_title' => $image_title ] );
		return $id;
	}

	public static function create_products() {
		$placeholder_id = self::upload_placeholder_image();
		$demo_products = [
			[ 'name' => 'HaruHaru Wonder - Aceite limpiador Black Rice', 'price' => '19.00', 'cat' => 'Limpiador de aceite', 'seed_id' => 'product_haruharu_black_rice' ],
			[ 'name' => 'Round Lab - Limpiador 1025 Dokdo', 'price' => '14.00', 'cat' => 'Limpiador a base de agua', 'seed_id' => 'product_roundlab_dokdo' ],
			[ 'name' => 'Heimish - Bálsamo limpiador All Clean', 'price' => '16.00', 'cat' => 'Limpiador de aceite', 'seed_id' => 'product_heimish_all_clean' ],
			[ 'name' => 'Some By Mi - Tónico AHA BHA PHA 30 Days', 'price' => '18.00', 'cat' => 'Tónico', 'seed_id' => 'product_somebymi_aha_bha_pha' ],
			[ 'name' => 'COSRX - Esencia Advanced Snail 96 Mucin', 'price' => '21.00', 'cat' => 'Esencia', 'seed_id' => 'product_cosrx_snail' ],
			[ 'name' => 'Anua - Ampolla calmante Heartleaf 80%', 'price' => '24.00', 'cat' => 'Sérum/Ampolla', 'seed_id' => 'product_anua_heartleaf' ],
			[ 'name' => 'Beauty of Joseon - Sérum Glow Deep Rice + Arbutin', 'price' => '17.00', 'cat' => 'Sérum/Ampolla', 'seed_id' => 'product_boj_glow_rice' ],
			[ 'name' => 'Beauty of Joseon - Mascarilla Red Bean Refreshing', 'price' => '18.00', 'cat' => 'Mascarilla', 'seed_id' => 'product_boj_red_bean' ],
			[ 'name' => 'Mediheal - Mascarillas de tela Tea Tree', 'price' => '12.00', 'cat' => 'Mascarillas de tela', 'seed_id' => 'product_mediheal_teatree' ],
			[ 'name' => 'Klairs - Crema de ojos Fundamental Eye Butter', 'price' => '23.00', 'cat' => 'Cuidado de ojos', 'seed_id' => 'product_klairs_eye_butter' ],
			[ 'name' => 'Isntree - Crema hidratante Hyaluronic Acid', 'price' => '20.00', 'cat' => 'Hidratante', 'seed_id' => 'product_isntree_hyaluronic' ],
			[ 'name' => 'Laneige - Lip Sleeping Mask Berry', 'price' => '19.00', 'cat' => 'Labios', 'seed_id' => 'product_laneige_lip_sleeping' ],
			[ 'name' => 'Rom&nd - Juicy Lasting Tint', 'price' => '13.00', 'cat' => 'Labios', 'seed_id' => 'product_romand_juicy_tint' ],
			[ 'name' => 'Etude - Double Lasting Foundation', 'price' => '22.00', 'cat' => 'Rostro', 'seed_id' => 'product_etude_double_lasting' ],
			[ 'name' => 'Dear Dahlia - Sombra de ojos Blooming', 'price' => '25.00', 'cat' => 'Ojos', 'seed_id' => 'product_dear_dahlia_blooming' ],
			[ 'name' => 'Beauty of Joseon - Protector solar Relief Sun: Rice + Probiotics', 'price' => '16.00', 'cat' => 'Protector solar', 'seed_id' => 'product_boj_relief_sun' ],
			[ 'name' => 'Mixsoon - Esencia Bean', 'price' => '28.00', 'cat' => 'Esencia', 'seed_id' => 'product_mixsoon_bean' ],
			[ 'name' => 'Beauty of Joseon - Set de viaje Glow', 'price' => '30.00', 'cat' => 'Sets y regalos', 'seed_id' => 'product_boj_travel_glow' ],
		];

		foreach ( $demo_products as $p ) {
			$existing = self::get_seeded_post( 'product', $p['seed_id'], [ 'title' => $p['name'] ] );
			if ( ! $existing ) {
				$post_id = wp_insert_post( [
					'post_type'    => 'product',
					'post_title'   => $p['name'],
					'post_content' => 'Descripción del producto ' . $p['name'] . '. Ideal para todo tipo de piel, con textura ligera y resultados visibles.',
					'post_status'  => 'publish',
				] );

				if ( $post_id ) {
					update_post_meta( $post_id, '_price', $p['price'] );
					update_post_meta( $post_id, '_regular_price', $p['price'] );
					update_post_meta( $post_id, '_visibility', 'visible' );
					update_post_meta( $post_id, '_stock_status', 'instock' );
					update_post_meta( $post_id, 'ingredients', 'Agua, glicerina, extractos botánicos y activos calmantes.' );
					update_post_meta( $post_id, 'how_to_use', 'Después de limpiar y tonificar, aplica una pequeña cantidad en rostro y cuello.' );

					$term = get_term_by( 'name', $p['cat'], 'product_cat' );
					if ( $term ) wp_set_object_terms( $post_id, $term->term_id, 'product_cat' );

					if ( $placeholder_id ) set_post_thumbnail( $post_id, $placeholder_id );

					self::mark_seeded_post( $post_id, $p['seed_id'] );
				}
			} else {
				if ( $placeholder_id && ! has_post_thumbnail( $existing->ID ) ) {
					set_post_thumbnail( $existing->ID, $placeholder_id );
				}
				self::mark_seeded_post( $existing->ID, $p['seed_id'] );
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

		$header_content = '
		<div class="sk-header-row">
			<div class="sk-logo"><h1>Skin Cupid</h1></div>
			<div class="sk-menu"><span>Menú principal</span></div>
			<div class="sk-icons">
				<a href="#" class="sk-search-trigger"><i class="eicon-search"></i></a>
				<a href="/wishlist/"><i class="eicon-heart"></i></a>
				<a href="#cart-drawer" class="sk-cart-trigger"><i class="eicon-bag-medium"></i></a>
			</div>
		</div>';
		$footer_content = '<div class="sk-footer-content"><p>© ' . date('Y') . ' Skin Cupid. Todos los derechos reservados.</p></div>';
		$archive_content = [];

		$settings = get_option( 'sk_theme_builder_settings', [] );

		foreach ( $parts as $title => $key ) {
			// Check if setting points to a valid post
			$has_valid_setting = false;
			if ( ! empty( $settings[ $key ] ) ) {
				$post = get_post( $settings[ $key ] );
				if ( $post && $post->post_status === 'publish' ) {
					$has_valid_setting = true;
				}
			}

			if ( $has_valid_setting ) continue;

			$content = '';
			$elementor_data = [];
			if ( $key === 'global_header' ) {
				$elementor_data = self::build_elementor_data( [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => $header_content,
						],
					],
				] );
			}
			if ( $key === 'global_footer' ) {
				$elementor_data = self::build_elementor_data( [
					[
						'widget_type' => 'text-editor',
						'settings' => [
							'editor' => $footer_content,
						],
					],
				] );
			}
			if ( $key === 'shop_archive' ) {
				$elementor_data = [
					self::build_elementor_columns_section( [
						[
							'size' => 25,
							'widgets' => [
								[ 'widget_type' => 'sk_ajax_filter' ],
							],
						],
						[
							'size' => 75,
							'widgets' => [
								[
									'widget_type' => 'sk_product_grid',
									'settings' => [
										'posts_per_page' => 12,
									],
								],
							],
						],
					] ),
				];
			}

			$existing = self::get_seeded_post( 'sk_template', 'sk_template_' . $key, [ 'title' => $title ] );
			if ( $existing ) {
				$post_id = $existing->ID;
			} else {
				$post_id = wp_insert_post( [
				'post_type'   => 'sk_template',
				'post_title'  => $title,
				'post_content' => $content,
				'post_status' => 'publish',
				] );
			}

			if ( ! is_wp_error( $post_id ) ) {
				self::seed_elementor_data( $post_id, $elementor_data );
				self::mark_seeded_post( $post_id, 'sk_template_' . $key );
				$settings[ $key ] = $post_id;
			}
		}

		update_option( 'sk_theme_builder_settings', $settings );
	}

	public static function create_menus() {
		$primary = 'Primary Menu';
		$footer = 'Footer Menu';

		$primary_id = 0;
		$footer_id = 0;

		// Handle Primary
		$existing_primary = wp_get_nav_menu_object( $primary );
		if ( ! $existing_primary ) {
			// Try to recover from term_exists error if any
			$term = get_term_by( 'name', $primary, 'nav_menu' );
			if ( $term ) {
				$primary_id = $term->term_id;
			} else {
				$primary_id = wp_create_nav_menu( $primary );
			}
		} else {
			$primary_id = $existing_primary->term_id;
		}

		// Handle Footer
		$existing_footer = wp_get_nav_menu_object( $footer );
		if ( ! $existing_footer ) {
			$term = get_term_by( 'name', $footer, 'nav_menu' );
			if ( $term ) {
				$footer_id = $term->term_id;
			} else {
				$footer_id = wp_create_nav_menu( $footer );
			}
		} else {
			$footer_id = $existing_footer->term_id;
		}

		// Clean up errors if still present
		if ( is_wp_error( $primary_id ) ) $primary_id = 0;
		if ( is_wp_error( $footer_id ) ) $footer_id = 0;

		if ( $primary_id ) {
			self::mark_seeded_term( $primary_id, 'menu_primary' );
		}
		if ( $footer_id ) {
			self::mark_seeded_term( $footer_id, 'menu_footer' );
		}

		// Assign Locations (Always)
		$locations = get_theme_mod( 'nav_menu_locations' );
		if ( $primary_id ) $locations['primary'] = $primary_id;
		if ( $footer_id ) $locations['footer'] = $footer_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		// Populate items ONLY if empty
		if ( $primary_id ) {
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
					if ( $page_obj ) {
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
		}

		if ( $footer_id ) {
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
					if ( $page_obj ) {
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
}
