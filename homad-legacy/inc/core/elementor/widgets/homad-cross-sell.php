<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homad_Cross_Sell_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_cross_sell';
	}

	public function get_title() {
		return esc_html__( 'Homad Cross-Sell Grid', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return [ 'homad-core' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Settings', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Product Count', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 3,
			]
		);

        $this->add_control(
			'source',
			[
				'label' => esc_html__( 'Source', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'related',
				'options' => [
					'related'  => esc_html__( 'Related Products (Auto)', 'homad-core' ),
					'upsells'  => esc_html__( 'Upsells (Manual)', 'homad-core' ),
                    'random'   => esc_html__( 'Random', 'homad-core' ),
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Card Style', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'card_bg_color',
			[
				'label' => esc_html__( 'Card Background', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .homad-product-card' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        $settings = $this->get_settings_for_display();
        global $product;

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status'    => 'publish',
        ];

        if ( $settings['source'] === 'related' && $product ) {
            $related_ids = wc_get_related_products( $product->get_id(), $settings['posts_per_page'] );
            if ( empty( $related_ids ) ) return;
            $args['post__in'] = $related_ids;
        } elseif ( $settings['source'] === 'upsells' && $product ) {
            $upsell_ids = $product->get_upsell_ids();
            if ( empty( $upsell_ids ) ) return;
            $args['post__in'] = $upsell_ids;
        } else {
            $args['orderby'] = 'rand';
        }

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            echo '<div class="homad-cross-sell-grid">';
            while ( $query->have_posts() ) {
                $query->the_post();
                global $product;

                echo '<div class="homad-product-card">';
                echo '<a href="' . get_permalink() . '">';
                echo woocommerce_get_product_thumbnail();
                echo '<h4 class="homad-card-title">' . get_the_title() . '</h4>';
                echo '<span class="price">' . $product->get_price_html() . '</span>';
                echo '</a>';
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        }
	}
}
