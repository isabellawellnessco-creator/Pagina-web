<?php
/**
 * Single template.
 */
get_header();
?>
<section class="homad-single">
    <div class="homad-container">
        <?php
        while (have_posts()) :
            the_post();
            the_title('<h1>', '</h1>');
            the_content();
        endwhile;
        ?>
    </div>
</section>
<?php
get_footer();
