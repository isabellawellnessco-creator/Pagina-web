<?php
/**
 * Best sellers section.
 */
?>
<section class="homad-best-sellers">
    <div class="homad-container">
        <header>
            <h2><?php echo esc_html__('Best Sellers', 'homad'); ?></h2>
            <p><?php echo esc_html__('TODO: Add subtitle copy for best sellers.', 'homad'); ?></p>
        </header>
        <div class="homad-best-sellers-grid">
            <?php if (class_exists('WooCommerce')) : ?>
                <?php
                $query = new WP_Query(array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'post_status' => 'publish',
                ));
                ?>
                <?php if ($query->have_posts()) : ?>
                    <ul class="homad-products">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <li class="homad-product">
                                <a href="<?php the_permalink(); ?>">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo esc_html__('TODO: Add product meta/price.', 'homad'); ?></p>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else : ?>
                    <p><?php echo esc_html__('No products available yet.', 'homad'); ?></p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php echo esc_html__('WooCommerce is not active. Placeholder products will appear here.', 'homad'); ?></p>
                <ul class="homad-products">
                    <li class="homad-product">Placeholder product 1</li>
                    <li class="homad-product">Placeholder product 2</li>
                    <li class="homad-product">Placeholder product 3</li>
                    <li class="homad-product">Placeholder product 4</li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
