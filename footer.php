        </div><!-- #content -->

        <footer id="colophon" class="site-footer">
            <?php get_template_part( 'template-parts/footer/main' ); // Assuming a main footer part ?>
        </footer><!-- #colophon -->

        <?php
        // Fixed Bottom Navigation for Mobile
        get_template_part('template-parts/navigation/bottom-nav');
        ?>

    </div><!-- .homad-panel-container -->

</div><!-- #homad-app-root -->

<?php wp_footer(); ?>

</body>
</html>
