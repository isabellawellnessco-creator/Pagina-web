<?php
// Handle AJAX Search
add_action( 'wp_ajax_skincare_ajax_search', 'skincare_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_skincare_ajax_search', 'skincare_ajax_search_callback' );

function skincare_ajax_search_callback() {
    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';

    if (empty($term)) {
        wp_send_json_error();
    }

    $response = [
        'products' => [],
        'categories' => [],
        'brands' => [] // Assuming Brands are just pages or a custom tax for now
    ];

    // 1. Search Products
    $product_args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        's' => $term
    ];
    $product_query = new WP_Query($product_args);

    if ($product_query->have_posts()) {
        while ($product_query->have_posts()) {
            $product_query->the_post();
            global $product;
            $product = wc_get_product( get_the_ID() );

            $response['products'][] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'thumb' => $product->get_image('thumbnail'),
                'price' => $product->get_price_html(),
            ];
        }
    }
    wp_reset_postdata();

    // 2. Search Categories
    $cat_args = [
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'name__like' => $term
    ];
    $categories = get_terms($cat_args);
    if ( ! is_wp_error( $categories ) ) {
        foreach ( $categories as $cat ) {
             $response['categories'][] = [
                 'name' => $cat->name,
                 'url' => get_term_link($cat),
                 'count' => $cat->count
             ];
             if(count($response['categories']) >= 3) break;
        }
    }

    // 3. Search Brands (Pages for now, later could be taxonomy)
    // We assume 'Marcas' are Pages that are children of a 'Brands' page or just pages with 'Brand' in title
    // For simplicity, we search Pages.
    $brand_args = [
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        's' => $term
    ];
    $brand_query = new WP_Query($brand_args);
    if($brand_query->have_posts()) {
        while($brand_query->have_posts()){
            $brand_query->the_post();
            $response['brands'][] = [
                'name' => get_the_title(),
                'url' => get_permalink()
            ];
        }
    }
    wp_reset_postdata();

    wp_send_json_success($response);
}
