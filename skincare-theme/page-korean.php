<?php
/**
 * Korean Skincare template.
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
				<?php endif; ?>
				<?php
				get_template_part( 'template-parts/sections/product-grid', null, [
					'title' => __( 'Korean skincare favorites', 'skincare' ),
					'description' => __( 'Rutinas, tratamientos y lanzamientos de K-Beauty.', 'skincare' ),
					'category' => 'k-beauty',
					'limit' => 12,
				] );
				?>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
