<?php
/**
 * The main template file
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="main" class="site-main sk-container" role="main">
	<?php if ( have_posts() ) : ?>
		<div class="page-content">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>

        <div class="entry-pagination">
			<?php
			the_posts_pagination( [
				'mid_size'  => 2,
				'prev_text' => __( '&larr; Anterior', 'skincare' ),
				'next_text' => __( 'Siguiente &rarr;', 'skincare' ),
			] );
			?>
		</div>

	<?php else : ?>
		<p><?php esc_html_e( 'Lo sentimos, no hay publicaciones que coincidan con tu criterio.', 'skincare' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
