        </div><!-- #content -->

        <footer id="colophon" class="site-footer">
            <div class="site-info">
                &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>
            </div><!-- .site-info -->
        </footer><!-- #colophon -->

    </div><!-- .homad-container -->

    <!-- Mobile Bottom Navigation (hidden on desktop) -->
    <nav class="mobile-bottom-nav">
         <?php
            wp_nav_menu( array(
                'theme_location' => 'mobile',
                'menu_class'     => 'menu',
                'container'      => false,
            ) );
        ?>
    </nav>

</div><!-- #page -->

<!-- Splash Screen for Mobile (controlled by splash.js) -->
<div id="splash-screen" class="splash-screen-container">
    <div class="splash-content">
        <p class="splash-line-thin">Transform Your</p>
        <h2 class="splash-line-strong">Home with Elegance!</h2>
        <button id="close-splash" class="btn btn-primary">Get Started</button>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
