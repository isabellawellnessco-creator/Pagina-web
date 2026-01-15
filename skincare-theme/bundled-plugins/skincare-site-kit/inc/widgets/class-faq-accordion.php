<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class FAQ_Accordion extends Shortcode_Renderer {
	public function get_name() { return 'sk_faq_accordion'; }
	public function get_title() { return __( 'Acordeón de preguntas', 'skincare' ); }
	public function get_icon() { return 'eicon-accordion'; }
	public function get_categories() { return [ 'general' ]; }

	protected function _register_controls() {
		$this->start_controls_section( 'content', [ 'label' => 'Preguntas frecuentes' ] );
		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'question', [ 'label' => 'Pregunta', 'type' => Controls_Manager::TEXT ] );
		$repeater->add_control( 'answer', [ 'label' => 'Respuesta', 'type' => Controls_Manager::WYSIWYG ] );
		$this->add_control( 'faqs', [
			'label' => 'Preguntas frecuentes',
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => [
				[
					'question' => '¿Cuánto tarda el envío?',
					'answer' => 'Los envíos estándar suelen tardar entre 2 y 4 días laborables.',
				],
				[
					'question' => '¿Puedo devolver un producto?',
					'answer' => 'Sí, aceptamos devoluciones dentro de los 30 días posteriores a la compra.',
				],
				[
					'question' => '¿Cómo gano puntos?',
					'answer' => 'Ganas puntos por cada compra y acciones especiales dentro de tu cuenta.',
				],
			],
		] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="sk-faq-accordion">';
		foreach ( $settings['faqs'] as $faq ) {
			echo '<div class="sk-faq-item">';
			echo '<h4 class="sk-faq-question">' . esc_html( $faq['question'] ) . '</h4>';
			echo '<div class="sk-faq-answer">' . $faq['answer'] . '</div>';
			echo '</div>';
		}
		echo '</div>';
		?>
		<style>
			.sk-faq-item { border-bottom: 1px solid #eee; padding: 15px 0; }
			.sk-faq-question { cursor: pointer; color: #0F3062; margin: 0; }
			.sk-faq-answer { display: none; padding-top: 10px; color: #666; }
		</style>
		<script>
		jQuery(document).ready(function($){
			$('.sk-faq-question').click(function(){
				$(this).next('.sk-faq-answer').slideToggle();
			});
		});
		</script>
		<?php
	}
}
