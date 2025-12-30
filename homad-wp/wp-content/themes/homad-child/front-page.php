<?php
/**
 * Template Name: Home Router
 * The main entry point.
 */

get_header(); ?>

<div class="homad-home-router">

    <!-- 1. HERO ROUTER -->
    <section class="hero-router">
        <div class="hero-content">
            <span class="eyebrow">Arquitectura • Interiorismo • Construcción</span>
            <h1 class="hero-title">
                <span class="text-gray">Tu Espacio Soñado.</span><br>
                <span class="text-white">Construido sin el Caos de Siempre.</span>
            </h1>
            <p class="hero-sub">Diseño de clase mundial y muebles que duran toda la vida. Todo gestionado en una sola plataforma.</p>

            <div class="hero-actions">
                <a href="<?php echo home_url('/shop'); ?>" class="homad-btn homad-btn--primary">Ver Colección Hogar</a>
                <a href="<?php echo home_url('/proyectos'); ?>" class="homad-btn homad-btn--outline">Cotizar Proyecto</a>
            </div>
        </div>

        <div class="hero-visual">
            <!-- Floating Card Overlay -->
            <div class="floating-card">
                <div class="fc-thumb" style="background-image: url('<?php echo HOMAD_CHILD_URI; ?>/assets/images/closet-thumb.jpg');"></div>
                <div class="fc-info">
                    <h4>Sistemas de Almacenaje</h4>
                    <p>Maximiza cada centímetro.</p>
                    <a href="<?php echo home_url('/shop'); ?>" class="link-arrow">Shop Now →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. BEST SELLERS STRIP -->
    <section class="section-strip">
        <div class="strip-header">
            <h2>Piezas que Elevan tu Hogar al Instante</h2>
            <a href="<?php echo home_url('/shop'); ?>">Ver Todo</a>
        </div>
        <div class="product-scroller">
            <!-- Hardcoded for visual fidelity as per Blueprint, ideally dynamic later -->
            <div class="homad-card product-card">
                <div class="pc-tag">Top Ventas</div>
                <div class="pc-img"></div>
                <div class="pc-info">
                    <h3>Escritorio 'Air' Minimalista</h3>
                    <p class="material">Roble Técnico Soft-Touch (HPL)</p>
                    <span class="price">S/ 890</span>
                </div>
            </div>
            <div class="homad-card product-card">
                <div class="pc-tag new">Nuevo</div>
                <div class="pc-img"></div>
                <div class="pc-info">
                    <h3>Vanity 'Spa-Mode'</h3>
                    <p class="material">Superficie 100% Impermeable</p>
                    <span class="price">S/ 1,200</span>
                </div>
            </div>
             <div class="homad-card product-card">
                <div class="pc-img"></div>
                <div class="pc-info">
                    <h3>Mesa de Centro 'Orbit'</h3>
                    <p class="material">Piedra Sinterizada</p>
                    <span class="price">S/ 1,500</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. CATEGORY CHIPS -->
    <section class="section-chips">
        <div class="chip-scroller">
            <a href="<?php echo home_url('/shop?cat=cocinas'); ?>" class="homad-chip">Cocinas de Revista</a>
            <a href="<?php echo home_url('/shop?cat=banos'); ?>" class="homad-chip">Baños Modernos</a>
            <a href="<?php echo home_url('/shop?cat=closets'); ?>" class="homad-chip">Closets Inteligentes</a>
            <a href="<?php echo home_url('/shop?cat=salas'); ?>" class="homad-chip">Salas & Deco</a>
            <a href="<?php echo home_url('/shop?cat=iluminacion'); ?>" class="homad-chip">Iluminación</a>
        </div>
    </section>

    <!-- 4. SERVICES SNAPSHOT -->
    <section class="section-services">
        <div class="homad-container">
            <h2 class="section-title">Expertos en Crear Valor</h2>
            <div class="services-grid-2x2">
                <div class="service-glass-card">
                    <h3>Arquitectura</h3>
                    <p>Casas frescas y eficientes (Bioclimática).</p>
                </div>
                <div class="service-glass-card">
                    <h3>Interiorismo</h3>
                    <p>Espacios diseñados para vivir mejor.</p>
                </div>
                <div class="service-glass-card">
                    <h3>Construcción</h3>
                    <p>Olvida los retrasos. Llaves en mano.</p>
                </div>
                <div class="service-glass-card">
                    <h3>Inversión</h3>
                    <p>Rentabilidad segura antes de construir.</p>
                </div>
            </div>
            <div class="center-cta">
                <a href="<?php echo home_url('/proyectos'); ?>" class="homad-btn homad-btn--primary">Evaluar mi Proyecto</a>
            </div>
        </div>
    </section>

    <!-- 5. PACKAGES SNAPSHOT -->
    <section class="section-packages">
        <div class="homad-container">
            <div class="pkg-card b2c">
                <h3>Packs "Renovación Total"</h3>
                <p>Cocinas y Baños Listos. Instalación en 72h.</p>
                <a href="<?php echo home_url('/proyectos#packages'); ?>" class="homad-btn homad-btn--white">Ver Packs</a>
            </div>
            <div class="pkg-card b2b">
                <h3>Soluciones Inmobiliarias</h3>
                <p>Supply Chain para edificios. Acabados estandarizados.</p>
                <a href="<?php echo home_url('/proyectos#b2b'); ?>" class="homad-btn homad-btn--white">Cotizar B2B</a>
            </div>
        </div>
    </section>

    <!-- 6. SOCIAL PROOF -->
    <section class="section-proof">
        <div class="proof-content">
            <p class="quote">"Pensé que remodelar sería una pesadilla, pero Homad lo hizo en una semana y quedó espectacular."</p>
            <div class="author">
                <span class="name">— Andrea M., Miraflores.</span>
                <span class="stars">★★★★★</span>
            </div>
        </div>
    </section>

    <!-- 7. CTA FINAL -->
    <section class="section-final-cta">
        <div class="homad-container">
            <h2>Deja de postergar la casa que mereces.</h2>
            <a href="<?php echo home_url('/proyectos'); ?>" class="homad-btn homad-btn--primary large">Empezar mi Transformación Ahora</a>
        </div>
    </section>

</div>

<?php get_footer(); ?>
