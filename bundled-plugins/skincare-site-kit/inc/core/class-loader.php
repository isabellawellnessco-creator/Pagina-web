<?php
namespace Skincare\SiteKit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Loader {

	public function run() {
		// Initialize Modules
		\Skincare\SiteKit\Modules\Theme_Builder::init();
		\Skincare\SiteKit\Modules\Mega_Menu::init();
		\Skincare\SiteKit\Modules\Stock_Notifier::init();
		\Skincare\SiteKit\Modules\Ajax_Search::init();
		\Skincare\SiteKit\Modules\Wishlist::init();
		\Skincare\SiteKit\Modules\Rewards::init();

		// Admin
		if ( is_admin() ) {
			\Skincare\SiteKit\Admin\Settings::init();
			\Skincare\SiteKit\Modules\Seeder::init();
		}

		// Enqueue Scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Elementor Widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'sk-site-kit', SKINCARE_KIT_URL . 'assets/css/site-kit.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-site-kit', SKINCARE_KIT_URL . 'assets/js/site-kit.js', ['jquery'], '1.0.0', true );

		// Localize Script for AJAX
		wp_localize_script( 'sk-site-kit', 'sk_vars', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'sk_ajax_nonce' )
		] );
	}

	public function register_widgets( $widgets_manager ) {
		// Widgets are now autoloaded via namespace
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Hero_Slider() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Product_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Title() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Price() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Add_To_Cart() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Wishlist_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Dashboard() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Ajax_Search() );
	}
}
