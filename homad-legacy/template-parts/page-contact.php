<?php
/* Template Name: Contact */
get_header();
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <section class="homad-section" id="contact">
      <h1 class="homad-title">Contacto</h1>
      <p class="homad-subtitle">Cuéntanos cómo podemos ayudarte</p>
      <form class="homad-contact-form" method="post" action="">
        <div class="homad-form-row">
          <label for="reason">Motivo:</label>
          <select id="reason" name="reason">
            <option value="shop">Shop</option>
            <option value="projects">Projects</option>
            <option value="b2b">B2B</option>
          </select>
        </div>
        <div class="homad-form-row">
          <label for="message">Mensaje:</label>
          <textarea id="message" name="message" required></textarea>
        </div>
        <div class="homad-form-row">
          <label for="contact">Email/WhatsApp:</label>
          <input type="text" id="contact" name="contact" required>
        </div>
        <button type="submit" class="homad-btn homad-btn--primary">Enviar</button>
      </form>

      <!-- Canales rápidos -->
      <div class="homad-section homad-section--tight" id="contact-channels">
        <span class="homad-btn homad-btn--secondary" aria-disabled="true">WhatsApp (Automático)</span>
        <a href="mailto:info@homad.com" class="homad-btn homad-btn--secondary">Email</a>
      </div>
    </section>
  </div>
</div>
<?php get_footer(); ?>
