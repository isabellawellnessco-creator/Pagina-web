<?php
/**
 * Template Name: Home Router
 * The main entry point.
 */

get_header(); ?>

<div class="main-page-wrapper">
    <div class="container-fluid p-0">
        <div class="row content-layout-wrapper no-gutters">
            <div class="site-content col-12" role="main">

                <div class="homad-home-router">

                    <!-- 1. HERO ROUTER -->
                    <!-- Background is handled via CSS in components.css .hero-router::before -->
                    <section class="hero-router">
                        <div class="hero-content">
                            <span class="eyebrow">Arquitectura • Interiorismo • Construcción</span>
                            <h1 class="hero-title">
                                <span class="text-gray">Tu Espacio Soñado.</span><br>
                                <span class="text-white">Construido sin el Caos de Siempre.</span>
                            </h1>
                            <p class="hero-sub">Diseño de clase mundial y gestión integral en una sola plataforma. Desde los planos hasta el último mueble.</p>

                            <div class="hero-actions">
                                <a href="<?php echo home_url('/shop'); ?>" class="homad-btn homad-btn--primary">Ver Colección Hogar</a>
                                <a href="<?php echo home_url('/proyectos'); ?>" class="homad-btn homad-btn--outline">Cotizar Proyecto</a>
                            </div>
                        </div>

                        <div class="hero-visual">
                            <!-- Floating Card Overlay -->
                            <div class="floating-card">
                                <div class="fc-info">
                                    <h4>Sistemas de Almacenaje</h4>
                                    <p>Maximiza cada centímetro con nuestros closets inteligentes.</p>
                                    <a href="<?php echo home_url('/shop'); ?>" class="link-arrow">Ver Catálogo →</a>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 2. BEST SELLERS STRIP -->
                    <section class="section-strip">
                        <div class="homad-container">
                            <div class="strip-header">
                                <div>
                                    <span class="eyebrow" style="color:var(--color-text-muted); margin-bottom: 5px; display:block;">Curado por Expertos</span>
                                    <h2>Piezas que Elevan tu Hogar</h2>
                                </div>
                                <a href="<?php echo home_url('/shop'); ?>" class="link-arrow" style="color:var(--color-primary)">Ver Todo el Catálogo →</a>
                            </div>

                            <!-- WooCommerce Grid Shortcode -->
                            <div class="woocommerce-products-wrapper">
                                <?php echo do_shortcode('[products limit="3" columns="3" orderby="popularity"]'); ?>
                            </div>
                        </div>
                    </section>

                    <!-- 3. CATEGORY CHIPS -->
                    <section class="section-chips">
                         <div class="homad-container">
                            <h4 style="margin-bottom: 20px; font-weight: 600;">Explora por Categoría:</h4>
                            <div class="chip-scroller">
                                <a href="<?php echo home_url('/shop?cat=cocinas'); ?>" class="homad-chip">🍽️ Cocinas</a>
                                <a href="<?php echo home_url('/shop?cat=banos'); ?>" class="homad-chip">🚿 Baños</a>
                                <a href="<?php echo home_url('/shop?cat=closets'); ?>" class="homad-chip">👗 Closets</a>
                                <a href="<?php echo home_url('/shop?cat=salas'); ?>" class="homad-chip">🛋️ Salas</a>
                                <a href="<?php echo home_url('/shop?cat=iluminacion'); ?>" class="homad-chip">💡 Iluminación</a>
                                <a href="<?php echo home_url('/shop?cat=deco'); ?>" class="homad-chip">🖼️ Decoración</a>
                            </div>
                        </div>
                    </section>

                    <!-- 4. SERVICES SNAPSHOT -->
                    <section class="section-services">
                        <div class="homad-container">
                            <div class="text-center" style="max-width: 700px; margin: 0 auto 50px;">
                                <span class="eyebrow" style="color:var(--color-text-muted)">Servicios Integrales</span>
                                <h2 class="section-title" style="margin-bottom: 20px;">Expertos en Crear Valor</h2>
                                <p style="font-size: 1.1rem; color: #666;">No somos solo una tienda de muebles. Somos un estudio de arquitectura y construcción que se encarga de todo.</p>
                            </div>

                            <div class="services-grid-2x2">
                                <div class="service-glass-card">
                                    <div style="font-size: 2rem; margin-bottom: 15px;">📐</div>
                                    <h3>Arquitectura</h3>
                                    <p>Diseño bioclimático y funcional. Creamos planos que optimizan la luz, el aire y tu presupuesto.</p>
                                </div>
                                <div class="service-glass-card">
                                    <div style="font-size: 2rem; margin-bottom: 15px;">🎨</div>
                                    <h3>Interiorismo</h3>
                                    <p>Espacios con personalidad. Seleccionamos materiales, colores y texturas que cuentan tu historia.</p>
                                </div>
                                <div class="service-glass-card">
                                    <div style="font-size: 2rem; margin-bottom: 15px;">🏗️</div>
                                    <h3>Construcción</h3>
                                    <p>Ejecución "Llave en Mano". Olvídate de tratar con contratistas; nosotros lo hacemos por ti.</p>
                                </div>
                                <div class="service-glass-card">
                                    <div style="font-size: 2rem; margin-bottom: 15px;">📈</div>
                                    <h3>Inversión</h3>
                                    <p>Maximiza tu retorno. Análisis de factibilidad para proyectos inmobiliarios y comerciales.</p>
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
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="pkg-card b2c">
                                        <div>
                                            <span class="eyebrow" style="color: rgba(255,255,255,0.7);">Para Propietarios</span>
                                            <h3>Packs "Renovación Total"</h3>
                                            <p style="opacity: 0.9; font-size: 1.1rem; margin-top: 10px;">Cocinas y Baños prediseñados de alta gama. Instalación express en 72 horas.</p>
                                        </div>
                                        <a href="<?php echo home_url('/proyectos#packages'); ?>" class="btn">Ver Packs</a>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="pkg-card b2b">
                                        <div>
                                            <span class="eyebrow" style="color: rgba(255,255,255,0.7);">Para Desarrolladores</span>
                                            <h3>Soluciones Inmobiliarias B2B</h3>
                                            <p style="opacity: 0.9; font-size: 1.1rem; margin-top: 10px;">Supply Chain integral para edificios. Acabados estandarizados y logística just-in-time.</p>
                                        </div>
                                        <a href="<?php echo home_url('/proyectos#b2b'); ?>" class="btn">Cotizar B2B</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 6. SOCIAL PROOF -->
                    <section class="section-proof">
                        <div class="homad-container">
                            <div class="proof-content">
                                <div class="stars" style="font-size: 1.5rem; margin-bottom: 20px;">★★★★★</div>
                                <p class="quote">"Pensé que remodelar sería una pesadilla, pero Homad lo hizo en una semana y el resultado superó mis expectativas. La atención al detalle es increíble."</p>
                                <div class="author">
                                    <span class="name">— Andrea M., Miraflores</span>
                                    <span style="display:block; font-size: 0.9rem; font-weight: 400; color: #999;">Proyecto de Cocina Integral</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 7. CTA FINAL -->
                    <section class="section-final-cta">
                        <div class="homad-container">
                            <h2 style="max-width: 800px; margin: 0 auto 30px;">Deja de postergar la casa que mereces.</h2>
                            <p style="font-size: 1.2rem; margin-bottom: 40px; opacity: 0.9;">Agenda una asesoría gratuita hoy mismo y recibe un presupuesto preliminar.</p>
                            <a href="<?php echo home_url('/proyectos'); ?>" class="homad-btn homad-btn--primary" style="background: #fff; color: #000; border: none;">Empezar mi Transformación</a>
                        </div>
                    </section>

                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
