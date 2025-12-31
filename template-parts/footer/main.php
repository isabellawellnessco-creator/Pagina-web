<div class="homad-footer-main">
    <div class="container">
        <div class="footer-widgets">
             <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        <div class="site-info">
            <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'homad' ) ); ?>">
                <?php
                /* translators: %s: CMS name, i.e. WordPress. */
                printf( esc_html__( 'Proudly powered by %s', 'homad' ), 'WordPress' );
                ?>
            </a>
            <span class="sep"> | </span>
                <?php
                /* translators: 1: Theme name, 2: Theme author. */
                printf( esc_html__( 'Theme: %1$s by %2$s.', 'homad' ), 'homad', 'Homad' );
                ?>
        </div><!-- .site-info -->
    </div>
</div>
