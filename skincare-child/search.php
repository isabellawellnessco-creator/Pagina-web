<?php
/**
 * The template for displaying search results
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="main" class="site-main sk-container" role="main">
    <header class="page-header">
        <h1 class="page-title">
            <?php
            /* translators: %s: search query. */
            printf( esc_html__( 'Resultados de búsqueda para: %s', 'skincare' ), '<span>' . get_search_query() . '</span>' );
            ?>
        </h1>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="search-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                    </header>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
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
        <div class="no-results not-found">
            <p><?php esc_html_e( 'Lo sentimos, no encontramos nada que coincida con tu búsqueda. Intenta con otras palabras clave.', 'skincare' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
