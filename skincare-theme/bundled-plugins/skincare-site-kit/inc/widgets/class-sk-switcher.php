<?php
namespace Skincare\SiteKit\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Skincare\SiteKit\Modules\Localization;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sk_Switcher extends Widget_Base {

	public function get_name() {
		return 'sk_switcher';
	}

	public function get_title() {
		return __( 'Skin Cupid Switcher', 'skincare' );
	}

	public function get_icon() {
		return 'eicon-globe';
	}

	public function get_categories() {
		return [ 'skincare-site-kit' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Configuración', 'skincare' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_language',
			[
				'label' => __( 'Mostrar Idioma', 'skincare' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_currency',
			[
				'label' => __( 'Mostrar Moneda', 'skincare' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Diseño', 'skincare' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dropdown',
				'options' => [
					'dropdown'  => __( 'Desplegables', 'skincare' ),
					'inline' => __( 'Inline (Lista)', 'skincare' ),
				],
			]
		);

		$this->end_controls_section();

		// Style Tab
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Estilo General', 'skincare' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .sk-switcher-item, {{WRAPPER}} .sk-switcher-select',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color Texto', 'skincare' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sk-switcher-item' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sk-switcher-select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __( 'Color Fondo', 'skincare' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sk-switcher-select' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .sk-switcher-select',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Relleno', 'skincare' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .sk-switcher-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$show_lang = $settings['show_language'] === 'yes';
		$show_curr = $settings['show_currency'] === 'yes';

		if ( ! $show_lang && ! $show_curr ) return;

		$currencies = Localization::get_currencies();
		$languages = Localization::get_languages();
		$active_currency = Localization::get_active_currency();
		$active_language = Localization::get_active_language();

		echo '<div class="sk-switcher-wrapper ' . esc_attr( $settings['layout'] ) . '">';

		if ( $show_lang && ! empty( $languages ) ) {
			echo '<div class="sk-switcher-group sk-lang-switcher">';
			echo '<select class="sk-switcher-select" onchange="window.skSwitchLanguage(this.value)">';
			foreach ( $languages as $code => $data ) {
				$label = isset( $data['label'] ) ? $data['label'] : $code;
				$selected = selected( $active_language, $code, false );
				echo '<option value="' . esc_attr( $code ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
			}
			echo '</select>';
			echo '</div>';
		}

		if ( $show_curr && ! empty( $currencies ) ) {
			echo '<div class="sk-switcher-group sk-curr-switcher">';
			echo '<select class="sk-switcher-select" onchange="window.skSwitchCurrency(this.value)">';
			foreach ( $currencies as $code => $data ) {
				$symbol = isset( $data['symbol'] ) ? $data['symbol'] : $code;
				$selected = selected( $active_currency, $code, false );
				echo '<option value="' . esc_attr( $code ) . '" ' . $selected . '>' . esc_html( $code ) . ' (' . esc_html( $symbol ) . ')</option>';
			}
			echo '</select>';
			echo '</div>';
		}

		echo '</div>';

		// Inline script for immediate action
		?>
		<script>
		if (!window.skSwitchCurrency) {
			window.skSwitchCurrency = function(val) {
				jQuery.post(sk_vars.ajax_url, {
					action: 'sk_switch_currency',
					currency: val
				}, function(res) {
					if(res.success) {
						location.reload();
					}
				});
			};
		}
		if (!window.skSwitchLanguage) {
			window.skSwitchLanguage = function(val) {
				jQuery.post(sk_vars.ajax_url, {
					action: 'sk_switch_language',
					language: val
				}, function(res) {
					if(res.success) {
						location.reload();
					}
				});
			};
		}
		</script>
		<style>
			.sk-switcher-wrapper { display: flex; gap: 10px; align-items: center; }
			.sk-switcher-wrapper.dropdown { flex-direction: row; }
			.sk-switcher-wrapper.inline { flex-direction: column; }
			.sk-switcher-select { cursor: pointer; border: 1px solid #ddd; background: transparent; }
		</style>
		<?php
	}
}
