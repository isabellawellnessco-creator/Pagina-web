<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Splash Screen (Mobile Only) -->
<div id="homad-splash" class="splash-screen" style="display:none;">
    <div class="splash-content">
        <h1>Transform Your Home</h1>
        <button id="splash-start-btn" class="homad-btn homad-btn--primary">Get Started</button>
    </div>
</div>

<!-- Desktop Wrapper: Panel Container -->
<div class="panel-container">

    <!-- Header: Desktop Pill -->
    <header class="header-pill mobile-hidden">
        <a href="<?php echo home_url('/'); ?>" class="logo">HOMAD</a>
        <nav class="desktop-nav">
            <a href="<?php echo home_url('/shop'); ?>">Shop</a>
            <a href="<?php echo home_url('/proyectos'); ?>">Projects</a>
            <a href="<?php echo home_url('/nosotros'); ?>">About</a>
        </nav>
        <div class="header-actions">
            <a href="<?php echo home_url('/cart'); ?>" class="cart-icon">Cart</a>
        </div>
    </header>

    <!-- Header: Mobile 2-Row (App Style) -->
    <header class="mobile-header desktop-hidden">
        <div class="mh-row-1">
            <div class="mh-left">
                <span class="app-title">HOMAD</span>
            </div>
            <div class="mh-right">
                <a href="#" class="notif-icon">🔔</a>
            </div>
        </div>
        <div class="mh-row-2">
            <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                <input type="search" class="mobile-search-bar" placeholder="Buscar piezas técnicas..." value="" name="s" />
                <input type="hidden" name="post_type" value="product" />
            </form>
        </div>
    </header>

    <div class="main-content">
