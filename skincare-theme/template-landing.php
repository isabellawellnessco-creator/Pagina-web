<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
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
	$is_elementor = ( get_post_meta( get_the_ID(), '_elementor_edit_mode', true ) === 'builder' );
	?>
	<main id="main" class="site-main landing-page" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<?php if ( $is_elementor ) : ?>
				<div class="landing-content">
					<?php the_content(); ?>
				</div>
			<?php else : ?>
				<section id="landing-marquee" class="landing-section landing-marquee">
					<?php echo skincare_safe_shortcode( 'sk_marquee' ); ?>
				</section>
				<section id="landing-hero" class="landing-section landing-hero">
					<?php echo skincare_safe_shortcode( 'sk_hero_slider' ); ?>
				</section>
				<section id="landing-icons" class="landing-section landing-icons">
					<?php echo skincare_safe_shortcode( 'sk_icon_box_grid' ); ?>
				</section>
				<section id="landing-products" class="landing-section landing-products">
					<?php echo skincare_safe_shortcode( 'sk_product_grid', 'posts_per_page="16"' ); ?>
				</section>
				<section id="landing-concerns" class="landing-section landing-concerns">
					<?php echo skincare_safe_shortcode( 'sk_concern_grid' ); ?>
				</section>
				<section id="landing-brands" class="landing-section landing-brands">
					<?php echo skincare_safe_shortcode( 'sk_brand_slider' ); ?>
				</section>
				<section id="landing-instagram" class="landing-section landing-instagram">
					<?php echo skincare_safe_shortcode( 'sk_instagram_feed' ); ?>
				</section>
			<?php endif; ?>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
