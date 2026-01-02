<?php
/**
 * The template for displaying all pages
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>

	<main id="main" class="site-main" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<div class="page-content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>
	</main>

<?php endwhile; // End of the loop. ?>

<?php
get_footer();
