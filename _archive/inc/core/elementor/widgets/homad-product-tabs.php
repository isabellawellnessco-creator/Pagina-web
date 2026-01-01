<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homad_Product_Tabs_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_product_tabs';
	}

	public function get_title() {
		return esc_html__( 'Homad Product Tabs', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return [ 'homad-core' ];
	}

	protected function register_controls() {
        // Standard Style Controls
        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Tab Style', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Active Tab Color', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .homad-tab-link.active' => 'border-bottom-color: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        global $product;
        if ( ! $product ) return;

        $desc = apply_filters('the_content', $product->get_description());
        $policy = get_post_meta($product->get_id(), '_homad_return_policy', true);
        if (!$policy) $policy = "Standard 30-day return policy applies.";

        echo '<div class="homad-product-tabs">';

        // Headers
        echo '<div class="homad-tabs-nav">';
        echo '<button class="homad-tab-link active" data-tab="desc">Description</button>';
        echo '<button class="homad-tab-link" data-tab="policy">Return Policy</button>';
        echo '</div>';

        // Content
        echo '<div class="homad-tab-content active" id="tab-desc">' . $desc . '</div>';
        echo '<div class="homad-tab-content" id="tab-policy">' . wpautop(esc_html($policy)) . '</div>';

        echo '</div>';

        ?>
        <script>
        jQuery(document).ready(function($){
            $('.homad-tab-link').click(function(){
                var t = $(this);
                var id = t.data('tab');

                t.closest('.homad-product-tabs').find('.homad-tab-link').removeClass('active');
                t.addClass('active');

                t.closest('.homad-product-tabs').find('.homad-tab-content').removeClass('active').hide();
                $('#tab-'+id).addClass('active').fadeIn();
            });
        });
        </script>
        <style>
            .homad-tabs-nav { border-bottom: 1px solid #ddd; margin-bottom: 15px; }
            .homad-tab-link { background:none; border:none; border-bottom: 2px solid transparent; padding: 10px 20px; cursor: pointer; font-weight: bold; }
            .homad-tab-link.active { border-bottom-color: #000; }
            .homad-tab-content { display: none; }
            .homad-tab-content.active { display: block; }
        </style>
        <?php
	}
}
