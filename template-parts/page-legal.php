<?php
/* Template Name: Legal */
get_header();
?>
<div class="homad-panel">
  <div class="homad-panel__inner">
    <?php
    while ( have_posts() ) : the_post();
      the_title('<h1 class="homad-title">','</h1>');
      the_content();
    endwhile;
    ?>
  </div>
</div>
<?php get_footer(); ?>
