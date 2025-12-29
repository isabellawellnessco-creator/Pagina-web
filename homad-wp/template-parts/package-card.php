<?php
/**
 * Template part for displaying a package card.
 * To be used within a WP_Query loop for the 'package' CPT.
 *
 * @package Homad
 */
?>

<div class="package-card">
    <div class="package-card__content">
        <h3 class="package-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="package-card__meta">
            <?php
            // Example of how to get custom field data
            $price_from = get_post_meta( get_the_ID(), 'price_from', true );
            if ( ! empty( $price_from ) ) {
                echo '<span class="package-card__price">From: ' . esc_html( $price_from ) . '</span>';
            }

            $eta = get_post_meta( get_the_ID(), 'eta', true );
            if ( ! empty( $eta ) ) {
                echo '<span class="package-card__eta">ETA: ' . esc_html( $eta ) . '</span>';
            }
            ?>
        </div>
        <div class="package-card__includes">
             <?php
            // Example for a repeater field or a simple list in a custom field
            // $includes = get_post_meta( get_the_ID(), 'includes', true );
            // if ( ! empty( $includes ) ) {
            //     echo '<ul>';
            //     foreach ( (array) $includes as $item ) {
            //         echo '<li>' . esc_html( $item ) . '</li>';
            //     }
            //     echo '</ul>';
            // }
            ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="package-card__cta">
            <?php esc_html_e( 'View Package', 'homad' ); ?>
        </a>
    </div>
</div>
