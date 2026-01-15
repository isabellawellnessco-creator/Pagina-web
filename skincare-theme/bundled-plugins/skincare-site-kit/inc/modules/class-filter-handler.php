<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Filter_Handler {

	public static function init() {
		add_action( 'wp_ajax_sk_filter_products', [ __CLASS__, 'handle_filter' ] );
		add_action( 'wp_ajax_nopriv_sk_filter_products', [ __CLASS__, 'handle_filter' ] );
	}

	public static function handle_filter() {
		// Enabled Nonce Check (Mandatory)
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'tax_query'      => [ 'relation' => 'AND' ],
			'meta_query'     => [ 'relation' => 'AND' ],
		];

		// Brands - Safe Fallback
		if ( ! empty( $_POST['brand'] ) ) {
			if ( taxonomy_exists( 'pa_brand' ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'pa_brand',
					'field'    => 'slug',
					'terms'    => array_map( 'sanitize_text_field', $_POST['brand'] ),
				];
			}
			// If taxonomy doesn't exist, we silently ignore the filter to show all products
			// instead of crashing. Admin notice handles the warning.
		}

		// Price
		if ( ! empty( $_POST['max_price'] ) ) {
			$args['meta_query'][] = [
				'key'     => '_price',
				'value'   => floatval( $_POST['max_price'] ),
				'compare' => '<=',
				'type'    => 'NUMERIC'
			];
		}

		$query = new \WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			echo '<ul class="products columns-4 sk-product-grid">';
			while ( $query->have_posts() ) {
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			}
			echo '</ul>';
		} else {
			echo '<p class="woocommerce-info">' . __( 'No se encontraron productos.', 'skincare' ) . '</p>';
		}

		wp_reset_postdata();

		$html = ob_get_clean();
		wp_send_json_success( [ 'html' => $html ] );
	}
}
