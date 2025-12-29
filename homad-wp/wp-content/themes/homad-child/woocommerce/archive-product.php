<?php
/**
 * WooCommerce archive product template placeholder.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop');
?>
<div class="homad-woocommerce-archive">
    <?php do_action('woocommerce_before_main_content'); ?>
    <header class="woocommerce-products-header">
        <?php do_action('woocommerce_archive_description'); ?>
    </header>
    <?php if (woocommerce_product_loop()) : ?>
        <?php woocommerce_product_loop_start(); ?>
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <?php wc_get_template_part('content', 'product'); ?>
        <?php endwhile; ?>
        <?php woocommerce_product_loop_end(); ?>
    <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>
    <?php do_action('woocommerce_after_main_content'); ?>
</div>
<?php
get_footer('shop');
