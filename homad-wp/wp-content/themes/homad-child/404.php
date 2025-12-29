<?php
/**
 * 404 template.
 */
get_header();
?>
<section class="homad-404">
    <div class="homad-container">
        <h1><?php echo esc_html__('Page not found', 'homad'); ?></h1>
        <p><?php echo esc_html__('The content you are looking for is not available yet.', 'homad'); ?></p>
    </div>
</section>
<?php
get_footer();
