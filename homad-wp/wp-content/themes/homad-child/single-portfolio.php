<?php
/**
 * Template Name: Portfolio Single (Case of Success)
 * Matches Blueprint "CASE OF SUCCESS"
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="site-content col-lg-12 col-12 col-md-12" role="main">

            <?php while ( have_posts() ) : the_post();
                $location = get_post_meta(get_the_ID(), '_homad_pf_location', true) ?: 'Ubicación Desconocida';
                $area = get_post_meta(get_the_ID(), '_homad_pf_area', true) ?: '0m²';
                $time = get_post_meta(get_the_ID(), '_homad_pf_time', true) ?: '0 Meses';
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('homad-portfolio-single'); ?>>

                <!-- Header -->
                <header class="entry-header" style="text-align: center; margin-bottom: 40px;">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="meta-info" style="color: #666;">
                        <span><?php echo esc_html($location); ?></span> |
                        <span><?php echo esc_html($area); ?></span> |
                        <span>Ejecución: <?php echo esc_html($time); ?></span>
                    </div>
                </header>

                <!-- Gallery (Placeholder for Before/After Slider) -->
                <div class="portfolio-gallery" style="background: #eee; height: 400px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px;">
                    <p class="text-muted">[ Slider Antes vs. Después ]</p>
                    <?php the_post_thumbnail('large'); ?>
                </div>

                <!-- Content Grid -->
                <div class="row">
                    <div class="col-md-6">
                        <h3>El Reto</h3>
                        <p><?php echo get_post_meta(get_the_ID(), '_homad_pf_challenge', true) ?: "El cliente quería luz natural sin calor excesivo."; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h3>La Solución Homad</h3>
                        <p><?php echo get_post_meta(get_the_ID(), '_homad_pf_solution', true) ?: "Implementamos celosías de madera técnica y ventilación cruzada para reducir el uso de aire acondicionado."; ?></p>
                    </div>
                </div>

                <!-- CTA -->
                <div class="portfolio-cta" style="text-align: center; margin-top: 60px;">
                    <p style="font-size: 1.2rem; font-weight: bold;">¿Quieres un resultado así?</p>
                    <a href="<?php echo home_url('/proyectos'); ?>" class="btn btn-primary">Cotiza aquí</a>
                </div>

            </article>

            <?php endwhile; ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>
