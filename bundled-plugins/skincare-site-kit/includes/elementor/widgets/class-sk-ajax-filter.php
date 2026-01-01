<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Ajax_Filter_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_ajax_filter';
	}

	public function get_title() {
		return esc_html__( 'Filtros AJAX (Skin Cupid)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-filter';
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
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomía', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'product_cat' => 'Categoría',
					'product_tag' => 'Etiqueta',
                    // Attributes would appear here dynamically in a full implementation
				],
				'default' => 'product_cat',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $taxonomy = $settings['taxonomy'];

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
		?>
		<div class="sk-ajax-filter" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
            <h4 class="sk-filter-title"><?php _e('Filtrar por', 'skincare-site-kit'); ?></h4>
            <ul class="sk-filter-list">
                <?php foreach ( $terms as $term ) : ?>
                    <li>
                        <label>
                            <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                            <span class="count">(<?php echo esc_html($term->count); ?>)</span>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
		</div>
        <script>
        jQuery(document).ready(function($) {
            $('.sk-ajax-filter input').on('change', function() {
                const wrapper = $(this).closest('.sk-ajax-filter');
                const taxonomy = wrapper.data('taxonomy');
                const selected = [];

                wrapper.find('input:checked').each(function() {
                    selected.push($(this).val());
                });

                // Trigger Filter Event (Archive page should listen to this)
                $(document).trigger('skincare_filter_change', [{ taxonomy: taxonomy, terms: selected }]);
            });
        });
        </script>
        <style>
            .sk-filter-title { margin-bottom: 10px; font-family: var(--font-family-heading); }
            .sk-filter-list { list-style: none; padding: 0; margin: 0; }
            .sk-filter-list li { margin-bottom: 5px; }
            .sk-filter-list label { cursor: pointer; display: flex; align-items: center; }
            .sk-filter-list input { margin-right: 8px; }
            .sk-filter-list .count { color: var(--c-text-light); font-size: 0.8rem; margin-left: auto; }
        </style>
		<?php
	}
}
