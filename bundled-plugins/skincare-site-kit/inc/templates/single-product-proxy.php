<?php
/**
 * Proxy template to render Elementor Content for Single Product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_id = \Skincare\SiteKit\Modules\Theme_Builder::get_location_id( 'single_product' );

if ( $template_id ) {
	echo '<div class="sk-single-product-template">';
	\Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $template_id );
	echo '</div>';
}
