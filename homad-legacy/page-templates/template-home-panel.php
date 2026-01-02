<?php
/**
 * Template Name: Home Panel (Premium)
 * Description: A unified "Panel" layout for the Desktop Homepage, floating on a clean background.
 *
 * @package homad-child
 */

get_header();

// Retrieve Global Settings
$hero_kicker = get_option('homad_hero_kicker', 'Design. Build. Furnish.');
?>

<!-- Panel Container Wrapper (Desktop Only Effect) -->
<div class="panel-container">

    <!-- Custom Header inside Panel (Optional: if user wants standard header, we can remove this and use theme's) -->
    <!-- Ideally, we hide the theme's header via CSS on this page and render a custom one here, or use Elementor Header -->

    <main class="site-content" role="main">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>

    <!-- Editorial Band (Footer inside Panel) -->
    <!-- This can also be part of Elementor, but if hardcoded preference: -->
    <!-- <div class="panel-footer-band">...</div> -->

</div>

<?php get_footer(); ?>
