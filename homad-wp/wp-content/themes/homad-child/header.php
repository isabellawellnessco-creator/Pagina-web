<?php
/**
 * Header template.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php if (is_front_page()) : ?>
<div class="homad-panel-shell">
    <div class="homad-panel">
<?php endif; ?>
<header class="homad-site-header" role="banner">
    <div class="homad-container homad-gnb">
        <div class="homad-gnb__left">
            <button class="homad-icon-button homad-icon-button--solid" type="button" aria-label="<?php echo esc_attr__('Abrir menú', 'homad'); ?>">
                <span aria-hidden="true">&#9776;</span>
            </button>
            <nav class="homad-nav-pill" aria-label="<?php echo esc_attr__('Navegación principal', 'homad'); ?>">
                <a class="homad-pill is-active" href="#"><?php echo esc_html__('Home', 'homad'); ?></a>
                <a class="homad-pill" href="#"><?php echo esc_html__('Shop', 'homad'); ?></a>
                <a class="homad-pill" href="#"><?php echo esc_html__('Packages', 'homad'); ?></a>
                <a class="homad-pill" href="#"><?php echo esc_html__('Services', 'homad'); ?></a>
            </nav>
        </div>
        <div class="homad-gnb__center">
            <span class="homad-brand"><?php echo esc_html__('Homad.', 'homad'); ?></span>
        </div>
        <div class="homad-gnb__right">
            <div class="homad-customer-pill">
                <div class="homad-avatar-stack" aria-hidden="true">
                    <span class="homad-avatar"></span>
                    <span class="homad-avatar"></span>
                    <span class="homad-avatar"></span>
                </div>
                <span class="homad-customer-pill__text"><?php echo esc_html__('+15K Customers', 'homad'); ?></span>
            </div>
            <button class="homad-icon-button homad-icon-button--soft" type="button" aria-label="<?php echo esc_attr__('Buscar', 'homad'); ?>">
                <span aria-hidden="true">&#128269;</span>
            </button>
            <button class="homad-icon-button homad-icon-button--solid" type="button" aria-label="<?php echo esc_attr__('Perfil', 'homad'); ?>">
                <span aria-hidden="true">&#128100;</span>
            </button>
        </div>
    </div>
</header>
<main class="homad-site-main" role="main">
