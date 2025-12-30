<?php
/**
 * The Template for displaying all single products
 *
 * Implements "Buy Box" (Desktop) and "Bottom Sheet" (Mobile) logic.
 *
 * @package Homad
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="homad-pdp-container">

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="homad-pdp-grid">

            <!-- 1. Gallery (Left) -->
            <div class="homad-pdp-gallery">
                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 * Output: Product Images.
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>

            <!-- 2. Buy Box (Right / Bottom Sheet) -->
            <div class="homad-pdp-buybox homad-glass">

                <h1 class="product_title entry-title"><?php the_title(); ?></h1>

                <div class="price-rating-row">
                    <?php woocommerce_template_single_price(); ?>
                    <?php woocommerce_template_single_rating(); ?>
                </div>

                <!-- Logistics Meta (Native) -->
                <?php
                $eta_min = get_post_meta(get_the_ID(), '_homad_eta_min', true);
                $eta_max = get_post_meta(get_the_ID(), '_homad_eta_max', true);
                if($eta_min): ?>
                    <div class="homad-meta-block">
                        <span class="dashicons dashicons-clock"></span>
                        <strong>ETA:</strong> <?php echo esc_html("$eta_min - $eta_max days"); ?>
                    </div>
                <?php endif; ?>

                <div class="homad-add-to-cart-wrap">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>

                <div class="homad-trust-stack">
                    <small><span class="dashicons dashicons-shield"></span> 1 Year Warranty</small>
                    <small><span class="dashicons dashicons-undo"></span> 30-Day Returns</small>
                </div>
            </div>

        </div><!-- .homad-pdp-grid -->

        <!-- 3. Details Tabs (Below) -->
        <div class="homad-pdp-tabs">
            <?php woocommerce_output_product_data_tabs(); ?>
        </div>

        <!-- 4. Related -->
        <?php woocommerce_output_related_products(); ?>

    <?php endwhile; // end of the loop. ?>

</div>

<?php get_footer(); ?>
