<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_hero'; }

	public function get_title() { return __( 'SK Hero', 'skincare' ); }

	public function get_icon() { return 'eicon-banner'; }

	public function get_categories() { return [ 'general' ]; }

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Unlock Your Glow', 'skincare' ),
			]
		);

        $this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'K-Beauty Essentials', 'skincare' ),
			]
		);

        $this->add_control(
			'image',
			[
				'label' => __( 'Image', 'skincare' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_control(
			'link',
			[
				'label' => __( 'Link', 'skincare' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'skincare' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="sk-hero" style="background-image: url('<?php echo esc_url($settings['image']['url']); ?>');">
            <div class="sk-hero-overlay"></div>
            <div class="sk-hero-content sk-container">
                <?php if($settings['subtitle']) : ?>
                    <span class="sk-hero-subtitle"><?php echo esc_html($settings['subtitle']); ?></span>
                <?php endif; ?>

                <h2 class="sk-hero-title"><?php echo esc_html($settings['title']); ?></h2>

                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="btn btn-primary">
                    <?php _e('Comprar Ahora', 'skincare'); ?>
                </a>
            </div>
		</div>
		<?php
	}
}
