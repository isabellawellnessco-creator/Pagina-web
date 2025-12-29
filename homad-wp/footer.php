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

<?php wp_footer(); ?>

</body>
</html>
