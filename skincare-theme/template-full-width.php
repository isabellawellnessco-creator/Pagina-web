<?php
/**
 * Template Name: Full Width Page
 * Template Post Type: page
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
	<main id="main" class="site-main full-width-page" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<div class="page-content full-width-content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
