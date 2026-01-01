<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Search_Chips_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_search_chips';
	}

	public function get_title() {
		return esc_html__( 'Búsquedas Populares (Chips)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-tags';
	}

	public function get_categories() {
		return [ 'skincare-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Chips', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'chip_text',
			[
				'label' => esc_html__( 'Texto', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'New In', 'skincare-site-kit' ),
			]
		);

        $repeater->add_control(
			'chip_link',
			[
				'label' => esc_html__( 'Enlace', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::URL,
				'default' => [
                    'url' => '#'
                ],
			]
		);

		$this->add_control(
			'chips',
			[
				'label' => esc_html__( 'Lista', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'chip_text' => 'Lifestyle' ],
					[ 'chip_text' => 'Gifting' ],
                    [ 'chip_text' => 'New In' ],
                    [ 'chip_text' => 'Hair & Body' ],
				],
				'title_field' => '{{{ chip_text }}}',
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="sk-search-chips">
            <span class="sk-chips-label"><?php _e('Most Searched:', 'skincare-site-kit'); ?></span>
			<?php foreach ( $settings['chips'] as $item ) : ?>
                <a href="<?php echo esc_url( $item['chip_link']['url'] ); ?>" class="sk-chip">
                    <?php echo esc_html( $item['chip_text'] ); ?>
                </a>
			<?php endforeach; ?>
		</div>
        <style>
            .sk-search-chips { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: center; margin: 20px 0; }
            .sk-chips-label { font-weight: 600; margin-right: 10px; color: var(--c-text-heading); }
            .sk-chip {
                padding: 8px 16px;
                border-radius: 20px;
                background-color: #fff;
                border: 1px solid var(--c-border);
                color: var(--c-text-main);
                text-decoration: none;
                transition: all 0.2s;
                font-size: 0.9rem;
            }
            .sk-chip:hover {
                background-color: var(--c-primary);
                border-color: var(--c-accent);
                transform: translateY(-2px);
                box-shadow: var(--shadow-sm);
            }
        </style>
		<?php
	}
}
