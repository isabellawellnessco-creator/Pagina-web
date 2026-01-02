<?php
/**
 * The footer for our theme
 *
 * Checks for Skincare Site Kit Theme Builder first.
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Theme Builder has a footer assigned
$custom_footer_id = false;
if ( class_exists( '\Skincare\SiteKit\Modules\Theme_Builder' ) ) {
	$custom_footer_id = \Skincare\SiteKit\Modules\Theme_Builder::get_location_id( 'global_footer' );
}

?>
</div><!-- #content -->

<?php if ( $custom_footer_id ) : ?>
	<footer id="site-footer" class="sk-theme-footer">
		<?php \Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $custom_footer_id ); ?>
	</footer>
<?php else : ?>
	<footer id="site-footer" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php printf( __( '© %d %s. All rights reserved.', 'skincare' ), date( 'Y' ), get_bloginfo( 'name' ) ); ?>
		</div>
	</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
