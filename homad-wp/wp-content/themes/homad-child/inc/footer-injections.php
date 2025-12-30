<?php
/**
 * Helper to inject markup into Footer.
 * Splash Screen + Bottom Nav + Sticky Buy Box.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

add_action('wp_footer', 'homad_render_mobile_elements');

function homad_render_mobile_elements() {
    // 1. Splash Screen Logic
    $splash_active = get_option('homad_splash_active');
    if ($splash_active) {
        get_template_part('template-parts/mobile-splash');
    }

    // 2. Bottom Navigation (Visible only on Mobile via CSS)
    ?>
    <div class="homad-bottom-nav">
        <a href="<?php echo home_url('/'); ?>" class="nav-item">
            <span class="dashicons dashicons-admin-home"></span>
            <span class="label">Home</span>
        </a>
        <a href="<?php echo home_url('/shop'); ?>" class="nav-item">
            <span class="dashicons dashicons-cart"></span>
            <span class="label">Shop</span>
        </a>
         <a href="<?php echo home_url('/projects'); ?>" class="nav-item">
            <span class="dashicons dashicons-hammer"></span>
            <span class="label">Quote</span>
        </a>
        <a href="<?php echo wc_get_cart_url(); ?>" class="nav-item">
            <span class="dashicons dashicons-bag"></span>
            <span class="label">Cart</span>
        </a>
    </div>

    <?php
    // 3. Sticky Buy Box (Inject only on PDP)
    if(is_product() && homad_is_woocommerce_active()):
        global $product;
        if($product):
    ?>
        <div class="homad-sticky-buy-box">
            <div class="homad-sbb-thumb">
                <?php echo $product->get_image('thumbnail'); ?>
            </div>
            <div class="homad-sbb-info">
                <span class="price"><?php echo $product->get_price_html(); ?></span>
            </div>
            <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="homad-btn homad-btn--primary">
                    Buy Now
                </button>
            </form>
        </div>
    <?php
        endif;
    endif;
}
