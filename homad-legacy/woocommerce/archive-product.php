<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template handles the Sidebar Filters + Grid Layout.
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 */
do_action( 'woocommerce_before_main_content' );
?>

<header class="woocommerce-products-header panel-header">
	<h1 class="woocommerce-products-header__title page-title">Muebles de Diseño y Acabados Premium</h1>
    <div class="term-description">
        <p>Piezas creadas para resistir la vida real. Estética de lujo, durabilidad industrial.</p>
    </div>
</header>

<div class="homad-shop-layout">

    <!-- Sidebar / Drawer Filters -->
    <aside class="homad-shop-sidebar">
        <div class="filter-group">
            <h4>Estilo de Vida</h4>
            <ul class="filter-list">
                <li><label><input type="checkbox"> Para Mascotas/Niños</label></li>
                <li><label><input type="checkbox"> Home Office</label></li>
                <li><label><input type="checkbox"> Relax</label></li>
            </ul>
        </div>

        <div class="filter-group">
            <h4>Material (Clave)</h4>
             <ul class="filter-list">
                <li><label><input type="checkbox"> Maderas Eterna (HPL)</label></li>
                <li><label><input type="checkbox"> Piedra Sinterizada</label></li>
                <li><label><input type="checkbox"> Metal Mate</label></li>
                <li><label><input type="checkbox"> Polímero Técnico</label></li>
            </ul>
        </div>

         <div class="filter-group">
            <h4>Disponibilidad</h4>
             <ul class="filter-list">
                <li><label><input type="checkbox"> Entrega Express (24h)</label></li>
                <li><label><input type="checkbox"> Pre-order</label></li>
            </ul>
        </div>
    </aside>

    <div class="homad-shop-grid">
        <?php
        if ( woocommerce_product_loop() ) {

            /**
             * Hook: woocommerce_before_shop_loop.
             */
            do_action( 'woocommerce_before_shop_loop' );

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

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
