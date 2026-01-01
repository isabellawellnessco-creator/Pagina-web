<?php
namespace Skincare\SiteKit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcodes {

	public static function init() {
		// Register a generic shortcode wrapper for any registered widget
		add_shortcode( 'sk_widget', [ __CLASS__, 'render_widget' ] );

		// Register specific shortcodes for ease of use in Seeder
		$widgets = self::get_widget_map();

		foreach ( $widgets as $tag => $class ) {
			add_shortcode( $tag, function( $atts ) use ( $class ) {
				return self::render_widget_class( 'Skincare\\SiteKit\\Widgets\\' . $class, $atts );
			} );
		}
	}

	public static function get_widget_map() {
		return [
			'sk_marquee' => 'Marquee',
			'sk_hero_slider' => 'Hero_Slider',
			'sk_icon_box_grid' => 'Icon_Box_Grid',
			'sk_product_grid' => 'Product_Grid',
			'sk_concern_grid' => 'Concern_Grid',
			'sk_brand_slider' => 'Brand_Slider',
			'sk_instagram_feed' => 'Instagram_Feed',
			'sk_rewards_castle' => 'Rewards_Castle',
			'sk_rewards_earn_redeem' => 'Rewards_Earn_Redeem',
			'sk_rewards_dashboard' => 'Rewards_Dashboard',
			'sk_contact_section' => 'Contact_Section',
			'sk_faq_accordion' => 'FAQ_Accordion',
			'sk_shipping_table' => 'Shipping_Table',
			'sk_store_locator' => 'Store_Locator',
			'sk_account_dashboard' => 'Account_Dashboard',
			'sk_wishlist_grid' => 'Wishlist_Grid',
			'sk_ajax_filter' => 'Ajax_Filter',
			'sk_product_gallery' => 'Product_Gallery',
			'sk_product_tabs' => 'Product_Tabs',
			'sk_theme_part_title' => 'Theme_Part_Title',
			'sk_theme_part_price' => 'Theme_Part_Price',
			'sk_theme_part_add_to_cart' => 'Theme_Part_Add_To_Cart',
			'sk_nav_menu' => 'Nav_Menu',
		];
	}

	public static function render_widget( $atts ) {
		$atts = shortcode_atts( [ 'name' => '' ], $atts );
		if ( empty( $atts['name'] ) ) return '';

		$map = self::get_widget_map();
		// Allow name to be the shortcode tag (sk_nav_menu) or just the suffix (nav_menu)
		$key = 'sk_' . str_replace( 'sk_', '', $atts['name'] );

		if ( isset( $map[ $key ] ) ) {
			return self::render_widget_class( 'Skincare\\SiteKit\\Widgets\\' . $map[ $key ], $atts );
		}

		return '';
	}

	public static function render_widget_class( $class_name, $atts = [] ) {
		if ( ! class_exists( $class_name ) ) {
			return '';
		}

		ob_start();

		$widget = new $class_name();

		try {
			$reflection = new \ReflectionMethod( $class_name, 'render' );
			$reflection->setAccessible( true );

			$reflection_settings = new \ReflectionProperty( \Elementor\Widget_Base::class, 'settings' );
			$reflection_settings->setAccessible( true );

			// Mock settings
			$settings = [];
			if ( strpos( $class_name, 'Marquee' ) !== false ) $settings['text'] = 'Free Delivery on UK orders over £25 🚚 • 10% OFF first order with code: HELLO10 ✨';
			if ( strpos( $class_name, 'Rewards_Castle' ) !== false ) {
				$settings['tiers'] = [
					[ 'title' => 'Cupids', 'points' => 0, 'benefits' => '1 Point per £1' ],
					[ 'title' => 'Cherubs', 'points' => 200, 'benefits' => '1.25 Points per £1' ],
					[ 'title' => 'Angels', 'points' => 500, 'benefits' => '1.5 Points per £1' ],
				];
			}

			$reflection_settings->setValue( $widget, $settings );
			$reflection->invoke( $widget );

		} catch ( \ReflectionException $e ) {
			return '';
		}

		return ob_get_clean();
	}
}
