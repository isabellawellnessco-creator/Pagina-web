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
		\Skincare\SiteKit\Modules\Cart_Drawer::init();
		\Skincare\SiteKit\Modules\Swatches::init();
		\Skincare\SiteKit\Modules\Forms::init();
		\Skincare\SiteKit\Modules\Filter_Handler::init(); // Added

		// Initialize Shortcodes Wrapper
		\Skincare\SiteKit\Core\Shortcodes::init();

		// Admin
		if ( is_admin() ) {
			\Skincare\SiteKit\Admin\Settings::init();
			\Skincare\SiteKit\Admin\Order_Management::init();
			\Skincare\SiteKit\Admin\Rewards_Admin::init();
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
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'sk_ajax_nonce' ),
			'placeholder_image' => SKINCARE_KIT_URL . 'assets/images/placeholder-product.svg',
		] );
	}

	public function register_widgets( $widgets_manager ) {
		// General Widgets
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Hero_Slider() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Product_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Wishlist_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Dashboard() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Ajax_Search() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Castle() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Contact_Section() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\FAQ_Accordion() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Shipping_Table() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Store_Locator() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Account_Dashboard() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Marquee() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Icon_Box_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Concern_Grid() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Brand_Slider() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Instagram_Feed() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Earn_Redeem() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Catalog() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Rewards_Actions() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Product_Tabs() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Ajax_Filter() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Product_Gallery() );

		// Theme Builder Widgets
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Title() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Price() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Add_To_Cart() );
	}
}
