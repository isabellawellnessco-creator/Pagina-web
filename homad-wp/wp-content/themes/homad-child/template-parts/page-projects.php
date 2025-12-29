<?php
/* Template Name: Projects */
get_header(); ?>
<div class="homad-panel">
  <div class="homad-panel__inner">

    <!-- Projects Hero -->
    <section class="homad-section" id="projects-hero">
      <h1 class="homad-title">Nuestros proyectos y servicios</h1>
      <p class="homad-subtitle">Diseño + Paquetes + Construcción/instalación. Modalidad: Lima y remoto.</p>
      <a href="#quote" class="homad-btn homad-btn--primary">Request Quote</a>
    </section>

    <!-- Tabs -->
    <nav class="homad-nav-pills" id="projects-tabs">
      <a href="#services" class="homad-nav-pill homad-nav-pill--active">Services</a>
      <a href="#packages" class="homad-nav-pill homad-nav-pill--inactive">Packages</a>
      <a href="#b2b" class="homad-nav-pill homad-nav-pill--inactive">B2B</a>
    </nav>

    <!-- Services Section -->
    <section class="homad-section" id="services">
      <h2 class="homad-title">Services</h2>
      <?php
      $services=new WP_Query(array('post_type'=>'service','posts_per_page'=>4));
      while($services->have_posts()): $services->the_post(); ?>
        <div class="homad-card">
          <div class="homad-card__pad">
            <h3 class="homad-title" style="font-size:20px;"><?php the_title(); ?></h3>
            <p class="homad-subtitle"><?php the_excerpt(); ?></p>
            <a href="#quote" class="homad-btn homad-btn--secondary">Cotizar este servicio</a>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </section>

    <!-- Packages Section -->
    <section class="homad-section" id="packages">
      <h2 class="homad-title">Packages</h2>
      <?php
      $packages=new WP_Query(array('post_type'=>'package','posts_per_page'=>3));
      while($packages->have_posts()): $packages->the_post(); ?>
        <div class="homad-card">
          <div class="homad-card__pad">
            <h3 class="homad-title" style="font-size:20px;"><?php the_title(); ?></h3>
            <p class="homad-subtitle"><?php the_excerpt(); ?></p>
            <a href="#quote" class="homad-btn homad-btn--primary">Ver Paquete</a>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </section>

    <!-- B2B Section -->
    <section class="homad-section" id="b2b">
      <h2 class="homad-title">B2B</h2>
      <p class="homad-subtitle">Tipologías, volumen, estándar de acabados, QA, plazos, forma de trabajo.</p>
      <a href="#quote" class="homad-btn homad-btn--primary">Cotizar B2B</a>
    </section>

    <!-- Proof -->
    <section class="homad-section" id="proof">
      <h2 class="homad-title">Proof</h2>
      <?php
      $cases=new WP_Query(array('post_type'=>'portfolio','posts_per_page'=>3));
      while($cases->have_posts()): $cases->the_post(); ?>
        <div class="homad-card">
          <div class="homad-card__pad">
            <?php the_post_thumbnail('medium'); ?>
            <h3 class="homad-title" style="font-size:18px;"><?php the_title(); ?></h3>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </section>

    <!-- Quote Wizard -->
    <section class="homad-section" id="quote">
      <h2 class="homad-title">Request a Quote</h2>
      <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" class="homad-quote-form">
        <input type="hidden" name="action" value="homad_submit_quote">
        <div class="homad-form-row">
          <label for="country">País/Ciudad:</label>
          <input type="text" name="country" id="country" required>
        </div>
        <div class="homad-form-row">
          <label for="type">Tipo:</label>
          <select name="type" id="type">
            <option value="service">Servicio</option>
            <option value="package">Paquete</option>
            <option value="b2b">B2B</option>
          </select>
        </div>
        <div class="homad-form-row">
          <label for="area">m²:</label>
          <input type="number" name="area" id="area" required>
        </div>
        <div class="homad-form-row">
          <label for="budget">Presupuesto:</label>
          <input type="text" name="budget" id="budget" required>
        </div>
        <div class="homad-form-row">
          <label for="contact">Email/WhatsApp:</label>
          <input type="text" name="contact" id="contact" required>
        </div>
        <div class="homad-form-row">
          <button type="submit" class="homad-btn homad-btn--primary">Enviar</button>
        </div>
      </form>
    </section>

    <!-- FAQ -->
    <section class="homad-section homad-section--tight" id="faq">
      <h2 class="homad-title">Preguntas Frecuentes</h2>
      <div class="homad-faq">
        <details>
          <summary>¿Trabajan internacionalmente?</summary>
          <p>Sí, ofrecemos diseño remoto internacional...</p>
        </details>
        <!-- Añade más FAQ aquí -->
      </div>
    </section>

  </div><!-- .homad-panel__inner -->
</div><!-- .homad-panel -->
<?php get_footer(); ?>
