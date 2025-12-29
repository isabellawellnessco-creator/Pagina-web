<?php
/* Template Name: Home */
get_header(); ?>
<div class="homad-panel">
  <div class="homad-panel__inner">

    <!-- Hero Router -->
    <section class="homad-hero" id="hero-router">
      <div class="homad-hero-left">
        <div class="homad-kicker">Arquitectura • Interiorismo • Construcción</div>
        <div class="homad-title">Transforma tu hogar con elegancia</div>
        <div class="homad-subtitle">Diseño remoto internacional. Ejecución e instalación: Lima (por ahora).</div>
        <div class="homad-hero-ctas">
          <a href="<?php echo esc_url( home_url('/shop') ); ?>" class="homad-btn homad-btn--primary">Shop</a>
          <a href="<?php echo esc_url( home_url('/projects') ); ?>" class="homad-btn homad-btn--secondary">Request Quote</a>
        </div>
      </div>
      <div class="homad-hero-right">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-chair.jpg" alt="Hero Chair" class="homad-hero-image">
        <div class="homad-hero-overlay">
          <div class="homad-hero-overlay-thumb">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/product-thumb.jpg" alt="Product thumb">
          </div>
          <div class="homad-hero-overlay-content">
            <div class="homad-title" style="font-size:18px;">Producto Destacado</div>
            <div class="homad-subtitle">Una breve descripción</div>
            <a href="<?php echo esc_url( home_url('/shop') ); ?>" class="homad-btn homad-btn--primary">Shop Now</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Best Sellers -->
    <section class="homad-section" id="best-sellers">
      <div class="homad-kicker">Top ventas</div>
      <h2 class="homad-title">Best Sellers</h2>
      <div class="homad-products-grid">
        <?php
        $args = array('post_type' => 'product','posts_per_page'=>4,'meta_key'=>'total_sales','orderby'=>'meta_value_num','order'=>'DESC');
        $products = new WP_Query($args);
        while ($products->have_posts()) : $products->the_post();
          wc_get_template_part('content','product');
        endwhile; wp_reset_postdata();
        ?>
      </div>
    </section>

    <!-- Category Quick Access -->
    <section class="homad-section" id="quick-categories">
      <h2 class="homad-title">Categorías</h2>
      <div class="homad-categories-row">
        <?php
        $terms = get_terms('product_cat',array('orderby'=>'name','order'=>'ASC','hide_empty'=>true));
        foreach($terms as $term){
          echo '<a class="homad-category-chip" href="'.get_term_link($term).'">'.$term->name.'</a>';
        }
        ?>
      </div>
    </section>

    <!-- Services Snapshot -->
    <section class="homad-section" id="services">
      <div class="homad-kicker">Servicios</div>
      <h2 class="homad-title">Servicios de Homad</h2>
      <div class="homad-services-grid">
        <?php
        $services = new WP_Query(array('post_type'=>'service','posts_per_page'=>4));
        while($services->have_posts()): $services->the_post(); ?>
          <div class="homad-card">
            <div class="homad-card__pad">
              <h3 class="homad-title" style="font-size:20px;"><?php the_title(); ?></h3>
              <p class="homad-subtitle"><?php the_excerpt(); ?></p>
              <a href="<?php echo esc_url( home_url('/projects') ); ?>#quote" class="homad-btn homad-btn--secondary">Request Quote</a>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>

    <!-- Packages Snapshot -->
    <section class="homad-section" id="packages">
      <div class="homad-kicker">Paquetes</div>
      <h2 class="homad-title">Paquetes & Soluciones</h2>
      <div class="homad-packages-grid">
        <?php
        $packages = new WP_Query(array('post_type'=>'package','posts_per_page'=>2));
        while($packages->have_posts()): $packages->the_post(); ?>
          <div class="homad-card">
            <div class="homad-card__pad">
              <h3 class="homad-title" style="font-size:20px;"><?php the_title(); ?></h3>
              <p class="homad-subtitle"><?php the_excerpt(); ?></p>
              <a href="<?php echo esc_url( home_url('/projects') ); ?>" class="homad-btn homad-btn--primary">Ver Paquetes</a>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>

    <!-- Proof Mini -->
    <section class="homad-section" id="proof">
      <div class="homad-kicker">Nuestros proyectos</div>
      <h2 class="homad-title">Portfolio Breve</h2>
      <div class="homad-proof-grid">
        <?php
        $cases = new WP_Query(array('post_type'=>'portfolio','posts_per_page'=>3));
        while($cases->have_posts()): $cases->the_post(); ?>
          <div class="homad-card">
            <div class="homad-card__pad">
              <?php the_post_thumbnail('medium'); ?>
              <h3 class="homad-title" style="font-size:18px;"><?php the_title(); ?></h3>
            </div>
          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <?php echo do_shortcode('[homad_trust_mini]'); ?>
    </section>

    <!-- CTA Final -->
    <section class="homad-section homad-section--tight" id="final-cta">
      <h2 class="homad-title">¿Listo para tu proyecto?</h2>
      <div class="homad-hero-ctas">
        <a href="<?php echo esc_url( home_url('/shop') ); ?>" class="homad-btn homad-btn--primary">Compra hoy</a>
        <a href="<?php echo esc_url( home_url('/projects') ); ?>#quote" class="homad-btn homad-btn--secondary">Cotiza en 24–48h</a>
      </div>
    </section>

  </div><!-- .homad-panel__inner -->
</div><!-- .homad-panel -->
<?php get_footer(); ?>
