<?php
/**
 * Default page content template.
 *
 * @package SkincareThemeChild
 */
?>

<main id="main" class="site-main" role="main">
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
		<div class="page-content">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>
</main>
