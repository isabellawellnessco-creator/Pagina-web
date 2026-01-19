<?php
/**
 * Default page content template.
 *
 * @package SkincareThemeChild
 */
?>

<main id="main" class="site-main" role="main">
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
		<section <?php post_class( 'sk-page' ); ?>>
			<header class="sk-page-header">
				<h1 class="sk-page-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="sk-page-subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</header>
			<section class="page-content sk-page-content">
				<?php the_content(); ?>
			</section>
		</section>
	<?php endif; ?>
</main>
