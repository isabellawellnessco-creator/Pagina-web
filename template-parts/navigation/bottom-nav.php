<?php
/**
 * Mobile Bottom Navigation
 * Fixed to bottom of viewport.
 */
?>
<nav class="homad-bottom-nav">
    <ul class="homad-bottom-nav__list">

        <li class="nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
            <a href="<?php echo home_url('/'); ?>">
                <span class="dashicons dashicons-admin-home"></span>
                <span class="label">Home</span>
            </a>
        </li>

        <li class="nav-item <?php echo homad_is_shop() ? 'active' : ''; ?>">
            <a href="<?php echo home_url('/shop'); ?>">
                <span class="dashicons dashicons-store"></span>
                <span class="label">Shop</span>
            </a>
        </li>

        <!-- Central "Action" Item (Quote) -->
        <li class="nav-item nav-item--main <?php echo is_page('projects') ? 'active' : ''; ?>">
            <a href="<?php echo home_url('/projects'); ?>">
                <div class="circle-btn">
                    <span class="dashicons dashicons-hammer"></span>
                </div>
                <span class="label">Quote</span>
            </a>
        </li>

        <li class="nav-item <?php echo homad_is_cart() ? 'active' : ''; ?>">
            <a href="<?php echo homad_get_cart_url(); ?>">
                <span class="dashicons dashicons-cart"></span>
                <span class="label">Cart</span>
                <?php if (homad_is_woocommerce_active() && WC()->cart && WC()->cart->get_cart_contents_count() > 0): ?>
                    <span class="badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="nav-item <?php echo homad_is_account_page() ? 'active' : ''; ?>">
            <a href="<?php echo homad_is_woocommerce_active() ? get_permalink( get_option('woocommerce_myaccount_page_id') ) : home_url('/my-account'); ?>">
                <span class="dashicons dashicons-admin-users"></span>
                <span class="label">Profile</span>
            </a>
        </li>

    </ul>
</nav>
