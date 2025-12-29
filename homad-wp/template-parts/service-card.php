<?php
/**
 * Template part for displaying a service card.
 * To be used within a WP_Query loop for the 'service' CPT.
 *
 * @package Homad
 */
?>

<div class="service-card">
    <div class="service-card__content">
        <h3 class="service-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="service-card__excerpt">
            <?php the_excerpt(); ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="service-card__cta">
            <?php esc_html_e( 'Learn More', 'homad' ); ?>
        </a>
    </div>
</div>
