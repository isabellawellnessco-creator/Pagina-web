<?php
/**
 * Wishlist template.
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
	$content = trim( get_the_content() );
	?>
	<main id="main" class="site-main" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<section <?php post_class( 'sk-page' ); ?>>
				<header class="sk-page-header">
					<h1 class="sk-page-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="sk-page-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</header>
				<?php if ( $content ) : ?>
					<section class="page-content sk-page-content">
						<?php the_content(); ?>
					</section>
				<?php else : ?>
					<section class="sk-page-section sk-wishlist-grid">
						<?php echo skincare_safe_shortcode( 'sk_wishlist_grid' ); ?>
					</section>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
