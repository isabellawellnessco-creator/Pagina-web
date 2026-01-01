<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_hero';
	}

	public function get_title() {
		return esc_html__( 'Hero Slider (Skin Cupid)', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'skincare-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Diapositivas', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Imagen', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

        $repeater->add_control(
			'mobile_image',
			[
				'label' => esc_html__( 'Imagen Móvil', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtítulo', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Nueva Colección', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Título', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Descubre nuestros favoritos 2026', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Texto Botón', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Comprar Ahora', 'skincare-site-kit' ),
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Enlace Botón', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'skincare-site-kit' ),
			]
		);

        $repeater->add_control(
			'content_align',
			[
				'label' => esc_html__( 'Alineación', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left' ],
					'center' => [ 'title' => 'Centro', 'icon' => 'eicon-text-align-center' ],
					'right' => [ 'title' => 'Derecha', 'icon' => 'eicon-text-align-right' ],
				],
				'default' => 'center',
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => 'Descubre nuestros favoritos 2026',
						'subtitle' => 'Lo mejor de K-Beauty',
					],
					[
						'title' => 'Rutina Coreana Esencial',
						'subtitle' => 'Piel radiante en 10 pasos',
					],
				],
				'title_field' => '{{{ title }}}',
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
		<div class="sk-hero-slider swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $settings['slides'] as $slide ) :
                    $image_url = $slide['image']['url'];
                    $mobile_image_url = !empty($slide['mobile_image']['url']) ? $slide['mobile_image']['url'] : $image_url;
                ?>
					<div class="swiper-slide sk-hero-slide text-<?php echo esc_attr($slide['content_align']); ?>">
                        <div class="sk-hero-bg desktop-bg" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                        <div class="sk-hero-bg mobile-bg" style="background-image: url('<?php echo esc_url($mobile_image_url); ?>');"></div>

                        <div class="sk-container">
                            <div class="sk-hero-content" data-aos="fade-up">
                                <?php if($slide['subtitle']): ?>
                                    <span class="sk-hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></span>
                                <?php endif; ?>

                                <?php if($slide['title']): ?>
                                    <h2 class="sk-hero-title"><?php echo esc_html($slide['title']); ?></h2>
                                <?php endif; ?>

                                <?php if($slide['button_text']): ?>
                                    <a href="<?php echo esc_url($slide['button_link']['url']); ?>" class="btn btn-primary btn-ripple">
                                        <?php echo esc_html($slide['button_text']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
					</div>
				<?php endforeach; ?>
			</div>
            <div class="swiper-pagination"></div>
		</div>

        <script>
        jQuery(document).ready(function($) {
            new Swiper('.elementor-element-<?php echo $this->get_id(); ?> .sk-hero-slider', {
                loop: true,
                autoplay: { delay: 6000 },
                pagination: { el: '.swiper-pagination', clickable: true },
                effect: 'fade',
                fadeEffect: { crossFade: true }
            });
        });
        </script>

        <style>
            .sk-hero-slider { height: 600px; position: relative; }
            .sk-hero-slide { position: relative; display: flex; align-items: center; height: 100%; width: 100%; }

            .sk-hero-bg {
                position: absolute; top:0; left:0; width:100%; height:100%;
                background-size: cover; background-position: center; z-index: 1;
            }
            .mobile-bg { display: none; }

            .sk-hero-content {
                position: relative; z-index: 2; max-width: 600px; padding: 2rem;
                background: rgba(255,255,255,0.85); backdrop-filter: blur(5px);
                border-radius: var(--radius-lg);
            }

            .text-center .sk-container { display: flex; justify-content: center; }
            .text-right .sk-container { display: flex; justify-content: flex-end; }
            .text-center .sk-hero-content { text-align: center; }
            .text-right .sk-hero-content { text-align: right; }

            .sk-hero-subtitle {
                display: block; font-family: var(--font-family-heading);
                font-size: var(--text-sm); letter-spacing: 2px; text-transform: uppercase;
                color: var(--c-text-light); margin-bottom: 1rem;
            }
            .sk-hero-title {
                font-size: var(--text-4xl); font-weight: 700; color: var(--c-text-heading);
                margin-bottom: 1.5rem; line-height: 1.1;
            }

            @media (max-width: 768px) {
                .sk-hero-slider { height: 500px; }
                .desktop-bg { display: none; }
                .mobile-bg { display: block; }
                .sk-hero-content { width: 90%; margin: 0 auto; padding: 1.5rem; }
                .sk-hero-title { font-size: var(--text-2xl); }
            }
        </style>
		<?php
	}
}
