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

$community_assets = get_stylesheet_directory_uri() . '/assets/images/community';
$press_settings   = get_option( 'sk_press_page_settings', [] );
$press_title      = isset( $press_settings['press_title'] ) && $press_settings['press_title'] ? $press_settings['press_title'] : 'Skin Cupid Press';
$press_subtitle   = isset( $press_settings['press_subtitle'] ) && $press_settings['press_subtitle'] ? $press_settings['press_subtitle'] : 'Discover the must-have products that have caught the eye of top magazines!';
$community_title  = isset( $press_settings['community_title'] ) && $press_settings['community_title'] ? $press_settings['community_title'] : 'Join the Skin Cupid Community';
$community_handle = isset( $press_settings['community_handle'] ) && $press_settings['community_handle'] ? $press_settings['community_handle'] : '@skin.cupid.uk';
$press_posts      = get_posts( [
	'post_type'      => 'sk_press',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
] );
?>

<?php
while ( have_posts() ) :
	the_post();
	$content = trim( get_the_content() );
	?>
	<main id="main" class="site-main" role="main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<section <?php post_class( 'sk-page sk-press-page' ); ?>>
				<section class="sk-press-hero">
					<h1 class="sk-press-title"><?php echo esc_html( $press_title ); ?></h1>
					<p class="sk-press-intro"><?php echo esc_html( $press_subtitle ); ?></p>
					<?php if ( $content ) : ?>
						<section class="page-content sk-page-content">
							<?php the_content(); ?>
						</section>
					<?php endif; ?>
				</section>

				<section class="sk-press-list" aria-label="Skin Cupid Press Features">
					<?php if ( $press_posts ) : ?>
						<?php foreach ( $press_posts as $press_post ) : ?>
							<?php
							$logo_id = get_post_meta( $press_post->ID, '_sk_press_logo_id', true );
							$cta_text = get_post_meta( $press_post->ID, '_sk_press_cta_text', true );
							$cta_url = get_post_meta( $press_post->ID, '_sk_press_cta_url', true );
							$product_vendor = get_post_meta( $press_post->ID, '_sk_press_product_vendor', true );
							$product_name = get_post_meta( $press_post->ID, '_sk_press_product_name', true );
							$product_price = get_post_meta( $press_post->ID, '_sk_press_product_price', true );
							$product_url = get_post_meta( $press_post->ID, '_sk_press_product_url', true );
							$product_image_id = get_post_meta( $press_post->ID, '_sk_press_product_image_id', true );
							$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
							$product_image_url = $product_image_id ? wp_get_attachment_image_url( $product_image_id, 'medium' ) : '';
							$press_link = get_permalink( $press_post );
							?>
							<article class="sk-press-card">
								<a class="sk-press-card-media" href="<?php echo esc_url( $press_link ); ?>">
									<?php if ( has_post_thumbnail( $press_post ) ) : ?>
										<?php echo get_the_post_thumbnail( $press_post, 'large', [ 'loading' => 'lazy' ] ); ?>
									<?php endif; ?>
								</a>
								<div class="sk-press-card-body">
									<a class="sk-press-card-link" href="<?php echo esc_url( $press_link ); ?>">
										<?php if ( $logo_url ) : ?>
											<img class="sk-press-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_the_title( $press_post ) ); ?>" loading="lazy">
										<?php endif; ?>
										<h2 class="sk-press-card-title"><?php echo esc_html( get_the_title( $press_post ) ); ?></h2>
									</a>
									<?php if ( $cta_text && $cta_url ) : ?>
										<a class="btn btn-primary sk-press-cta" href="<?php echo esc_url( $cta_url ); ?>">
											<?php echo esc_html( $cta_text ); ?>
											<span class="sk-press-cta-icon" aria-hidden="true">
												<svg viewBox="0 0 16 13" role="presentation">
													<line x1="0.225" y1="6.6251" x2="14.225" y2="6.6251" stroke="currentColor" stroke-width="1.2"></line>
													<path d="M9.22498 0.975098L14.725 6.4751L9.22498 11.9751" stroke="currentColor" stroke-width="1.2"></path>
												</svg>
											</span>
										</a>
									<?php endif; ?>
								</div>
								<?php if ( $product_name || $product_url ) : ?>
									<aside class="sk-press-product">
										<h3 class="sk-press-product-heading">Featured Products</h3>
										<a class="sk-press-product-card" href="<?php echo esc_url( $product_url ); ?>">
											<?php if ( $product_image_url ) : ?>
												<img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" loading="lazy">
											<?php endif; ?>
											<div class="sk-press-product-info">
												<?php if ( $product_vendor ) : ?>
													<span class="sk-press-product-vendor"><?php echo esc_html( $product_vendor ); ?></span>
												<?php endif; ?>
												<?php if ( $product_name ) : ?>
													<span class="sk-press-product-name"><?php echo esc_html( $product_name ); ?></span>
												<?php endif; ?>
												<?php if ( $product_price ) : ?>
													<span class="sk-press-product-price"><?php echo esc_html( $product_price ); ?></span>
												<?php endif; ?>
											</div>
										</a>
									</aside>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					<?php else : ?>
						<p><?php esc_html_e( 'No press items found. Add them in Skincare Kit → Press.', 'skincare' ); ?></p>
					<?php endif; ?>
				</section>

				<section class="sk-community">
					<div class="sk-community-border" aria-hidden="true">
						<img src="<?php echo esc_url( $community_assets . '/community-border.svg' ); ?>" alt="">
					</div>
					<div class="sk-community-content">
						<h2 class="sk-community-title"><?php echo esc_html( $community_title ); ?></h2>
						<p class="sk-community-handle">
							<img src="<?php echo esc_url( $community_assets . '/instagram-sc.svg' ); ?>" alt="Instagram icon" loading="lazy">
							<a href="https://www.instagram.com/skin.cupid.uk/" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $community_handle ); ?></a>
						</p>
					</div>
					<div class="sk-community-grid" aria-label="Skin Cupid Community posts">
						<?php
						$community_option_images = [];
						for ( $index = 1; $index <= 12; $index++ ) {
							$key = 'community_image_' . $index;
							if ( ! empty( $press_settings[ $key ] ) ) {
								$community_option_images[] = absint( $press_settings[ $key ] );
							}
						}
						?>
						<?php if ( $community_option_images ) : ?>
							<?php foreach ( $community_option_images as $index => $attachment_id ) : ?>
								<?php $src = wp_get_attachment_image_url( $attachment_id, 'medium' ); ?>
								<?php if ( $src ) : ?>
									<a class="sk-community-item" href="https://www.instagram.com/skin.cupid.uk/" target="_blank" rel="noopener noreferrer">
										<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( 'Skin Cupid community post ' . ( $index + 1 ) ); ?>" loading="lazy">
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else : ?>
							<p><?php esc_html_e( 'Add community images in Skincare Kit → Branding & Content.', 'skincare' ); ?></p>
						<?php endif; ?>
					</div>
				</section>
			</section>
		<?php endif; ?>
	</main>
<?php endwhile; ?>

<?php
get_footer();
