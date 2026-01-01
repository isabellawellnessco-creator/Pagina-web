<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_USP_Bar_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_usp_bar'; }
	public function get_title() { return __( 'SK USP Bar', 'skincare' ); }
	public function get_icon() { return 'eicon-info-box'; }
	public function get_categories() { return [ 'general' ]; }

	protected function register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Items', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('text', [ 'label' => 'Text', 'type' => \Elementor\Controls_Manager::TEXT ]);
        $repeater->add_control('icon', [ 'label' => 'Icon', 'type' => \Elementor\Controls_Manager::ICONS ]);

        $this->add_control(
			'items',
			[
				'label' => __( 'USPs', 'skincare' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'default' => [
                    ['text' => 'Envíos Gratis > $50'],
                    ['text' => 'Muestras Gratis'],
                    ['text' => 'Cruelty Free'],
                ]
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
        ?>
        <div class="sk-usp-bar">
            <?php foreach($settings['items'] as $item) : ?>
                <div class="sk-usp-item">
                    <?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    <span><?php echo esc_html($item['text']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
