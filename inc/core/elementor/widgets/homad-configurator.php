<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homad_Configurator_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_configurator';
	}

	public function get_title() {
		return esc_html__( 'Homad Visual Configurator', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	public function get_categories() {
		return [ 'homad-core' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Configurator Settings', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'base_image_size',
			[
				'label' => esc_html__( 'Image Height', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [ 'min' => 200, 'max' => 1000 ],
				],
				'selectors' => [
					'{{WRAPPER}} .homad-configurator-stage' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
        global $product;
        if ( ! $product ) {
            echo 'Product not found.';
            return;
        }

        // Get Layers
        $layers = get_post_meta($product->get_id(), '_homad_configurator_layers', true);
        if (empty($layers) || !is_array($layers)) {
            echo 'No configuration layers found for this product.';
            return;
        }

        // Group Layers
        $groups = [];
        foreach ($layers as $layer) {
            $g = $layer['group'] ?: 'Options';
            $groups[$g][] = $layer;
        }

        wp_enqueue_script('homad-configurator', get_stylesheet_directory_uri() . '/assets/core/js/homad-configurator.js', ['jquery'], '1.0', true);

        // Render Stage
        echo '<div class="homad-configurator-wrapper">';

        // Image Stack
        echo '<div class="homad-configurator-stage" style="position:relative; width:100%; overflow:hidden;">';
        // Base Product Image
        echo '<img src="' . get_the_post_thumbnail_url($product->get_id(), 'full') . '" class="homad-base-img" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; z-index:0;">';

        // Layer Images (Hidden by default)
        foreach ($layers as $i => $layer) {
            $id = 'layer-' . sanitize_title($layer['group']) . '-' . sanitize_title($layer['name']);
            echo '<img src="' . esc_url($layer['image']) . '" id="' . $id . '" class="homad-layer-img group-' . sanitize_title($layer['group']) . '" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; object-fit:contain; z-index:' . intval($layer['zindex']) . ';">';
        }
        echo '</div>';

        // Controls
        echo '<div class="homad-configurator-controls">';
        echo '<h3>Customize Your Package</h3>';

        foreach ($groups as $group_name => $group_layers) {
            echo '<div class="homad-config-group">';
            echo '<h4>' . esc_html($group_name) . '</h4>';
            echo '<div class="homad-config-options">';

            // "None" option if needed, but for now we assume one must be picked or it's additive
            // Let's assume Radio behavior (Select one per group)
            foreach ($group_layers as $layer) {
                $layer_id = 'layer-' . sanitize_title($group_name) . '-' . sanitize_title($layer['name']);
                echo '<label class="homad-config-option">';
                echo '<input type="radio" name="homad_group_' . sanitize_title($group_name) . '" value="' . $layer_id . '" data-price="' . floatval($layer['price']) . '"> ';
                echo esc_html($layer['name']);
                if ($layer['price'] > 0) echo ' (+' . wc_price($layer['price']) . ')';
                echo '</label>';
            }
            echo '</div></div>';
        }

        echo '<div class="homad-total-price">Total Extra: <span id="homad-config-total">' . wc_price(0) . '</span></div>';

        // Add Hidden Inputs for Cart Form (Assumes this widget is inside the product form or we append it via JS)
        // Since we can't easily inject into the form tag from here without hooks, we use JS to append these to the form on submit
        // or place them here and assume the form wraps this area (unlikely in Elementor).
        // Strategy: Output inputs here, JS moves them to the form.
        echo '<div id="homad-cart-inputs" style="display:none;">';
        echo '<input type="hidden" name="homad_config_total_input" id="homad_config_total_input" value="0">';
        echo '<input type="hidden" name="homad_selected_layers" id="homad_selected_layers" value="">';
        echo '</div>';

        echo '</div>'; // End Controls

        echo '</div>'; // End Wrapper
	}
}
