<?php
/**
 * Desktop Header (Furdesign Style)
 * Layout: Left Cluster (Menu/Nav) | Center (Brand) | Right (Customer/Search/User)
 */
?>
<div class="homad-gnb homad-glass">
    <div class="homad-gnb__inner">

        <!-- Left Cluster -->
        <div class="homad-gnb__left">
            <button class="homad-icon-btn homad-icon-btn--solid">
                <span class="dashicons dashicons-menu"></span>
            </button>
            <nav class="homad-gnb__nav">
                <a href="<?php echo home_url('/'); ?>" class="homad-pill-link <?php echo is_front_page() ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo home_url('/shop'); ?>" class="homad-pill-link <?php echo homad_is_shop() ? 'active' : ''; ?>">Shop</a>
                <a href="<?php echo home_url('/projects'); ?>" class="homad-pill-link <?php echo is_page('projects') ? 'active' : ''; ?>">Projects</a>
            </nav>
        </div>

        <!-- Center Cluster -->
        <div class="homad-gnb__center">
            <a href="<?php echo home_url('/'); ?>" class="homad-brand">Homad.</a>
        </div>

        <!-- Right Cluster -->
        <div class="homad-gnb__right">
            <div class="homad-customer-pill homad-glass">
                <div class="homad-avatars">
                    <!-- Placeholders -->
                    <span class="avatar"></span><span class="avatar"></span><span class="avatar"></span>
                </div>
                <span class="label">+15K Customers</span>
            </div>
            <a href="<?php echo home_url('/?s='); ?>" class="homad-icon-btn homad-glass-btn">
                <span class="dashicons dashicons-search"></span>
            </a>
            <a href="<?php echo home_url('/my-account'); ?>" class="homad-icon-btn homad-icon-btn--solid">
                <span class="dashicons dashicons-admin-users"></span>
            </a>
        </div>

    </div>
</div>
