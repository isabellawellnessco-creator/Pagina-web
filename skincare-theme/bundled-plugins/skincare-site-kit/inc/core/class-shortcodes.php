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
		$widgets = [
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
		];

		foreach ( $widgets as $tag => $class ) {
			add_shortcode( $tag, function( $atts ) use ( $class ) {
				return self::render_widget_class( 'Skincare\\SiteKit\\Widgets\\' . $class, $atts );
			} );
		}
	}

	public static function render_widget_class( $class_name, $atts = [] ) {
		if ( ! class_exists( $class_name ) ) {
			return '';
		}

		if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
			return self::render_missing_elementor_notice();
		}

		ob_start();

		// Instantiate the widget
		$widget = new $class_name();

		$settings = is_array( $atts ) ? $atts : [];
		if ( strpos( $class_name, 'Marquee' ) !== false ) {
			$settings['text'] = 'Envío gratis en pedidos del Reino Unido superiores a £25 🚚 • 10% de descuento en tu primera compra con el código: HELLO10 ✨';
		}
		if ( strpos( $class_name, 'Rewards_Castle' ) !== false ) {
			$settings['tiers'] = [
				[ 'title' => 'Cupids', 'points' => 0, 'benefits' => '1 punto por £1' ],
				[ 'title' => 'Cherubs', 'points' => 200, 'benefits' => '1.25 puntos por £1' ],
				[ 'title' => 'Angels', 'points' => 500, 'benefits' => '1.5 puntos por £1' ],
			];
		}

		if ( method_exists( $widget, 'render_shortcode' ) ) {
			$widget->render_shortcode( $settings );
		} else {
			return '';
		}

		return ob_get_clean();
	}

	private static function render_missing_elementor_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		return '<div class="sk-widget-missing"><strong>Skincare Site Kit:</strong> Activa Elementor para mostrar estos widgets.</div>';
	}
}
