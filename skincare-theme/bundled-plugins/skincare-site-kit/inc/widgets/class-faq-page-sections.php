<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;

class Sk_FAQ_Page_Section extends Widget_Base {
	public function get_name() { return 'sk_faq_page_section'; }
	public function get_title() { return __( 'FAQ: Page Section', 'skincare' ); }
	public function get_icon() { return 'eicon-help-o'; }
	public function get_categories() { return [ 'skincare-faq' ]; }

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[ 'label' => __( 'FAQs', 'skincare' ), ]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'question',
			[
				'label' => __( 'Question', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'What is your return policy?', 'skincare' ),
			]
		);
		$repeater->add_control(
			'answer',
			[
				'label' => __( 'Answer', 'skincare' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'We accept returns within 30 days...', 'skincare' ),
			]
		);
		$repeater->add_control(
			'category',
			[
				'label' => __( 'Category', 'skincare' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'general' => 'General',
					'shipping' => 'Shipping',
					'products' => 'Products',
				],
				'default' => 'general',
			]
		);

		$this->add_control(
			'faqs',
			[
				'label' => __( 'FAQ Items', 'skincare' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'question' => 'Do you ship internationally?', 'answer' => 'Yes, we ship to most countries...', 'category' => 'shipping' ],
					[ 'question' => 'Are your products vegan?', 'answer' => 'Many of our products are vegan...', 'category' => 'products' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="sk-faq-section">
			<div class="sk-faq-header">
				<h1 class="sk-section-title"><?php esc_html_e( 'Frequently Asked Questions', 'skincare' ); ?></h1>
				<div class="sk-faq-search">
					<input type="text" placeholder="<?php esc_attr_e( 'Search FAQs...', 'skincare' ); ?>" id="sk-faq-search-input">
					<i class="eicon-search"></i>
				</div>
			</div>

			<div class="sk-faq-categories">
				<button class="sk-faq-cat active" data-cat="all"><?php esc_html_e( 'All', 'skincare' ); ?></button>
				<button class="sk-faq-cat" data-cat="shipping"><?php esc_html_e( 'Shipping', 'skincare' ); ?></button>
				<button class="sk-faq-cat" data-cat="products"><?php esc_html_e( 'Products', 'skincare' ); ?></button>
				<button class="sk-faq-cat" data-cat="general"><?php esc_html_e( 'General', 'skincare' ); ?></button>
			</div>

			<div class="sk-faq-list">
				<?php foreach ( $settings['faqs'] as $index => $faq ) : ?>
					<div class="sk-faq-item" data-category="<?php echo esc_attr( $faq['category'] ); ?>">
						<button class="sk-faq-question">
							<?php echo esc_html( $faq['question'] ); ?>
							<span class="sk-faq-icon">+</span>
						</button>
						<div class="sk-faq-answer">
							<?php echo wp_kses_post( $faq['answer'] ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<script>
		jQuery(document).ready(function($) {
			// Accordion
			$('.sk-faq-question').on('click', function() {
				$(this).toggleClass('active');
				$(this).next('.sk-faq-answer').slideToggle();
				$(this).find('.sk-faq-icon').text($(this).hasClass('active') ? '-' : '+');
			});

			// Filtering
			$('.sk-faq-cat').on('click', function() {
				$('.sk-faq-cat').removeClass('active');
				$(this).addClass('active');
				var cat = $(this).data('cat');
				if (cat === 'all') {
					$('.sk-faq-item').show();
				} else {
					$('.sk-faq-item').hide();
					$('.sk-faq-item[data-category="' + cat + '"]').show();
				}
			});

			// Search
			$('#sk-faq-search-input').on('keyup', function() {
				var value = $(this).val().toLowerCase();
				$('.sk-faq-item').filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
		</script>
		<?php
	}
}
