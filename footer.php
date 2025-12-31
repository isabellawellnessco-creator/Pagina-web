<?php
/**
 * Footer Template
 * Closes the Panel Wrapper.
 */
?>
    </main><!-- .homad-main -->

    <!-- Mobile Bottom Nav (Fixed) -->
    <div class="hide-on-desktop">
        <?php get_template_part('template-parts/navigation/bottom-nav'); ?>
    </div>

    <!-- Desktop Footer (Inside Panel) -->
    <footer id="homad-footer" class="hide-on-mobile">
        <?php get_template_part('template-parts/footer/desktop'); ?>
    </footer>

</div><!-- .homad-panel-wrapper (Ends Here) -->

<?php wp_footer(); ?>
</body>
</html>
