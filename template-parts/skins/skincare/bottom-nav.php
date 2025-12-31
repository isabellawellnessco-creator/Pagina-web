<?php
/**
 * Skincare Skin - Mobile Bottom Navigation
 */

$home_url    = home_url( '/' );
$shop_url    = wc_get_page_permalink( 'shop' );
$rewards_url = home_url( '/rewards' ); // Assumes page slug is 'rewards'
$cart_url    = wc_get_cart_url();
$account_url = wc_get_page_permalink( 'myaccount' );
$cart_count  = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>

<div class="homad-bottom-nav">
    <a href="<?php echo esc_url( $home_url ); ?>" class="nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>Home</span>
    </a>

    <a href="<?php echo esc_url( $shop_url ); ?>" class="nav-item <?php echo is_shop() ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
        <span>Shop</span>
    </a>

    <a href="<?php echo esc_url( $rewards_url ); ?>" class="nav-item <?php echo is_page('rewards') ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
        <span>Rewards</span>
    </a>

    <a href="<?php echo esc_url( $cart_url ); ?>" class="nav-item <?php echo is_cart() ? 'active' : ''; ?>">
        <div style="position: relative;">
            <svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            <?php if ( $cart_count > 0 ) : ?>
                <span style="position: absolute; top: -5px; right: -8px; background: var(--skin-color-primary); color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 10px; display: flex; align-items: center; justify-content: center;"><?php echo esc_html( $cart_count ); ?></span>
            <?php endif; ?>
        </div>
        <span>Cart</span>
    </a>

    <a href="<?php echo esc_url( $account_url ); ?>" class="nav-item <?php echo is_account_page() ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <span>Profile</span>
    </a>
</div>
