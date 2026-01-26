<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Sk_Store_Locator_Page_Section extends Widget_Base {
	public function get_name() { return 'sk_store_locator_page_section'; }
	public function get_title() { return __( 'Store Locator: Page Section', 'skincare' ); }
	public function get_icon() { return 'eicon-google-maps'; }
	public function get_categories() { return [ 'skincare-contact' ]; }

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'skincare' ),
			]
		);

		$this->add_control(
			'stockist_widget_id',
			[
				'label' => __( 'Stockist Widget ID', 'skincare' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'u12345', // Placeholder
				'description' => __( 'Enter your Stockist.co widget ID.', 'skincare' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $settings['stockist_widget_id'];
		?>
		<div class="sk-store-locator-section">
			<div class="sk-store-locator-header">
				<h1 class="sk-section-title"><?php esc_html_e( 'Store Locator', 'skincare' ); ?></h1>
				<p><?php esc_html_e( 'Find our products in a store near you.', 'skincare' ); ?></p>
			</div>

			<div class="sk-stockist-wrapper">
				<div data-stockist-widget-tag="<?php echo esc_attr( $widget_id ); ?>">
					<?php esc_html_e( 'Loading store locator...', 'skincare' ); ?>
				</div>
				<script>
				(function(s,t,o,c,k){c=s.createElement(t);c.src=o;c.async=1;
				k=s.getElementsByTagName(t)[0];k.parentNode.insertBefore(c,k);
				})(document,'script','//stockist.co/embed/v1/widget.min.js');
				</script>
			</div>
		</div>
		<?php
	}
}
