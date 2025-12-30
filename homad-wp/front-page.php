<?php
/**
 * The template for displaying the home page.
 *
 * @package Homad
 */

get_header(); ?>

<main id="main" class="site-main">

    <?php
    // Section 1: Hero Router
    // This will contain the complex layout for desktop (panel design) and mobile (app-like).
    ?>
    <section id="hero-router" class="home-section hero-router">
        <div class="hero-container">
            <div class="hero-left-copy">
                <p class="hero-kicker">Arquitectura • Interiorismo • Construcción</p>
                <h1 class="hero-h1">
                    <span class="h1-line-gray">Transforma tu espacio con</span>
                    <span class="h1-line-white">nuestra</span>
                    <span class="h1-line-white-bold">Colección Curada.</span>
                </h1>
                <p class="hero-subphrase">Diseño remoto global. Ejecución/instalación: Lima (por ahora).</p>
                <div class="hero-ctas">
                    <a href="#" class="btn btn-primary">Shop Now</a>
                    <a href="#" class="btn btn-secondary">Request Quote</a>
                </div>
            </div>
            <div class="hero-center-image">
                <!-- Main product image will be a background or an <img> tag here -->
            </div>
            <div class="hero-right-overlays">
                <div class="overlay-card-main">
                    <!-- Overlay content here -->
                </div>
                <div class="side-media-card">
                    <!-- Side image here -->
                </div>
            </div>
        </div>
    </section>

    <?php
    // Section 2: Best Sellers Strip
    // We will query WooCommerce for top-selling products here.
    ?>
    <section id="best-sellers" class="home-section best-sellers">
        <div class="container">
            <h2><?php esc_html_e( 'Best Sellers', 'homad' ); ?></h2>
            <div class="product-grid">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                );
                $best_sellers_query = new WP_Query( $args );
                if ( $best_sellers_query->have_posts() ) {
                    // We need to tell WooCommerce we're in a loop
                    woocommerce_product_loop_start();
                    while ( $best_sellers_query->have_posts() ) {
                        $best_sellers_query->the_post();
                        // Instead of wc_get_template_part, we will use our own custom template part
                        get_template_part( 'template-parts/product-card' );
                    }
                    woocommerce_product_loop_end();
                } else {
                    echo '<p>' . esc_html__( 'No best-selling products found.', 'homad' ) . '</p>';
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <?php
    // Section 3: Services Snapshot
    // We will query the 'service' CPT for a few items.
    ?>
    <section id="services-snapshot" class="home-section services-snapshot">
        <div class="container">
            <h2><?php esc_html_e( 'Our Services', 'homad' ); ?></h2>
            <div class="services-grid">
                 <?php
                $args_services = array(
                    'post_type' => 'service',
                    'posts_per_page' => 3,
                );
                $services_query = new WP_Query( $args_services );
                if ( $services_query->have_posts() ) :
                    while ( $services_query->have_posts() ) : $services_query->the_post();
                        get_template_part( 'template-parts/service-card' );
                    endwhile;
                else :
                    echo '<p>' . esc_html__( 'No services found.', 'homad' ) . '</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <?php
    // Section 4: Packages Snapshot
    // We will query the 'package' CPT.
    ?>
    <section id="packages-snapshot" class="home-section packages-snapshot">
        <div class="container">
            <h2><?php esc_html_e( 'Our Packages', 'homad' ); ?></h2>
            <div class="packages-grid">
                <?php
                $args_packages = array(
                    'post_type' => 'package',
                    'posts_per_page' => 2, // As per brief: B2C and B2B
                );
                $packages_query = new WP_Query( $args_packages );
                if ( $packages_query->have_posts() ) :
                    while ( $packages_query->have_posts() ) : $packages_query->the_post();
                        get_template_part( 'template-parts/package-card' );
                    endwhile;
                else :
                    echo '<p>' . esc_html__( 'No packages found.', 'homad' ) . '</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <?php
    // Section 5: Proof Mini
    // We will query the 'portfolio' CPT.
    ?>
    <section id="proof-mini" class="home-section proof-mini">
        <div class="container">
            <h2><?php esc_html_e( 'Our Work', 'homad' ); ?></h2>
            <div class="proof-grid">
                <?php
                $args_portfolio = array(
                    'post_type' => 'portfolio',
                    'posts_per_page' => 3, // As per brief: 2-3 projects
                );
                $portfolio_query = new WP_Query( $args_portfolio );
                if ( $portfolio_query->have_posts() ) :
                    while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();
                        get_template_part( 'template-parts/case-card' );
                    endwhile;
                else :
                    echo '<p>'. esc_html__( 'No portfolio items found.', 'homad' ) . '</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

    <?php
    // Section 6: Final CTA
    ?>
    <section id="final-cta" class="home-section final-cta">
        <div class="container">
            <h2><?php esc_html_e( 'Final CTA Section', 'homad' ); ?></h2>
        </div>
    </section>

</main><!-- #main -->

<?php get_footer();
