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

		// Elementor widgets usually need $args and $instance data passed to render()
		// Since we are bypassing Elementor's render cycle, we might need to manually call the protected render method
		// or use a reflection trick if it's protected.
		// However, most of our custom widgets have a protected render() method.

		// REFLECTION to call protected render()
		try {
			$reflection = new \ReflectionMethod( $class_name, 'render' );
			$reflection->setAccessible( true );

			// Default settings or passed attributes
			$settings = [];
			// This part is tricky without Elementor's full stack.
			// For simplicity in this "Replica" phase, our widgets might fail if they rely strictly on `get_settings_for_display`.
			// Let's ensure our widgets are robust enough or mock the data.

			// MOCK DATA for specific widgets to ensure they render something visuals
			if ( strpos( $class_name, 'Marquee' ) !== false ) $settings['text'] = 'Envío gratis en pedidos del Reino Unido superiores a £25 🚚 • 10% de descuento en tu primera compra con el código: HELLO10 ✨';
			if ( strpos( $class_name, 'Rewards_Castle' ) !== false ) {
				$settings['tiers'] = [
					[ 'title' => 'Cupids', 'points' => 0, 'benefits' => '1 punto por £1' ],
					[ 'title' => 'Cherubs', 'points' => 200, 'benefits' => '1.25 puntos por £1' ],
					[ 'title' => 'Angels', 'points' => 500, 'benefits' => '1.5 puntos por £1' ],
				];
			}
			// Add more mocks as needed for visual fidelity without DB

			$widget_base = new \ReflectionClass( \Elementor\Widget_Base::class );
			if ( $widget_base->hasProperty( 'settings' ) ) {
				$reflection_settings = $widget_base->getProperty( 'settings' );
				$reflection_settings->setAccessible( true );
				$reflection_settings->setValue( $widget, $settings );
			} elseif ( method_exists( $widget, 'set_settings' ) ) {
				$widget->set_settings( $settings );
			}

			$reflection->invoke( $widget );

		} catch ( \ReflectionException $e ) {
			return 'Error rendering widget: ' . $e->getMessage();
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
