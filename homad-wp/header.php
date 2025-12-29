<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'homad' ); ?></a>

    <!-- This container will be styled as the PanelContainer (desktop) or AppShell (mobile) -->
    <div class="homad-container">

        <!-- Mobile Header (conditionally displayed via CSS) -->
        <header id="masthead-mobile" class="site-header-mobile">
            <div class="mobile-header-row-1">
                <div class="mobile-header-title"><?php bloginfo( 'name' ); ?></div>
                <div class="notifications-icon"></div> <!-- Placeholder for icon -->
            </div>
            <div class="mobile-header-row-2">
                <input type="search" class="search-pill-input" placeholder="<?php esc_attr_e( 'Search products...', 'homad' ); ?>">
                <button class="filter-button"></button> <!-- Placeholder for icon -->
            </div>
        </header>

        <!-- Desktop Header (conditionally displayed via CSS) -->
        <header id="masthead-desktop" class="site-header-desktop">
             <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'menu',
                    'container'      => false,
                ) );
            ?>
        </header>

        <div id="content" class="site-content">
