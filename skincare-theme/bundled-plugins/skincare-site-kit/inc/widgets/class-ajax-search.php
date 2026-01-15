<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Ajax_Search extends Shortcode_Renderer {

	public function get_name() {
		return 'sk_ajax_search';
	}

	public function get_title() {
		return __( 'SK AJAX Search', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function render() {
		?>
		<div class="sk-ajax-search-wrapper">
			<form role="search" method="get" class="sk-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="sk-search-input" placeholder="<?php _e( 'Buscar productos...', 'skincare' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
				<button type="submit" class="sk-search-submit"><i class="eicon-search"></i></button>
			</form>
			<div class="sk-search-results"></div>
		</div>
		<?php
	}
}
