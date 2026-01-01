<?php
/**
 * Template Name: Home Router
 *
 * Concept: "App Router"
 * Goal: Split traffic immediately between SHOP (Product) and QUOTE (Service/Project).
 */
get_header(); ?>

<!-- H1: Hero Router (Above the Fold) -->
<section class="homad-hero-router">
    <div class="homad-hero-inner">

        <!-- Left: Copy Stack -->
        <div class="hero-copy-stack">
            <div class="hero-kicker">Arquitectura • Interiorismo • Construcción</div>
            <h1 class="hero-title">
                <span class="text-gray">Transform your</span><br>
                <span class="text-white fw-600">Home with</span><br>
                <span class="text-white fw-700">Elegance.</span>
            </h1>
            <p class="hero-subtitle">Diseño remoto global. Ejecución: Lima.</p>

            <div class="hero-ctas">
                <a href="<?php echo home_url('/shop'); ?>" class="homad-btn homad-btn--primary homad-pill">Shop</a>
                <a href="<?php echo home_url('/projects'); ?>" class="homad-btn homad-btn--glass homad-pill">Request Quote</a>
            </div>
        </div>

        <!-- Right: Media & Overlays -->
        <div class="hero-media-stack">
            <div class="hero-image-wrapper">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-chair.jpg" alt="Hero Interior" class="hero-main-img">
            </div>

            <!-- Floating Overlay Card (Glass) -->
            <div class="hero-overlay-card homad-glass">
                <div class="card-thumb">
                   <span class="dashicons dashicons-products"></span>
                </div>
                <div class="card-content">
                    <div class="card-title">Ropero Modular</div>
                    <div class="card-meta">Best Seller</div>
                    <a href="<?php echo home_url('/shop'); ?>" class="homad-btn-mini">Shop Now</a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- H2: Best Sellers (Immediate Sales) -->
<section class="homad-section-tight">
    <div class="homad-container">
        <div class="section-header">
            <h3>Best Sellers</h3>
            <a href="<?php echo home_url('/shop'); ?>">See all</a>
        </div>
        <div class="homad-product-rail">
            <?php echo do_shortcode('[products limit="4" columns="4" orderby="popularity"]'); ?>
        </div>
    </div>
</section>

<!-- H3: Category Chips -->
<section class="homad-section-tight">
    <div class="homad-container">
        <div class="homad-chip-row">
            <?php
            $cats = get_terms('product_cat', ['number'=>6, 'parent'=>0, 'hide_empty'=>true]);
            foreach($cats as $cat): ?>
                <a href="<?php echo get_term_link($cat); ?>" class="homad-cat-chip">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- H4: Services Snapshot (Lead Gen) -->
<section class="homad-section">
    <div class="homad-container">
        <div class="section-header">
            <h3>Nuestros Servicios</h3>
        </div>
        <div class="homad-grid-2x2">
            <!-- Hardcoded for layout control, linked to Hub -->
            <a href="<?php echo home_url('/projects'); ?>#services" class="service-snap-card">
                <span class="dashicons dashicons-admin-home"></span>
                <h4>Arquitectura</h4>
            </a>
            <a href="<?php echo home_url('/projects'); ?>#services" class="service-snap-card">
                <span class="dashicons dashicons-art"></span>
                <h4>Interiorismo</h4>
            </a>
            <a href="<?php echo home_url('/projects'); ?>#services" class="service-snap-card">
                <span class="dashicons dashicons-hammer"></span>
                <h4>Construcción</h4>
            </a>
            <a href="<?php echo home_url('/projects'); ?>#b2b" class="service-snap-card">
                <span class="dashicons dashicons-building"></span>
                <h4>B2B / Developers</h4>
            </a>
        </div>
    </div>
</section>

<!-- H5: Proof Mini -->
<section class="homad-section bg-light">
    <div class="homad-container">
        <div class="section-header">
            <h3>Recent Projects</h3>
            <a href="<?php echo home_url('/projects'); ?>#portfolio">View all</a>
        </div>
        <div class="homad-proof-mini-grid">
            <?php
            $cases = new WP_Query(['post_type'=>'portfolio', 'posts_per_page'=>2]);
            while($cases->have_posts()): $cases->the_post(); ?>
                <div class="proof-mini-card">
                    <?php the_post_thumbnail('medium'); ?>
                    <div class="proof-title"><?php the_title(); ?></div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>

<!-- H6: Final CTA -->
<section class="homad-section">
    <div class="homad-final-card">
        <h2>¿Listo para empezar?</h2>
        <div class="homad-hero-ctas">
            <a href="<?php echo home_url('/shop'); ?>" class="homad-btn homad-btn--primary">Ir a Tienda</a>
            <a href="<?php echo home_url('/projects'); ?>" class="homad-btn homad-btn--outline">Pedir Cotización</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
