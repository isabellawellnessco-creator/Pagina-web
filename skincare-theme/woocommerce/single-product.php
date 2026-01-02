<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @package SkincareTheme
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<section class="sk-woo-page sk-woo-single">
	<div class="sk-container">
		<?php do_action( 'woocommerce_before_main_content' ); ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		<?php endwhile; ?>

		<?php do_action( 'woocommerce_after_main_content' ); ?>
	</div>
</section>

<?php
do_action( 'woocommerce_sidebar' );
get_footer( 'shop' );
