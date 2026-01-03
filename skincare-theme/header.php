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

<?php if ( $custom_header_id ) : ?>
	<header id="site-header" class="sk-theme-header">
		<?php \Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $custom_header_id ); ?>
	</header>
<?php else : ?>
	<header id="site-header" class="site-header" role="banner">
		<div class="site-branding">
			<?php
			$branding = get_option( 'sk_theme_branding_settings', [] );
			$logo_id = isset( $branding['logo_id'] ) ? absint( $branding['logo_id'] ) : 0;
			if ( $logo_id ) {
				$logo = wp_get_attachment_image( $logo_id, 'full', false, [ 'class' => 'custom-logo' ] );
				if ( $logo ) {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">' . $logo . '</a>';
				}
			} elseif ( has_custom_logo() ) {
				the_custom_logo();
			} elseif ( is_front_page() && is_home() ) {
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			} else {
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			}
			?>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php wp_nav_menu( [ 'theme_location' => 'primary' ] ); ?>
		</nav>
	</header>
<?php endif; ?>

<div id="content" class="site-content">
