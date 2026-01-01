<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() { return 'sk_hero'; }

	public function get_title() { return __( 'SK Hero Slider (Skin Cupid)', 'skincare' ); }

	public function get_icon() { return 'eicon-slides'; }

	public function get_categories() { return [ 'skincare-widgets' ]; }

    public function get_script_depends() {
		return [ 'swiper' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( '2026 holy grail beauty edit', 'skincare' ),
			]
		);

        $repeater->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'NEW IN', 'skincare' ),
			]
		);

        $repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'skincare' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

        $repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'skincare' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Explorar el edit', 'skincare' ),
			]
		);

        $repeater->add_control(
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

        $this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'skincare' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( '2026 holy grail beauty edit', 'skincare' ),
                        'subtitle' => __( 'NEW IN', 'skincare' ),
					],
                    [
						'title' => __( 'The Best of K-Beauty', 'skincare' ),
                        'subtitle' => __( 'TRENDING', 'skincare' ),
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);

        $this->add_control(
			'slider_speed',
			[
				'label' => esc_html__( 'Velocidad (ms)', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5000,
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Estilo', 'skincare' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'title_color',
			[
				'label' => __( 'Color Título', 'skincare' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sk-hero-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        if ( empty( $settings['slides'] ) ) {
			return;
		}
		?>
		<div class="sk-hero-slider swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ( $settings['slides'] as $slide ) : ?>
                    <div class="swiper-slide sk-hero-slide" style="background-image: url('<?php echo esc_url($slide['image']['url']); ?>');">
                        <div class="sk-hero-content sk-container">
                            <?php if($slide['subtitle']) : ?>
                                <span class="sk-hero-subtitle animate-up"><?php echo esc_html($slide['subtitle']); ?></span>
                            <?php endif; ?>

                            <h2 class="sk-hero-title animate-up delay-1"><?php echo esc_html($slide['title']); ?></h2>

                            <?php if($slide['button_text']) : ?>
                                <a href="<?php echo esc_url($slide['link']['url']); ?>" class="btn btn-primary animate-up delay-2">
                                    <?php echo esc_html($slide['button_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
		</div>

        <script>
        jQuery(document).ready(function($) {
            new Swiper('.elementor-element-<?php echo $this->get_id(); ?> .swiper-container', {
                loop: true,
                autoplay: {
                    delay: <?php echo esc_js($settings['slider_speed']); ?>,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: { crossFade: true },
            });
        });
        </script>
        <style>
            .sk-hero-slider { height: 600px; width: 100%; position: relative; }
            .sk-hero-slide {
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            .sk-hero-content {
                background: rgba(255,255,255,0.7);
                padding: 40px;
                border-radius: var(--radius-md);
                max-width: 600px;
                animation: fadeIn 1s;
            }
            .sk-hero-title {
                font-family: var(--font-family-heading);
                font-size: var(--text-5xl);
                color: var(--c-text-heading);
                margin: 20px 0;
            }
            .sk-hero-subtitle {
                font-family: var(--font-family-base);
                text-transform: uppercase;
                letter-spacing: 2px;
                font-weight: 600;
                color: var(--c-text-light);
            }
            /* Animations */
            .animate-up { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s forwards; }
            .delay-1 { animation-delay: 0.2s; }
            .delay-2 { animation-delay: 0.4s; }
            @keyframes fadeInUp {
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
		<?php
	}
}
