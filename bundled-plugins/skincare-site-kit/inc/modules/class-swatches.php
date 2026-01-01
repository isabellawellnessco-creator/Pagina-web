<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Swatches {

	public static function init() {
		// Just a stub for attribute selection in this non-DB environment
		// In a real plugin, this would hook into 'product_attributes_type_selector'
		// and add a new type 'image/color'.

		// Frontend: Hook into variable product form to replace dropdowns
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', [ __CLASS__, 'render_swatches' ], 10, 2 );
	}

	public static function render_swatches( $html, $args ) {
		// If attribute name contains 'color' or 'shade', replace with swatches
		$attribute = $args['attribute'];

		if ( strpos( $attribute, 'color' ) === false && strpos( $attribute, 'shade' ) === false ) {
			return $html;
		}

		$options = $args['options'];
		$product = $args['product'];

		ob_start();
		echo '<div class="sk-swatches" data-attribute="' . esc_attr( $attribute ) . '">';
		foreach ( $options as $option ) {
			// Mocking color values based on option name slug for demo
			$color_hex = '#eee';
			if ( strpos( $option, 'red' ) !== false ) $color_hex = '#d32f2f';
			if ( strpos( $option, 'pink' ) !== false ) $color_hex = '#e91e63';
			if ( strpos( $option, 'beige' ) !== false ) $color_hex = '#f5f5dc';

			// If we had DB, we'd fetch term meta

			echo '<label class="sk-swatch-label">';
			echo '<input type="radio" name="' . esc_attr( $attribute ) . '" value="' . esc_attr( $option ) . '">';
			echo '<span class="sk-swatch-visual" style="background-color:' . $color_hex . ';"></span>';
			echo '<span class="sk-tooltip">' . esc_html( $option ) . '</span>';
			echo '</label>';
		}
		echo '</div>';

		// Hide original select but keep it for functionality
		$html = '<div style="display:none;">' . $html . '</div>';

		return ob_get_clean() . $html;
	}
}
