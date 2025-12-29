<?php
/* Template Name: Shop */
get_header();
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <section class="homad-section" id="shop-header">
      <h1 class="homad-title">Shop</h1>
      <p class="homad-subtitle">Encuentra el mueble perfecto para tu espacio</p>
      <div class="homad-kicker">Navega por categorías y ordena los productos según tu preferencia</div>
    </section>

    <!-- Categorías rápidas -->
    <div class="homad-section" id="shop-categories">
      <?php echo do_shortcode('[product_categories columns="5"]'); ?>
    </div>

    <!-- Ordenamiento -->
    <div class="homad-section" id="shop-sorting">
      <?php woocommerce_catalog_ordering(); ?>
    </div>

    <!-- Grid de productos -->
    <section class="homad-section" id="shop-products">
      <div class="homad-products-grid">
        <?php echo do_shortcode('[products limit="12" columns="4"]'); ?>
      </div>
    </section>

    <!-- Trust mini -->
    <section class="homad-section homad-section--tight">
      <?php echo do_shortcode('[homad_trust_mini]'); ?>
    </section>
  </div>
</div>
<?php get_footer(); ?>
