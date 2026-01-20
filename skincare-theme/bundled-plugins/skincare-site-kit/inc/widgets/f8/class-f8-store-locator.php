<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;

class F8_StoreLocator extends F8_Widget_Base {

	public function get_name() {
		return 'f8_store_locator';
	}

	public function get_title() {
		return __( 'F8 Store Locator', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Configuración', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Título', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Encuentra tu tienda', 'skincare' ),
			]
		);

		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="f8-store-locator <?php echo esc_attr( $settings['f8_custom_class'] ); ?>">
			<div class="f8-store-locator__header">
				<h2><?php echo esc_html( $settings['title'] ); ?></h2>
				<div class="f8-store-locator__search">
					<input type="text" id="sk-store-search-input" placeholder="<?php esc_attr_e( 'Buscar por ciudad o código postal...', 'skincare' ); ?>">
					<button id="sk-store-search-btn" class="f8-btn"><?php esc_html_e( 'Buscar', 'skincare' ); ?></button>
				</div>
			</div>

			<div class="f8-store-locator__body">
				<div class="f8-store-locator__list" id="sk-store-results">
					<!-- Results populated via JS -->
					<p class="sk-loading-msg" style="display:none;"><?php esc_html_e( 'Buscando...', 'skincare' ); ?></p>
				</div>
				<div class="f8-store-locator__map" id="sk-store-map">
					<!-- Map placeholder -->
					<div class="sk-map-placeholder-box">
						<p><?php esc_html_e( 'Mapa interactivo', 'skincare' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<script>
		jQuery(document).ready(function($) {
			const $results = $('#sk-store-results');
			const $btn = $('#sk-store-search-btn');
			const $input = $('#sk-store-search-input');

			function fetchStores(query = '') {
				$results.html('<p class="sk-loading-msg">Loading...</p>');
				$.ajax({
					url: sk_vars.rest_url + 'stores',
					method: 'GET',
					data: { search: query },
					beforeSend: function(xhr) {
						xhr.setRequestHeader('X-WP-Nonce', sk_vars.rest_nonce);
					},
					success: function(response) {
						$results.empty();
						if (response.length === 0) {
							$results.append('<p>No se encontraron tiendas.</p>');
							return;
						}
						response.forEach(store => {
							const html = `
								<div class="f8-store-item">
									<h3>${store.title}</h3>
									<p>${store.address}, ${store.city}</p>
									<p>${store.phone}</p>
									${store.hours ? `<div class="f8-store-hours">${store.hours}</div>` : ''}
								</div>
							`;
							$results.append(html);
						});
					}
				});
			}

			// Load all initially
			fetchStores();

			$btn.on('click', function() {
				fetchStores($input.val());
			});
		});
		</script>
		<style>
			/* Minimal styles for locator */
			.f8-store-locator { display: flex; flex-direction: column; gap: 20px; }
			.f8-store-locator__body { display: flex; gap: 20px; }
			.f8-store-locator__list { flex: 1; max-height: 500px; overflow-y: auto; }
			.f8-store-locator__map { flex: 2; background: #eee; min-height: 400px; border-radius: 8px; }
			.f8-store-item { padding: 15px; border-bottom: 1px solid #ddd; }
			.f8-store-item:last-child { border-bottom: none; }
			.sk-map-placeholder-box { height: 100%; display: flex; align-items: center; justify-content: center; color: #999; }
		</style>
		<?php
	}
}
