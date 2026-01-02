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
				get_template_part( 'template-parts/content/post' );
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
