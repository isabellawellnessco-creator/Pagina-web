<?php
/**
 * Template Name: Rewards Page (Skincare)
 *
 * @package Homad
 */

get_header();

// Load specific skin content
if ( defined('HOMAD_SKIN') && HOMAD_SKIN === 'skincare' ) {
    get_template_part('template-parts/skins/skincare/content-rewards');
} else {
    // Fallback or standard page
    while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content', 'page' );
    endwhile;
}

get_footer();
