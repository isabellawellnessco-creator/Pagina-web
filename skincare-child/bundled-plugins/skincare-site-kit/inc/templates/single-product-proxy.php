<?php
/**
 * Proxy template to render Elementor Content for Single Product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$template_id = \Skincare\SiteKit\Modules\Theme_Builder::get_location_id( 'single_product' );
global $product;

if ( $template_id ) {
	echo '<div class="sk-single-product-template">';
	\Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $template_id );

	// Sticky ATC for Mobile
	if ( $product ) {
		?>
		<div class="sk-sticky-atc">
			<div class="info">
				<span class="title"><?php echo esc_html( $product->get_name() ); ?></span>
				<span class="price"><?php echo $product->get_price_html(); ?></span>
			</div>
			<form class="cart" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post" enctype="multipart/form-data">
				<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt btn sk-btn"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
			</form>
		</div>
		<?php
	}

	echo '</div>';
}
