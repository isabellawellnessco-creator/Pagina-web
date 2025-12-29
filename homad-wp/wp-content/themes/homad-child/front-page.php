<?php
/**
 * Front page template.
 */
get_header();
?>
<?php
get_template_part('template-parts/home/hero');
get_template_part('template-parts/home/best-sellers');
get_template_part('template-parts/home/category-chips');
get_template_part('template-parts/home/services-snapshot');
get_template_part('template-parts/home/packages-snapshot');
get_template_part('template-parts/home/proof-mini');
get_template_part('template-parts/home/cta-final');
?>
<?php
get_footer();
