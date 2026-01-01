<?php
// Handle AJAX Search
add_action( 'wp_ajax_skincare_ajax_search', 'skincare_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_skincare_ajax_search', 'skincare_ajax_search_callback' );

function skincare_ajax_search_callback() {
    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    if (empty($term)) {
        wp_send_json_error();
    }

    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        's' => $term
    ];

    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );

            $results[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'thumb' => $product->get_image('thumbnail'),
                'price' => $product->get_price_html(),
            ];
        }
    }

    wp_reset_postdata();
    wp_send_json_success($results);
}
