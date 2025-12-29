<?php
/**
 * Archive template.
 */
get_header();
?>
<section class="homad-archive">
    <div class="homad-container">
        <h1><?php the_archive_title(); ?></h1>
        <?php if (have_posts()) : ?>
            <ul class="homad-archive-list">
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p><?php echo esc_html__('No content found.', 'homad'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
