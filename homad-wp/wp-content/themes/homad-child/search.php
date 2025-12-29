<?php
/**
 * Search template.
 */
get_header();
?>
<section class="homad-search">
    <div class="homad-container">
        <h1><?php printf(esc_html__('Search results for: %s', 'homad'), get_search_query()); ?></h1>
        <?php if (have_posts()) : ?>
            <ul class="homad-search-list">
                <?php while (have_posts()) : the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p><?php echo esc_html__('No results found.', 'homad'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
