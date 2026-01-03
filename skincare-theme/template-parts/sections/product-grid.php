<?php
/**
 * Product grid section template part.
 *
 * @package SkincareThemeChild
 */

$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$category    = $args['category'] ?? '';
$limit       = $args['limit'] ?? 12;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$query_args = [
	'post_type'      => 'product',
	'posts_per_page' => (int) $limit,
	'post_status'    => 'publish',
];

if ( $category ) {
	$query_args['tax_query'] = [
		[
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $category,
		],
	];
}

$products = new WP_Query( $query_args );
?>

<section class="sk-section sk-product-grid">
	<?php if ( $title ) : ?>
		<h2 class="sk-section-title"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>
	<?php if ( $description ) : ?>
		<p class="sk-section-intro"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>

	<?php if ( $products->have_posts() ) : ?>
		<?php woocommerce_product_loop_start(); ?>

		<?php
		while ( $products->have_posts() ) :
			$products->the_post();
			wc_get_template_part( 'content', 'product' );
		endwhile;
		?>

		<?php woocommerce_product_loop_end(); ?>
	<?php else : ?>
		<p class="sk-section-intro"><?php esc_html_e( 'No products found.', 'skincare' ); ?></p>
	<?php endif; ?>
</section>

<?php
wp_reset_postdata();
