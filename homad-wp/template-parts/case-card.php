<?php
/**
 * Template part for displaying a portfolio case study card.
 * To be used within a WP_Query loop for the 'portfolio' CPT.
 *
 * @package Homad
 */
?>

<div class="case-card">
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="case-card__image-wrapper">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large' ); ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="case-card__content">
        <h3 class="case-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="case-card__meta">
            <?php
            // Example of how to get custom field data
            $location = get_post_meta( get_the_ID(), 'location', true );
            if ( ! empty( $location ) ) {
                echo '<span class="case-card__location">' . esc_html( $location ) . '</span>';
            }

            $scope = get_post_meta( get_the_ID(), 'scope', true );
            if ( ! empty( $scope ) ) {
                echo '<span class="case-card__scope">' . esc_html( $scope ) . '</span>';
            }
            ?>
        </div>
    </div>
</div>
