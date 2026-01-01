<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Ajax_Search_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_ajax_search';
	}

	public function get_title() {
		return esc_html__( 'Búsqueda AJAX (Skin Cupid)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'skincare-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Configuración', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Buscar...', 'skincare-site-kit' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="sk-ajax-search-wrapper">
			<form role="search" method="get" class="sk-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="sk-search-field" placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>" value="" name="s" autocomplete="off" />
				<button type="submit" class="sk-search-submit">
                    <i class="fas fa-search"></i>
                </button>
                <div class="sk-search-spinner" style="display:none;"><i class="fas fa-spinner fa-spin"></i></div>
			</form>
			<div class="sk-search-results"></div>
		</div>

        <script>
        jQuery(document).ready(function($) {
            let timer;
            const $wrapper = $('.elementor-element-<?php echo $this->get_id(); ?> .sk-ajax-search-wrapper');
            const $input = $wrapper.find('.sk-search-field');
            const $results = $wrapper.find('.sk-search-results');
            const $spinner = $wrapper.find('.sk-search-spinner');

            $input.on('keyup', function() {
                clearTimeout(timer);
                const query = $(this).val();

                if (query.length < 3) {
                    $results.hide().html('');
                    return;
                }

                timer = setTimeout(function() {
                    $spinner.show();
                    $.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        type: 'POST',
                        data: {
                            action: 'skincare_ajax_search',
                            term: query
                        },
                        success: function(response) {
                            $spinner.hide();
                            if(response.success && response.data.length > 0) {
                                let html = '<ul>';
                                response.data.forEach(item => {
                                    html += `<li>
                                        <a href="${item.url}" class="sk-search-item">
                                            <div class="sk-search-thumb">${item.thumb}</div>
                                            <div class="sk-search-info">
                                                <div class="sk-search-title">${item.title}</div>
                                                <div class="sk-search-price">${item.price}</div>
                                            </div>
                                        </a>
                                    </li>`;
                                });
                                html += '</ul>';
                                $results.html(html).show();
                            } else {
                                $results.html('<div class="sk-no-results">No se encontraron productos.</div>').show();
                            }
                        }
                    });
                }, 500);
            });

            // Close when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.sk-ajax-search-wrapper').length) {
                    $results.hide();
                }
            });
        });
        </script>
        <style>
            .sk-ajax-search-wrapper { position: relative; width: 100%; }
            .sk-search-form { display: flex; align-items: center; border-bottom: 1px solid #e5e5e5; }
            .sk-search-field { border: none; width: 100%; padding: 10px; font-family: var(--font-family-base); outline: none; background: transparent; }
            .sk-search-submit { background: none; border: none; cursor: pointer; color: var(--c-text-heading); }
            .sk-search-results {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #fff;
                border: 1px solid #e5e5e5;
                z-index: 1000;
                display: none;
                max-height: 400px;
                overflow-y: auto;
                box-shadow: var(--shadow-md);
            }
            .sk-search-results ul { list-style: none; margin: 0; padding: 0; }
            .sk-search-results li { border-bottom: 1px solid #f0f0f0; }
            .sk-search-item { display: flex; align-items: center; padding: 10px; text-decoration: none; color: inherit; transition: background 0.2s; }
            .sk-search-item:hover { background: #fafafa; }
            .sk-search-thumb img { width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 4px; }
            .sk-search-title { font-weight: 600; font-size: 0.9rem; color: var(--c-text-heading); }
            .sk-search-price { font-size: 0.85rem; color: var(--c-text-main); }
            .sk-no-results { padding: 15px; text-align: center; color: var(--c-text-light); }
            .sk-search-spinner { margin-left: 5px; }
        </style>
		<?php
	}
}
