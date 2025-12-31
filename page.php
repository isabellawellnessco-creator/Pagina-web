<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package homad
 */

// Check for Skin-Specific Template Override
$skin = defined('HOMAD_SKIN') ? HOMAD_SKIN : 'default';
$slug = get_post_field( 'post_name', get_post() );
$skin_template_path = locate_template( "template-parts/skins/{$skin}/page-{$slug}.php" );

if ( ! empty( $skin_template_path ) ) {
    load_template( $skin_template_path );
    return;
}

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
