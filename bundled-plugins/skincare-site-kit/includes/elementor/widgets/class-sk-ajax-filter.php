<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Ajax_Filter_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_ajax_filter';
	}

	public function get_title() {
		return esc_html__( 'Filtros AJAX (Sidebar)', 'skincare-site-kit' );
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
				'label' => esc_html__( 'Filtros Activos', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        // In a real plugin, we would fetch registered attributes.
        // For now, we hardcode the ones we know we are creating.
		$this->add_control(
			'show_skin_type',
			[
				'label' => esc_html__( 'Mostrar Tipo de Piel', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
			]
		);

        $this->add_control(
			'show_concern',
			[
				'label' => esc_html__( 'Mostrar Preocupación', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
			]
		);

        $this->add_control(
			'show_ingredient',
			[
				'label' => esc_html__( 'Mostrar Ingredientes', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        // Map UI labels to Taxonomies
        $filters = [];
        if($settings['show_skin_type']) $filters['pa_tipo-de-piel'] = 'Tipo de Piel';
        if($settings['show_concern']) $filters['pa_preocupacion'] = 'Preocupación';
        if($settings['show_ingredient']) $filters['pa_ingrediente'] = 'Ingredientes';

		?>
		<div class="sk-sidebar-filters">
            <?php foreach($filters as $tax => $label):
                $terms = get_terms([ 'taxonomy' => $tax, 'hide_empty' => true ]);
                if ( empty( $terms ) || is_wp_error( $terms ) ) continue;
            ?>
            <div class="sk-filter-group" data-taxonomy="<?php echo esc_attr($tax); ?>">
                <h4 class="sk-filter-title"><?php echo esc_html($label); ?></h4>
                <ul class="sk-filter-list">
                    <?php foreach ( $terms as $term ) : ?>
                        <li>
                            <label class="sk-checkbox-label">
                                <input type="checkbox" value="<?php echo esc_attr($term->slug); ?>">
                                <span class="checkmark"></span>
                                <?php echo esc_html($term->name); ?>
                                <span class="count">(<?php echo esc_html($term->count); ?>)</span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
		</div>

        <script>
        jQuery(document).ready(function($) {
            $('.sk-sidebar-filters input').on('change', function() {
                // Collect all active filters
                const activeFilters = {};

                $('.sk-filter-group').each(function() {
                    const tax = $(this).data('taxonomy');
                    const values = [];
                    $(this).find('input:checked').each(function() {
                        values.push($(this).val());
                    });
                    if(values.length > 0) {
                        activeFilters[tax] = values.join(',');
                    }
                });

                // Trigger Global Event
                $(document).trigger('skincare_filter_update', [activeFilters]);
            });
        });
        </script>

        <style>
            .sk-sidebar-filters { padding: 20px; background: #fff; border: 1px solid var(--c-border); border-radius: var(--radius-md); }
            .sk-filter-group { margin-bottom: 25px; border-bottom: 1px solid var(--c-bg-light); padding-bottom: 20px; }
            .sk-filter-group:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
            .sk-filter-title { font-size: 1rem; margin-bottom: 15px; font-weight: 600; color: var(--c-text-heading); }

            .sk-filter-list { list-style: none; padding: 0; margin: 0; max-height: 200px; overflow-y: auto; }
            .sk-filter-list li { margin-bottom: 8px; }

            .sk-checkbox-label {
                cursor: pointer; display: flex; align-items: center; font-size: 0.9rem; color: var(--c-text-main);
                position: relative; padding-left: 28px;
            }
            .sk-checkbox-label input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
            .checkmark {
                position: absolute; top: 0; left: 0; height: 18px; width: 18px;
                background-color: #eee; border-radius: 4px;
            }
            .sk-checkbox-label:hover input ~ .checkmark { background-color: #ccc; }
            .sk-checkbox-label input:checked ~ .checkmark { background-color: var(--c-accent); }
            .checkmark:after {
                content: ""; position: absolute; display: none;
                left: 6px; top: 2px; width: 5px; height: 10px;
                border: solid white; border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }
            .sk-checkbox-label input:checked ~ .checkmark:after { display: block; }

            .sk-filter-list .count { margin-left: auto; color: var(--c-text-light); font-size: 0.75rem; }
        </style>
		<?php
	}
}
