<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class F8_FAQ extends F8_Widget_Base {

	public function get_name() {
		return 'f8_faq';
	}

	public function get_title() {
		return __( 'F8 FAQ', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Preguntas', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'question',
			[
				'label' => __( 'Pregunta', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '¿Pregunta?', 'skincare' ),
			]
		);

		$repeater->add_control(
			'answer',
			[
				'label' => __( 'Respuesta', 'skincare' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Respuesta aquí.', 'skincare' ),
			]
		);

		$this->add_control(
			'faqs',
			[
				'label' => __( 'FAQs', 'skincare' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'question' => __( '¿Pregunta 1?', 'skincare' ),
						'answer' => __( 'Respuesta 1.', 'skincare' ),
					],
				],
				'title_field' => '{{{ question }}}',
			]
		);

		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="f8-faq <?php echo esc_attr( $settings['f8_custom_class'] ); ?>">
			<?php foreach ( $settings['faqs'] as $index => $item ) : ?>
				<div class="f8-faq-item">
					<button class="f8-faq-question" aria-expanded="false" aria-controls="faq-ans-<?php echo esc_attr($index); ?>">
						<?php echo esc_html( $item['question'] ); ?>
						<span class="f8-faq-icon">+</span>
					</button>
					<div id="faq-ans-<?php echo esc_attr($index); ?>" class="f8-faq-answer" hidden>
						<div class="f8-faq-answer-inner">
							<?php echo wp_kses_post( $item['answer'] ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$('.f8-faq-question').on('click', function() {
				const expanded = $(this).attr('aria-expanded') === 'true';
				$(this).attr('aria-expanded', !expanded);
				$(this).next('.f8-faq-answer').slideToggle(300).attr('hidden', expanded);
				$(this).find('.f8-faq-icon').text(expanded ? '+' : '-');
			});
		});
		</script>
		<?php
	}
}
