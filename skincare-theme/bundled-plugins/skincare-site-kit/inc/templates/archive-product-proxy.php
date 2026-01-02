<?php
/**
 * Proxy template to render Elementor Content for Shop/Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$template_id = \Skincare\SiteKit\Modules\Theme_Builder::get_location_id( 'shop_archive' );

if ( $template_id ) {
	echo '<div class="sk-archive-template">';
	\Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $template_id );
	echo '</div>';
} else {
	// Fallback to default loop if something goes wrong but hook fired
	if ( have_posts() ) {
		echo '<div class="sk-container" style="padding: 40px 20px;">';
		echo '<h1>' . woocommerce_page_title( false ) . '</h1>';
		woocommerce_product_loop_start();
		while ( have_posts() ) {
			the_post();
			wc_get_template_part( 'content', 'product' );
		}
		woocommerce_product_loop_end();
		echo '</div>';
	}
}

get_footer();
