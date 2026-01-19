<?php
/**
 * Contact template.
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
	$faqs_url = skincare_get_page_url( 'faqs' );
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
				<?php else : ?>
					<section class="sk-page-section sk-contact-form">
						<?php echo skincare_safe_shortcode( 'sk_contact_section' ); ?>
					</section>
					<section class="sk-contact-details sk-section">
						<div>
							<h2><?php esc_html_e( 'Información', 'skincare' ); ?></h2>
							<p><strong><?php esc_html_e( 'Email:', 'skincare' ); ?></strong> hello@skincupid.co.uk</p>
							<p><strong><?php esc_html_e( 'Horario:', 'skincare' ); ?></strong> Lunes - Viernes, 9am - 5pm</p>
						</div>
						<div>
							<h2><?php esc_html_e( '¿Tienes preguntas?', 'skincare' ); ?></h2>
							<p><?php esc_html_e( 'Revisa nuestra sección de preguntas frecuentes antes de contactarnos.', 'skincare' ); ?></p>
							<a class="btn" href="<?php echo esc_url( $faqs_url ); ?>">
								<?php esc_html_e( 'Ver FAQs', 'skincare' ); ?>
							</a>
						</div>
					</section>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
