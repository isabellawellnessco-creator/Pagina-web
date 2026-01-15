<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax_Search {

	public static function init() {
		add_action( 'wp_ajax_sk_ajax_search', [ __CLASS__, 'handle_search' ] );
		add_action( 'wp_ajax_nopriv_sk_ajax_search', [ __CLASS__, 'handle_search' ] );
	}

	public static function handle_search() {
		// Enabled Nonce Check
		check_ajax_referer( 'sk_ajax_nonce', 'nonce' );

		$term = isset( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';

		if ( empty( $term ) ) {
			wp_send_json_success( [] );
		}

		$args = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			's'              => $term,
		];

		$query = new \WP_Query( $args );
		$results = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				// Fix Global Product Issue
				global $product;
				if ( ! $product ) {
					$product = wc_get_product( get_the_ID() );
				}

				$results[] = [
					'id'    => get_the_ID(),
					'title' => get_the_title(),
					'url'   => get_permalink(),
					'price' => $product ? $product->get_price_html() : '',
					'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
				];
			}
			wp_reset_postdata();
		}

		wp_send_json_success( $results );
	}
}
