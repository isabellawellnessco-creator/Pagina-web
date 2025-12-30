<?php
/**
 * Elementor Extension for Homad Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Homad_Elementor_Extension {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'init' ] );
	}

	public function init() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'frontend_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'frontend_scripts' ] );
	}

	public function add_category( $elements_manager ) {
		$elements_manager->add_category(
			'homad-core',
			[
				'title' => esc_html__( 'Homad Core', 'homad-core' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	public function register_widgets( $widgets_manager ) {
		// Include Widget Files
		require_once( __DIR__ . '/widgets/homad-buy-box.php' );
        require_once( __DIR__ . '/widgets/homad-adaptive-form.php' );
        require_once( __DIR__ . '/widgets/homad-configurator.php' );
        require_once( __DIR__ . '/widgets/homad-discount-timer.php' );
        require_once( __DIR__ . '/widgets/homad-product-tabs.php' );
        require_once( __DIR__ . '/widgets/homad-cross-sell.php' );

        // Register Widgets
        $widgets_manager->register( new \Homad_Buy_Box_Widget() );
        $widgets_manager->register( new \Homad_Adaptive_Form_Widget() );
        $widgets_manager->register( new \Homad_Configurator_Widget() );
        $widgets_manager->register( new \Homad_Discount_Timer_Widget() );
        $widgets_manager->register( new \Homad_Product_Tabs_Widget() );
        $widgets_manager->register( new \Homad_Cross_Sell_Widget() );
	}

    public function frontend_styles() {
        wp_register_style( 'homad-elementor', plugins_url( '../../assets/css/homad-elementor.css', __FILE__ ), [], '1.0.0' );
        wp_enqueue_style( 'homad-elementor' );
    }

    public function frontend_scripts() {
        wp_register_script( 'homad-elementor', plugins_url( '../../assets/js/homad-elementor.js', __FILE__ ), ['jquery'], '1.0.0', true );
        wp_enqueue_script( 'homad-elementor' );
    }
}

Homad_Elementor_Extension::instance();
