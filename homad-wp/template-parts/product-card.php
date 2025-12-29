<?php
/**
 * Template part for displaying a product card.
 * To be used within the WooCommerce loop.
 *
 * @package Homad
 */

global $product;
?>

<div class="product-card">
    <div class="product-card__image-wrapper">
        <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
            <?php echo $product->get_image(); ?>
        </a>
        <div class="product-card__actions">
            <!-- Wishlist button can be added here -->
        </div>
        <?php if ( $product->is_on_sale() ) : ?>
            <?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'homad' ) . '</span>', $post, $product ); ?>
        <?php endif; ?>
    </div>
    <div class="product-card__content">
        <h3 class="product-card__title">
            <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h3>
        <div class="product-card__price">
            <?php echo $product->get_price_html(); ?>
        </div>
        <?php if ( $rating_html = wc_get_rating_html( $product->get_average_rating() ) ) : ?>
            <div class="product-card__rating">
                <?php echo $rating_html; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
