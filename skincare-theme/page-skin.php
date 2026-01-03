<?php
/**
 * Press template.
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	$content = trim( get_the_content() );
	?>
	<main id="main" class="site-main" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<article <?php post_class( 'sk-page' ); ?>>
				<header class="sk-page-header">
					<h1 class="sk-page-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="sk-page-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</header>
				<div class="page-content sk-page-content">
					<?php if ( $content ) : ?>
						<?php the_content(); ?>
					<?php endif; ?>
					<?php
					get_template_part( 'template-parts/sections/post-grid', null, [
						'title' => __( 'Skin Cupid Press Features', 'skincare' ),
						'description' => __( 'Noticias, editoriales y menciones destacadas.', 'skincare' ),
						'category' => 'press',
						'limit' => 6,
					] );
					?>
				</div>
			</article>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
