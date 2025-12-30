<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Homad_Adaptive_Form_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'homad_adaptive_form';
	}

	public function get_title() {
		return esc_html__( 'Homad Adaptive Form', 'homad-core' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
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
			'form_title',
			[
				'label' => esc_html__( 'Form Title', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Request a Quote', 'homad-core' ),
			]
		);

		$this->end_controls_section();

        // Style
        $this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Form Style', 'homad-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => 'Labels',
				'selector' => '{{WRAPPER}} .homad-form-label',
			]
		);

        $this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Input Radius', 'homad-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .homad-form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        $settings = $this->get_settings_for_display();

        // Enqueue the frontend logic
        wp_enqueue_script('homad-forms', plugins_url('../../../assets/js/homad-forms.js', __FILE__), ['jquery'], '1.1', true);

        // Localize Script with DB Config
        $fields = get_option('homad_form_fields', []);
        $api_key = get_option('homad_google_maps_api_key', '');

        wp_localize_script('homad-forms', 'homadFormConfig', $fields);
        wp_localize_script('homad-forms', 'homadGoogleMapsKey', $api_key);
        wp_localize_script('homad-forms', 'homadFormNonce', wp_create_nonce('homad_lead_nonce'));
        wp_localize_script('homad-forms', 'homadAjaxUrl', admin_url('admin-ajax.php'));

        // Load Google Maps API if Key exists
        if ($api_key) {
             wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places', [], null, true);
        }

        echo '<div class="homad-adaptive-form-container" data-title="' . esc_attr($settings['form_title']) . '">';
        echo '<h3>' . esc_html($settings['form_title']) . '</h3>';
        echo '<div id="homad-form-root"><!-- Form Rendered by JS --></div>';
        echo '</div>';
	}
}
