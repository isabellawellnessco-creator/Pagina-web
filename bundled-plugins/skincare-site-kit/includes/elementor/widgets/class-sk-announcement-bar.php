<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Skincare_Announcement_Bar extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_announcement_bar';
	}

	public function get_title() {
		return esc_html__( 'Barra de Anuncios', 'skincare-site-kit' );
	}

	public function get_icon() {
		return 'eicon-alert';
	}

	public function get_categories() {
		return [ 'skincare-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Mensajes', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'message_text',
			[
				'label' => esc_html__( 'Texto', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Envío gratis a partir de S/ 200', 'skincare-site-kit' ),
			]
		);

        $repeater->add_control(
			'message_link',
			[
				'label' => esc_html__( 'Enlace (Opcional)', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://...', 'skincare-site-kit' ),
			]
		);

		$this->add_control(
			'messages',
			[
				'label' => esc_html__( 'Lista de Anuncios', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'message_text' => 'Envío gratis a partir de S/ 200' ],
					[ 'message_text' => 'Únete a nuestro programa de fidelidad' ],
				],
				'title_field' => '{{{ message_text }}}',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Estilo', 'skincare-site-kit' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Color de Fondo', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f877a0',
                'selectors' => [
					'{{WRAPPER}} .sk-announcement-bar' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color de Texto', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
                'selectors' => [
					'{{WRAPPER}} .sk-announcement-bar' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .sk-announcement-bar a' => 'color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['messages'] ) ) {
			return;
		}
		?>
		<div class="sk-announcement-bar swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $settings['messages'] as $item ) : ?>
					<div class="swiper-slide sk-announcement-slide">
                        <div class="sk-container text-center">
                            <?php if(!empty($item['message_link']['url'])): ?>
                                <a href="<?php echo esc_url($item['message_link']['url']); ?>">
                                    <?php echo esc_html( $item['message_text'] ); ?>
                                </a>
                            <?php else: ?>
                                <span><?php echo esc_html( $item['message_text'] ); ?></span>
                            <?php endif; ?>
                        </div>
					</div>
				<?php endforeach; ?>
			</div>
            <!-- Navigation arrows if needed, usually just autoplay -->
		</div>
        <style>
            .sk-announcement-bar { padding: 8px 0; font-size: var(--text-xs); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
            .sk-announcement-slide { display: flex; justify-content: center; align-items: center; }
        </style>
        <script>
        jQuery(document).ready(function($) {
            new Swiper('.elementor-element-<?php echo $this->get_id(); ?> .sk-announcement-bar', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: { crossFade: true }
            });
        });
        </script>
		<?php
	}
}
