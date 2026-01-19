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
		\Skincare\SiteKit\Modules\Filter_Handler::init();
		\Skincare\SiteKit\Modules\Press::init();
		\Skincare\SiteKit\Modules\Purchase_Orders::init();
		\Skincare\SiteKit\Modules\Notifications::init();
		\Skincare\SiteKit\Modules\Coupons_Automation::init();
		\Skincare\SiteKit\Modules\Tracking_Manager::init(); // Added
		\Skincare\SiteKit\Modules\Marketing_Events::init(); // Will create next
		\Skincare\SiteKit\Modules\Localization::init();

		\Skincare\SiteKit\Admin\Rewards_Master::init();
		\Skincare\SiteKit\Core\Rest_Controller::init();

		// Initialize Shortcodes Wrapper
		\Skincare\SiteKit\Core\Shortcodes::init();

		// Admin
		if ( is_admin() ) {
			\Skincare\SiteKit\Admin\Settings::init();
			\Skincare\SiteKit\Admin\Operations_Dashboard::init();
			\Skincare\SiteKit\Admin\Operations_Core::init();
			\Skincare\SiteKit\Admin\Whatsapp_Context::init();
			\Skincare\SiteKit\Admin\Stock_Manager::init();
			\Skincare\SiteKit\Admin\Label_Generator::init();
			\Skincare\SiteKit\Admin\Fulfillment::init();
			\Skincare\SiteKit\Admin\Order_Management::init();
			\Skincare\SiteKit\Admin\Rewards_Admin::init();
			\Skincare\SiteKit\Admin\Migration_Center::init();
			\Skincare\SiteKit\Admin\Notifications_Center::init();
			\Skincare\SiteKit\Admin\Coupons_Automation::init();
			\Skincare\SiteKit\Admin\Tracking_Settings::init();
			\Skincare\SiteKit\Admin\Whatsapp_Templates::init();
			\Skincare\SiteKit\Modules\Seeder::init();

			\Skincare\SiteKit\Admin\Admin_Onboarding::init();
			\Skincare\SiteKit\Admin\Tools::init();
		}

		// Enqueue Scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		// Elementor Sections
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function enqueue_assets() {
		$rules = get_option( 'sk_rewards_rules', [] );
		$redeem_points = isset( $rules['redeem_points'] ) ? (int) $rules['redeem_points'] : 500;
		$redeem_amount = isset( $rules['redeem_amount'] ) ? (float) $rules['redeem_amount'] : 5;

		wp_enqueue_style( 'sk-site-kit', SKINCARE_KIT_URL . 'assets/css/site-kit.css', [], '1.0.0' );
		wp_enqueue_script( 'sk-site-kit', SKINCARE_KIT_URL . 'assets/js/site-kit.js', ['jquery'], '1.0.0', true );

		// Localize Script for AJAX
		wp_localize_script( 'sk-site-kit', 'sk_vars', [
			'ajax_url'          => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'sk_ajax_nonce' ),
			'rest_url'          => esc_url_raw( rest_url( 'skincare/v1/' ) ),
			'rest_nonce'        => wp_create_nonce( 'wp_rest' ),
			'placeholder_image' => SKINCARE_KIT_URL . 'assets/images/placeholder-product.svg',
			'redeem_points'     => $redeem_points,
			'redeem_amount'     => $redeem_amount,
		] );
	}

	public function register_widgets( $widgets_manager ) {
		// General Sections
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

		// Theme Builder Sections
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Title() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Price() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Theme_Part_Add_To_Cart() );
		$widgets_manager->register( new \Skincare\SiteKit\Widgets\Sk_Switcher() );
	}
}
