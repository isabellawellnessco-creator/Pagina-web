<?php
/**
 * Post grid section template part.
 *
 * @package SkincareThemeChild
 */

$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$category    = $args['category'] ?? '';
$limit       = $args['limit'] ?? 6;

$query_args = [
	'post_type'      => 'post',
	'posts_per_page' => (int) $limit,
	'post_status'    => 'publish',
];

if ( $category ) {
	$query_args['category_name'] = $category;
}

$posts = new WP_Query( $query_args );
?>

<section class="sk-section sk-posts-grid">
	<?php if ( $title ) : ?>
		<h2 class="sk-section-title"><?php echo esc_html( $title ); ?></h2>
	<?php endif; ?>
	<?php if ( $description ) : ?>
		<p class="sk-section-intro"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>

	<?php if ( $posts->have_posts() ) : ?>
		<div class="sk-post-grid">
			<?php
			while ( $posts->have_posts() ) :
				$posts->the_post();
				?>
				<article <?php post_class( 'sk-post-card' ); ?>>
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
					<a class="btn btn-secondary" href="<?php the_permalink(); ?>">
						<?php esc_html_e( 'Read more', 'skincare' ); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p class="sk-section-intro"><?php esc_html_e( 'No posts found.', 'skincare' ); ?></p>
	<?php endif; ?>
</section>

<?php
wp_reset_postdata();
