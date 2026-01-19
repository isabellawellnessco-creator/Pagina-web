<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
} else {
	get_template_part( 'template-parts/f8/template-landing' );
}

get_footer();
