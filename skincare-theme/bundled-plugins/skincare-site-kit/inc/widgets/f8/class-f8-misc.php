<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;

class F8_Breadcrumbs extends F8_Widget_Base {

	public function get_name() { return 'f8_breadcrumbs'; }
	public function get_title() { return __( 'F8 Breadcrumbs', 'skincare' ); }
	public function get_icon() { return 'eicon-product-breadcrumbs'; }

	protected function register_controls() {
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="f8-breadcrumbs <?php echo esc_attr( $settings['f8_custom_class'] ); ?>">
			<?php
			if ( function_exists( 'woocommerce_breadcrumb' ) ) {
				woocommerce_breadcrumb( [
					'wrap_before' => '<nav class="f8-breadcrumbs-nav">',
					'wrap_after'  => '</nav>',
					'delimiter'   => '<span class="f8-breadcrumb-sep">/</span>',
				] );
			}
			?>
		</div>
		<?php
	}
}

class F8_Banner extends F8_Widget_Base {
	public function get_name() { return 'f8_banner'; }
	public function get_title() { return __( 'F8 Banner', 'skincare' ); }
	public function get_icon() { return 'eicon-banner'; }

	protected function register_controls() {
		$this->start_controls_section('content', ['label' => 'Content']);
		$this->add_control('text', ['label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'Banner Text']);
		$this->end_controls_section();
		$this->register_common_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="f8-banner ' . esc_attr($settings['f8_custom_class']) . '">' . esc_html($settings['text']) . '</div>';
	}
}
