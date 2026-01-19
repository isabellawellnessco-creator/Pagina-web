<?php
/**
 * Login template.
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
	$default_shortcode_tag = class_exists( 'WooCommerce' ) ? 'woocommerce_my_account' : '';
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
				<?php if ( $content ) : ?>
					<section class="page-content sk-page-content">
						<?php the_content(); ?>
					</section>
				<?php elseif ( $default_shortcode_tag ) : ?>
					<section class="sk-page-section sk-login-form">
						<?php echo skincare_safe_shortcode( $default_shortcode_tag ); ?>
					</section>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
