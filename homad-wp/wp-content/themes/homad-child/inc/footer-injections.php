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
    // Only fetch settings if on mobile? No, fetch always, hide via CSS/JS.
    $splash_active = get_option('homad_splash_active');
    $splash_title  = get_option('homad_splash_title', 'Welcome to Homad');
    $splash_sub    = get_option('homad_splash_subtitle', 'Design & Build');
    $splash_img    = get_option('homad_splash_image');

    // 1. Splash Screen
    if ($splash_active) : ?>
        <div id="homad-splash-screen" style="display:none;">
            <div class="homad-splash-content">
                <?php if($splash_img): ?><img src="<?php echo esc_url($splash_img); ?>" alt="Splash" class="homad-splash-img"><?php endif; ?>
                <h2 class="homad-splash-title"><?php echo esc_html($splash_title); ?></h2>
                <p class="homad-splash-sub"><?php echo esc_html($splash_sub); ?></p>
                <button class="homad-btn homad-btn--primary homad-splash-btn">Get Started</button>
            </div>
        </div>
        <style>
            #homad-splash-screen {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: #fff; z-index: 9999;
                flex-direction: column; justify-content: center; align-items: center;
                transition: opacity 0.5s ease;
            }
            #homad-splash-screen.hiding { opacity: 0; pointer-events: none; }
            .homad-splash-content { text-align: center; padding: 20px; }
            .homad-splash-img { max-width: 200px; margin-bottom: 20px; }
            .homad-splash-title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        </style>
    <?php endif;

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
            <span class="label">Projects</span>
        </a>
        <a href="<?php echo wc_get_cart_url(); ?>" class="nav-item">
            <span class="dashicons dashicons-bag"></span>
            <span class="label">Cart</span>
        </a>
    </div>
    <style>
        .homad-bottom-nav {
            display: none; /* Desktop hidden */
            position: fixed; bottom: 0; left: 0; width: 100%;
            background: #fff; border-top: 1px solid #eee;
            justify-content: space-around; padding: 10px 0;
            z-index: 999; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            padding-bottom: env(safe-area-inset-bottom);
        }
        .homad-bottom-nav .nav-item {
            text-decoration: none; color: #999;
            display: flex; flex-direction: column; align-items: center;
            font-size: 10px;
        }
        .homad-bottom-nav .nav-item .dashicons { font-size: 24px; height: 24px; margin-bottom: 2px; }
        .homad-bottom-nav .nav-item.active { color: var(--color-primary); }

        @media (max-width: 900px) {
            .homad-bottom-nav { display: flex; }
            body { padding-bottom: 70px; } /* Prevent footer overlap */
        }
    </style>

    <?php
    // 3. Sticky Buy Box (Inject only on PDP)
    if(is_product()):
        global $product;
        if($product):
    ?>
        <div class="homad-sticky-buy-box">
            <div class="homad-sbb-info">
                <h4><?php the_title(); ?></h4>
                <div class="price"><?php echo $product->get_price_html(); ?></div>
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
