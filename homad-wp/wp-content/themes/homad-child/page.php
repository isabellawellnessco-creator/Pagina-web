<?php
/**
 * Page template.
 */
get_header();
?>
<section class="homad-page">
    <div class="homad-container">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>
<?php
get_footer();
