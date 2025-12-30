    </div><!-- .main-content -->

    <!-- Desktop Footer (Simplified for Panel) -->
    <footer class="site-footer mobile-hidden">
        <div class="homad-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4>HOMAD</h4>
                    <p>Ingeniería del Bienestar.</p>
                </div>
                <div class="footer-col">
                    <a href="<?php echo home_url('/reclamaciones'); ?>">Libro de Reclamaciones</a>
                    <a href="<?php echo home_url('/terminos'); ?>">Términos y Condiciones</a>
                </div>
            </div>
        </div>
    </footer>

</div><!-- .panel-container -->

<!-- Mobile Bottom Navigation (Fixed outside panel) -->
<nav class="homad-bottom-nav desktop-hidden">
    <a href="<?php echo home_url('/'); ?>" class="nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
        <span>🏠</span>
        <span>Home</span>
    </a>
    <a href="<?php echo home_url('/shop'); ?>" class="nav-item <?php echo is_shop() ? 'active' : ''; ?>">
        <span>🛍️</span>
        <span>Shop</span>
    </a>
    <a href="<?php echo home_url('/proyectos'); ?>" class="nav-item <?php echo is_page('proyectos') ? 'active' : ''; ?>">
        <span>📋</span>
        <span>Quote</span>
    </a>
    <a href="<?php echo wc_get_cart_url(); ?>" class="nav-item">
        <span>🛒</span>
        <span>Cart</span>
    </a>
    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="nav-item">
        <span>👤</span>
        <span>Profile</span>
    </a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
