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

        <li class="nav-item <?php echo is_shop() ? 'active' : ''; ?>">
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

        <li class="nav-item <?php echo is_cart() ? 'active' : ''; ?>">
            <a href="<?php echo wc_get_cart_url(); ?>">
                <span class="dashicons dashicons-cart"></span>
                <span class="label">Cart</span>
                <?php if (WC()->cart->get_cart_contents_count() > 0): ?>
                    <span class="badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="nav-item <?php echo is_account_page() ? 'active' : ''; ?>">
            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
                <span class="dashicons dashicons-admin-users"></span>
                <span class="label">Profile</span>
            </a>
        </li>

    </ul>
</nav>
