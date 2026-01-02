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
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="site-main landing-page" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<section class="landing-content">
				<?php the_content(); ?>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
