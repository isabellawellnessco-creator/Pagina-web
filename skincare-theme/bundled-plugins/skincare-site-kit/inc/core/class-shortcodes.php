<?php
namespace Skincare\SiteKit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcodes {

	public static function init() {
		// Register a generic shortcode wrapper for any registered section
		add_shortcode( 'sk_section', [ __CLASS__, 'render_section' ] );
		// Backward compatibility for legacy widget shortcode usage.
		add_shortcode( 'sk_widget', [ __CLASS__, 'render_widget' ] );

		// Register specific shortcodes for ease of use in Seeder
		$sections = [
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

		foreach ( $sections as $tag => $class ) {
			add_shortcode( $tag, function( $atts ) use ( $class ) {
				return self::render_widget_class( 'Skincare\\SiteKit\\Widgets\\' . $class, $atts );
			} );
		}
	}

	public static function render_section( $atts = [] ) {
		$atts = shortcode_atts( [
			'name' => '',
		], $atts, 'sk_section' );
		$name = sanitize_key( $atts['name'] );

		if ( $name === 'nav_menu' ) {
			return self::render_nav_menu_section();
		}

		$sections = [
			'marquee' => 'Marquee',
			'hero_slider' => 'Hero_Slider',
			'icon_box_grid' => 'Icon_Box_Grid',
			'product_grid' => 'Product_Grid',
			'concern_grid' => 'Concern_Grid',
			'brand_slider' => 'Brand_Slider',
			'instagram_feed' => 'Instagram_Feed',
			'rewards_castle' => 'Rewards_Castle',
			'rewards_earn_redeem' => 'Rewards_Earn_Redeem',
			'rewards_dashboard' => 'Rewards_Dashboard',
			'contact_section' => 'Contact_Section',
			'faq_accordion' => 'FAQ_Accordion',
			'shipping_table' => 'Shipping_Table',
			'store_locator' => 'Store_Locator',
			'account_dashboard' => 'Account_Dashboard',
			'wishlist_grid' => 'Wishlist_Grid',
			'ajax_filter' => 'Ajax_Filter',
			'rewards_catalog' => 'Rewards_Catalog',
			'rewards_actions' => 'Rewards_Actions',
			'product_tabs' => 'Product_Tabs',
			'product_gallery' => 'Product_Gallery',
		];

		if ( isset( $sections[ $name ] ) ) {
			unset( $atts['name'] );
			return self::render_widget_class( 'Skincare\\SiteKit\\Widgets\\' . $sections[ $name ], $atts );
		}

		return '';
	}

	public static function render_widget( $atts = [] ) {
		return self::render_section( $atts );
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

	private static function render_nav_menu_section() {
		if ( has_nav_menu( 'primary' ) ) {
			return wp_nav_menu( [
				'theme_location' => 'primary',
				'echo' => false,
			] );
		}

		return wp_nav_menu( [
			'echo' => false,
		] );
	}

	private static function render_missing_elementor_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return '';
		}

		return '<div class="sk-section-missing"><strong>Skincare Site Kit:</strong> Activa Elementor para mostrar estas secciones.</div>';
	}
}
