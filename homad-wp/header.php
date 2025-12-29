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

        <!-- Desktop Header (hidden on mobile) -->
        <header id="masthead-desktop" class="site-header-desktop">
             <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ) );
            ?>
        </header>

        <div id="content" class="site-content">
