<?php
/**
 * The template for displaying archive pages
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="main" class="site-main sk-container" role="main">
    <?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) : ?>

        <?php if ( have_posts() ) : ?>
            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header>

            <div class="archive-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/post-summary' );
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
            <p><?php esc_html_e( 'No se encontraron resultados.', 'skincare' ); ?></p>
        <?php endif; ?>

    <?php endif; ?>
</main>

<?php
get_footer();
