<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="main" class="site-main sk-container" role="main">
    <section class="error-404 not-found text-center">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( '¡Ups! Esa página no existe.', 'skincare' ); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e( 'Parece que no hay nada en esta ubicación. ¿Tal vez intentar una búsqueda?', 'skincare' ); ?></p>
            <?php get_search_form(); ?>
            <br>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Volver al Inicio', 'skincare' ); ?></a>
        </div>
    </section>
</main>

<?php
get_footer();
