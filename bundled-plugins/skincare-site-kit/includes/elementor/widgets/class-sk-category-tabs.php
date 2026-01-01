<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Category_Tabs_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_category_tabs';
	}

	public function get_title() {
		return esc_html__( 'Categorías en Pestañas (Skin Cupid)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return [ 'skincare-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Categorías', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Título Pestaña', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Skincare', 'skincare-site-kit' ),
			]
		);

        $repeater->add_control(
			'category_slug',
			[
				'label' => esc_html__( 'Slug Categoría', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'description' => 'Ej: skincare, make-up',
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Pestañas', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'tab_title' => 'Skincare', 'category_slug' => 'skincare' ],
					[ 'tab_title' => 'Make Up', 'category_slug' => 'make-up' ],
                    [ 'tab_title' => 'Hair & Body', 'category_slug' => 'hair-body' ],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        $tabs = $settings['tabs'];
		?>
		<div class="sk-category-tabs">
            <div class="sk-tabs-nav">
                <?php foreach ( $tabs as $index => $tab ) : ?>
                    <button class="sk-tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-target="tab-<?php echo $index; ?>">
                        <?php echo esc_html( $tab['tab_title'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="sk-tabs-content">
                <?php foreach ( $tabs as $index => $tab ) : ?>
                    <div id="tab-<?php echo $index; ?>" class="sk-tab-pane <?php echo $index === 0 ? 'active' : ''; ?>">
                        <?php
                        // Use shortcode for simplicity in prototype, allows using existing loop designs
                        // [products limit="4" columns="4" category="slug"]
                        echo do_shortcode( sprintf( '[products limit="4" columns="4" category="%s"]', esc_attr( $tab['category_slug'] ) ) );
                        ?>
                        <div class="sk-tab-footer">
                            <a href="<?php echo esc_url( get_term_link( $tab['category_slug'], 'product_cat' ) ); ?>" class="btn btn-secondary">
                                <?php _e('View All', 'skincare-site-kit'); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
		</div>
        <script>
        jQuery(document).ready(function($) {
            $('.sk-tab-btn').on('click', function() {
                const target = $(this).data('target');
                const wrapper = $(this).closest('.sk-category-tabs');

                wrapper.find('.sk-tab-btn').removeClass('active');
                $(this).addClass('active');

                wrapper.find('.sk-tab-pane').removeClass('active').hide();
                wrapper.find('#' + target).addClass('active').fadeIn();
            });
        });
        </script>
        <style>
            .sk-tabs-nav { display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; border-bottom: 2px solid #eee; }
            .sk-tab-btn {
                background: none; border: none;
                padding: 10px 20px;
                font-family: var(--font-family-heading);
                font-size: 1.2rem;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                margin-bottom: -2px;
                color: var(--c-text-light);
            }
            .sk-tab-btn.active { border-bottom-color: var(--c-accent); color: var(--c-text-heading); }
            .sk-tab-pane { display: none; }
            .sk-tab-pane.active { display: block; }
            .sk-tab-footer { text-align: center; margin-top: 20px; }
        </style>
		<?php
	}
}
