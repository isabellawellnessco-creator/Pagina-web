<?php
/**
 * Best sellers section.
 */
?>
<section class="homad-best-sellers">
    <div class="homad-container">
        <header class="homad-section-header">
            <div>
                <h2><?php echo esc_html__('Best Sellers', 'homad'); ?></h2>
                <p><?php echo esc_html__('Venta rápida: favoritos de la semana.', 'homad'); ?></p>
            </div>
            <a class="homad-link" href="#"><?php echo esc_html__('See all', 'homad'); ?></a>
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
                            <?php $product = wc_get_product(get_the_ID()); ?>
                            <li class="homad-product">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="homad-product__media">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php else : ?>
                                            <span class="homad-product__placeholder" aria-hidden="true"></span>
                                        <?php endif; ?>
                                        <span class="homad-badge"><?php echo esc_html__('New', 'homad'); ?></span>
                                    </div>
                                    <div class="homad-product__body">
                                        <h3><?php the_title(); ?></h3>
                                        <div class="homad-product__meta">
                                            <span class="homad-product__price">
                                                <?php echo wp_kses_post($product ? $product->get_price_html() : esc_html__('Desde $---', 'homad')); ?>
                                            </span>
                                            <span class="homad-product__rating">★★★★★</span>
                                        </div>
                                    </div>
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
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <li class="homad-product">
                            <div class="homad-product__media">
                                <span class="homad-product__placeholder" aria-hidden="true"></span>
                                <span class="homad-badge"><?php echo esc_html__('Sale', 'homad'); ?></span>
                            </div>
                            <div class="homad-product__body">
                                <h3><?php echo esc_html(sprintf('Producto %d', $i)); ?></h3>
                                <div class="homad-product__meta">
                                    <span class="homad-product__price"><?php echo esc_html__('Desde $320', 'homad'); ?></span>
                                    <span class="homad-product__rating">★★★★☆</span>
                                </div>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>
