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

		<?php
		// Fallback Switcher for Language/Currency if needed
		if ( class_exists( '\Skincare\SiteKit\Widgets\Sk_Switcher' ) && class_exists( '\Elementor\Widget_Base' ) ) {
			// Manually render a simplified version or instantiate
			// Since instantiating Elementor widgets manually is complex, we render direct HTML
			// reusing the logic we know exists in Localization.
			if ( class_exists( '\Skincare\SiteKit\Modules\Localization' ) ) {
				$currencies = \Skincare\SiteKit\Modules\Localization::get_currencies();
				$active = \Skincare\SiteKit\Modules\Localization::get_active_currency();
				if ( count( $currencies ) > 1 ) {
					echo '<div class="sk-header-fallback-switcher" style="position:absolute; top:10px; right:10px; z-index:999;">';
					echo '<select onchange="window.skSwitchCurrency(this.value)" style="padding:5px; font-size:12px;">';
					foreach ( $currencies as $code => $data ) {
						echo '<option value="' . esc_attr( $code ) . '" ' . selected( $active, $code, false ) . '>' . esc_html( $code ) . '</option>';
					}
					echo '</select>';
					// Ensure JS helper exists
					echo '<script>
					if (!window.skSwitchCurrency) {
						window.skSwitchCurrency = function(val) {
							jQuery.post("' . admin_url('admin-ajax.php') . '", {
								action: "sk_switch_currency",
								currency: val
							}, function(res) {
								if(res.success) location.reload();
							});
						};
					}
					</script>';
					echo '</div>';
				}
			}
		}
		?>
	</header>
<?php endif; ?>

<div id="content" class="site-content">
