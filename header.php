<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="homad-app-root">

    <?php
    // Splash screen logic is usually handled via JS/CSS overlay,
    // but if a template part exists we can include it.
    get_template_part('template-parts/mobile-splash');
    ?>

    <?php
    // The Panel Container structure for Desktop
    // Mobile design mimics native app (usually full width/height),
    // but the request emphasizes the "Panel Container" wrapper.
    ?>
    <div class="homad-panel-container">

        <header id="masthead" class="site-header">
            <?php
            // Logic to load desktop or mobile header part
            // In a responsive modern theme, often both are loaded and hidden via CSS,
            // or we stick to one structure that adapts.
            // Given the memory "2-row Header (Title/Bell + Search/Filter)" for mobile,
            // let's try to load specific parts if they exist.

            get_template_part('template-parts/header/desktop');
            get_template_part('template-parts/header/mobile');
            ?>
        </header><!-- #masthead -->

        <div id="content" class="site-content">
