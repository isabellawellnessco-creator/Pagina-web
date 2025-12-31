<?php
/**
 * Header Template
 * Wraps the entire site in the Desktop Panel concept.
 */
?>
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

<!-- Global Panel Wrapper (Starts Here) -->
<div class="homad-panel-wrapper">

    <?php
    // Splash Screen (Mobile Only, LocalStorage logic in JS)
    if ( is_front_page() ) {
        get_template_part('template-parts/mobile-splash');
    }

    // Header Logic
    // We load both and toggle via CSS/Media Query to ensure caching compatibility
    // but distinct DOM structures as requested (2 rows vs 1 row).
    ?>

    <header id="homad-header-desktop" class="hide-on-mobile">
        <?php get_template_part('template-parts/header/desktop'); ?>
    </header>

    <header id="homad-header-mobile" class="hide-on-desktop">
        <?php get_template_part('template-parts/header/mobile'); ?>
    </header>

    <main class="homad-main">
