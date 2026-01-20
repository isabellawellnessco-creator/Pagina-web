<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;

class F8_Hero extends F8_Widget_Base {

	public function get_name() {
		return 'f8_hero';
	}

	public function get_title() {
		return __( 'F8 Hero', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Contenido', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Título', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Título Hero', 'skincare' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtítulo', 'skincare' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Subtítulo descriptivo aquí.', 'skincare' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Imagen de Fondo', 'skincare' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label' => __( 'Texto Botón', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ver más', 'skincare' ),
			]
		);

		$this->add_control(
			'btn_url',
			[
				'label' => __( 'Enlace Botón', 'skincare' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://tu-enlace.com', 'skincare' ),
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->end_controls_section();

		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image_url = !empty($settings['image']['url']) ? $settings['image']['url'] : '';

		// Structure mimicking F8 Hero (inferred)
		?>
		<div class="f8-hero <?php echo esc_attr( $settings['f8_custom_class'] ); ?>" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
			<div class="f8-hero__overlay"></div>
			<div class="f8-hero__content container">
				<h1 class="f8-hero__title"><?php echo esc_html( $settings['title'] ); ?></h1>
				<?php if ( $settings['subtitle'] ) : ?>
					<p class="f8-hero__subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></p>
				<?php endif; ?>

				<?php if ( $settings['btn_text'] ) : ?>
					<a href="<?php echo esc_url( $settings['btn_url']['url'] ); ?>" class="f8-btn f8-btn--primary">
						<?php echo esc_html( $settings['btn_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
