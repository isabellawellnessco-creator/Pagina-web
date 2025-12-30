<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Homad_Buy_Box_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_buy_box';
	}

	public function get_title() {
		return esc_html__( 'Homad Buy Box', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	public function get_categories() {
		return [ 'homad-core' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'show_price',
			[
				'label' => esc_html__( 'Show Price', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'homad-core' ),
				'label_off' => esc_html__( 'Hide', 'homad-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
			'show_eta',
			[
				'label' => esc_html__( 'Show ETA', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Button Style', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			]
		);

        $this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single_add_to_cart_button' => 'color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

        global $product;
        if ( ! $product ) {
            echo '<div class="homad-elementor-preview">Product Buy Box (Preview)</div>';
            return;
        }

        echo '<div class="homad-buy-box">';

        // Price
        if ( 'yes' === $settings['show_price'] ) {
            echo '<div class="homad-price">' . $product->get_price_html() . '</div>';
        }

        // ETA
        if ( 'yes' === $settings['show_eta'] ) {
            $eta = get_post_meta( $product->get_id(), '_homad_eta_text', true );
            if ( $eta ) {
                echo '<div class="homad-eta"><i class="dashicons dashicons-clock"></i> ' . esc_html( $eta ) . '</div>';
            }
        }

        // Add to Cart Form
        woocommerce_template_single_add_to_cart();

        echo '</div>';
	}
}
