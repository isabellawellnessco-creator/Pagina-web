<?php
/**
 * Asset helpers.
 *
 * @package SkincareTheme
 */

if ( ! function_exists( 'skincupid_theme_asset_url' ) ) {
	function skincupid_theme_asset_url( $path ) {
		$path = ltrim( $path, '/' );

		return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/' . $path;
	}
}
