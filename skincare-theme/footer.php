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

$footer_html = '';
if ( $custom_footer_id ) {
	ob_start();
	\Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $custom_footer_id );
	$footer_html = ob_get_clean();
}

?>
</div><!-- #content -->

<?php if ( ! empty( trim( $footer_html ) ) ) : ?>
	<footer id="site-footer" class="sk-theme-footer">
		<?php echo $footer_html; ?>
	</footer>
<?php else : ?>
	<!-- FALLBACK / SAFE MODE FOOTER -->
	<footer id="site-footer" class="site-footer sk-fallback-footer" role="contentinfo" style="background: #F8F5F1; padding: 40px 0; margin-top: 40px; text-align: center;">
		<div class="site-info">
			<p style="color: #0F3062; margin: 0;">&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
		</div>
	</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
