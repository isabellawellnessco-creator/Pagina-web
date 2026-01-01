<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Skincare_Announcement_Bar extends \Elementor\Widget_Base {

	public function get_name() {
		return 'skincare_announcement_bar';
	}

	public function get_title() {
		return esc_html__( 'Barra de Anuncios (Skin Cupid)', 'skincare-site-kit' );
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
				'label' => esc_html__( 'Texto del Mensaje', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Free UK Delivery Over £30', 'skincare-site-kit' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'message_link',
			[
				'label' => esc_html__( 'Enlace (Opcional)', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'skincare-site-kit' ),
				'default' => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'announcements',
			[
				'label' => esc_html__( 'Lista de Anuncios', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'message_text' => esc_html__( 'Beauty Subscription box Shop Now', 'skincare-site-kit' ),
					],
					[
						'message_text' => esc_html__( 'Free UK Delivery Over £30', 'skincare-site-kit' ),
					],
				],
				'title_field' => '{{{ message_text }}}',
			]
		);

        $this->add_control(
			'scroll_speed',
			[
				'label' => esc_html__( 'Velocidad de Scroll (ms)', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'default' => 3000,
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
			'background_color',
			[
				'label' => esc_html__( 'Color de Fondo', 'skincare-site-kit' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffeeef', // Brand Primary
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
                'default' => '#00246b', // Brand Heading
				'selectors' => [
					'{{WRAPPER}} .sk-announcement-item' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .sk-announcement-item a' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .sk-announcement-item',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        if ( empty( $settings['announcements'] ) ) {
			return;
		}
		?>
		<div class="sk-announcement-bar" role="region" aria-label="Anuncios">
            <div class="sk-announcement-slider" data-speed="<?php echo esc_attr($settings['scroll_speed']); ?>">
			<?php foreach ( $settings['announcements'] as $index => $item ) : ?>
                <div class="sk-announcement-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php if ( ! empty( $item['message_link']['url'] ) ) : ?>
                        <a href="<?php echo esc_url( $item['message_link']['url'] ); ?>">
                            <?php echo esc_html( $item['message_text'] ); ?>
                        </a>
                    <?php else : ?>
                        <span><?php echo esc_html( $item['message_text'] ); ?></span>
                    <?php endif; ?>
                </div>
			<?php endforeach; ?>
            </div>
		</div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.elementor-element-<?php echo $this->get_id(); ?> .sk-announcement-slider');
            if(!slider) return;
            const items = slider.querySelectorAll('.sk-announcement-item');
            if(items.length < 2) return;

            let currentIndex = 0;
            const speed = parseInt(slider.getAttribute('data-speed')) || 3000;

            setInterval(() => {
                items[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % items.length;
                items[currentIndex].classList.add('active');
            }, speed);
        });
        </script>
        <style>
            .sk-announcement-bar {
                text-align: center;
                padding: 10px;
                overflow: hidden;
                position: relative;
            }
            .sk-announcement-item {
                display: none;
                animation: skFadeIn 0.5s ease-in-out;
            }
            .sk-announcement-item.active {
                display: block;
            }
            @keyframes skFadeIn {
                from { opacity: 0; transform: translateY(5px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
		<?php
	}
}
