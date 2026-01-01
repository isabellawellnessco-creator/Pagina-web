<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Category_Tiles_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_cat_tiles'; }
	public function get_title() { return __( 'SK Category Tiles', 'skincare' ); }
	public function get_icon() { return 'eicon-gallery-grid'; }
	public function get_categories() { return [ 'general' ]; }

	protected function register_controls() {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Categories', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        // Repeater for tiles
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'cat_title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT ]
		);
        $repeater->add_control(
			'cat_image', [ 'label' => 'Image', 'type' => \Elementor\Controls_Manager::MEDIA ]
		);
        $repeater->add_control(
			'cat_link', [ 'label' => 'Link', 'type' => \Elementor\Controls_Manager::URL ]
		);

        $this->add_control(
			'tiles',
			[
				'label' => __( 'Tiles', 'skincare' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
                'default' => [
                    ['cat_title' => 'Limpiadores'],
                    ['cat_title' => 'Tonicos'],
                    ['cat_title' => 'Serums'],
                    ['cat_title' => 'Hidratantes'],
                ]
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
        ?>
        <div class="sk-category-grid">
            <?php foreach($settings['tiles'] as $tile) : ?>
                <a href="<?php echo esc_url($tile['cat_link']['url']); ?>" class="sk-cat-tile">
                    <div class="sk-cat-image">
                        <img src="<?php echo esc_url($tile['cat_image']['url']); ?>" alt="<?php echo esc_attr($tile['cat_title']); ?>">
                    </div>
                    <span class="sk-cat-label"><?php echo esc_html($tile['cat_title']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
