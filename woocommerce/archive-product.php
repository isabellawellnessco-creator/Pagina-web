<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template follows the Homad "Panel" & "Filter Drawer" concept.
 *
 * @package Homad
 */

defined( 'ABSPATH' ) || exit;

get_header(); // Starts Panel Wrapper

/**
 * Hook: woocommerce_before_main_content.
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="homad-shop-header">
    <h1 class="homad-page-title"><?php woocommerce_page_title(); ?></h1>

    <!-- Sort & Filter Toggle (Mobile) -->
    <div class="homad-shop-controls">
        <div class="homad-cat-chips-scroll">
            <?php
             // Quick Sub-category chips
             $current_term = is_product_category() ? get_queried_object_id() : 0;
             $terms = get_terms('product_cat', ['parent' => $current_term, 'hide_empty' => true]);
             foreach($terms as $term) {
                 echo '<a href="'.get_term_link($term).'" class="homad-chip">'.$term->name.'</a>';
             }
            ?>
        </div>

        <div class="homad-sort-wrap">
            <?php woocommerce_catalog_ordering(); ?>
        </div>
    </div>
</header>

<div class="homad-shop-layout">

    <!-- Sidebar Filter (Desktop) / Drawer (Mobile) -->
    <aside class="homad-shop-sidebar">
        <div class="homad-filter-drawer-inner">
            <div class="drawer-header hide-on-desktop">
                <h3>Filters</h3>
                <button class="close-drawer">X</button>
            </div>
            <?php
            /**
             * Hook: woocommerce_sidebar.
             */
            do_action( 'woocommerce_sidebar' );
            ?>
        </div>
    </aside>

    <!-- Product Grid -->
    <div class="homad-shop-grid">
        <?php
        if ( woocommerce_product_loop() ) {

            woocommerce_product_loop_start();

            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();

                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );
                }
            }

            woocommerce_product_loop_end();

            /**
             * Hook: woocommerce_after_shop_loop.
             */
            do_action( 'woocommerce_after_shop_loop' );

        } else {
            /**
             * Hook: woocommerce_no_products_found.
             */
            do_action( 'woocommerce_no_products_found' );
        }
        ?>
    </div>

</div>

<!-- Trust Strip Mini -->
<div class="homad-trust-strip-mini">
    <div class="trust-item"><span class="dashicons dashicons-saved"></span> Garantía Directa</div>
    <div class="trust-item"><span class="dashicons dashicons-truck"></span> Envíos a todo Lima</div>
    <div class="trust-item"><span class="dashicons dashicons-hammer"></span> Instalación Disponible</div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action( 'woocommerce_after_main_content' );

get_footer(); // Closes Panel Wrapper
