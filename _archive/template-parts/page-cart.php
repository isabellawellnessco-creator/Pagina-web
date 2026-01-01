<?php
/* Template Name: Cart */
get_header();
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <section class="homad-section" id="cart">
      <h1 class="homad-title">Carrito de compras</h1>
      <?php echo do_shortcode('[woocommerce_cart]'); ?>
    </section>

    <!-- Cross‑sell: productos relacionados -->
    <section class="homad-section" id="cart-cross-sell">
      <h2 class="homad-title">También te puede gustar</h2>
      <div class="homad-products-grid">
        <?php echo do_shortcode('[products limit="4" columns="4" category="cross-sell"]'); ?>
      </div>
    </section>
  </div>
</div>
<?php get_footer(); ?>
