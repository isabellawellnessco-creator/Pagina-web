<?php
/**
 * The header for our theme
 *
 * Checks for Skincare Site Kit Theme Builder first.
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Theme Builder has a header assigned
$custom_header_id = false;
if ( class_exists( '\Skincare\SiteKit\Modules\Theme_Builder' ) ) {
	$custom_header_id = \Skincare\SiteKit\Modules\Theme_Builder::get_location_id( 'global_header' );
}

$header_html = '';
if ( $custom_header_id ) {
	ob_start();
	\Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $custom_header_id );
	$header_html = ob_get_clean();
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( ! empty( trim( $header_html ) ) ) : ?>
	<header id="site-header" class="sk-theme-header">
		<?php echo $header_html; ?>
	</header>
<?php else : ?>
	<!-- FALLBACK / SAFE MODE HEADER -->
	<header id="site-header" class="site-header sk-fallback-header" role="banner" style="background: #fff; border-bottom: 1px solid #eee; padding: 20px 0;">
		<div class="sk-container" style="display: flex; align-items: center; justify-content: space-between;">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<h1 class="site-title" style="margin:0; font-size: 24px; font-weight: bold;">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="text-decoration: none; color: #0F3062;">
							<?php bloginfo( 'name' ); ?>
						</a>
					</h1>
					<?php
				}
				?>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php
				wp_nav_menu( [
					'theme_location' => 'primary',
					'menu_class'     => 'sk-fallback-menu',
					'container'      => false,
					'fallback_cb'    => false, // Don't list pages if menu missing
					'items_wrap'     => '<ul id="%1$s" class="%2$s" style="display: flex; gap: 20px; list-style: none; margin: 0; padding: 0;">%3$s</ul>'
				] );
				?>
			</nav>

            <div class="sk-header-actions" style="display: flex; gap: 15px;">
                <a href="<?php echo wc_get_cart_url(); ?>"><i class="eicon-bag-medium"></i> Cart</a>
            </div>
		</div>
	</header>
<?php endif; ?>

<div id="content" class="site-content">
