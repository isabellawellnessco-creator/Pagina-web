<?php
/**
 * Plantilla personalizada para el detalle de producto (PDP).
 * Sobrescribe al template de Woodmart.
 */
defined('ABSPATH') || exit;
get_header('shop');
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <?php
    while ( have_posts() ) :
      the_post();
      /* Utiliza el template de WooCommerce para mostrar el producto,
         pero lo envuelve en nuestro panel para mantener consistencia. */
      wc_get_template_part( 'content', 'single-product' );
    endwhile;
    ?>
  </div>
</div>
<?php get_footer('shop'); ?>
