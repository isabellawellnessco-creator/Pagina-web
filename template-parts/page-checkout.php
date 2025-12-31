<?php
/* Template Name: Checkout */
get_header();
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <section class="homad-section" id="checkout">
      <h1 class="homad-title">Finaliza tu compra</h1>
      <p class="homad-subtitle">Completa los datos para recibir tu pedido</p>
      <?php echo do_shortcode('[woocommerce_checkout]'); ?>
    </section>
  </div>
</div>
<?php get_footer(); ?>
