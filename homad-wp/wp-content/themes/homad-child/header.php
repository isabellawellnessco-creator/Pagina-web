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
<header class="homad-site-header" role="banner">
    <div class="homad-container">
        <p class="homad-site-title"><?php bloginfo('name'); ?></p>
        <p class="homad-site-tagline"><?php bloginfo('description'); ?></p>
    </div>
</header>
<main class="homad-site-main" role="main">
