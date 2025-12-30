<?php
/**
 * Template Name: Projects Hub (Service + Packages + Quote)
 * The "All-in-One" conversion page.
 */

get_header(); ?>

<div class="main-page-wrapper">
    <div class="container">
        <div class="row content-layout-wrapper">
            <div class="site-content col-lg-12 col-12 col-md-12" role="main">

                <div class="homad-projects-hub">

                    <!-- 1. HERO SERVICES -->
                    <section class="homad-hero-small">
                        <div class="homad-container">
                            <h1 class="homad-hero-title">Creamos Espacios, Construimos Futuro.</h1>
                            <p class="homad-hero-subtitle">Arquitectura, construcción y diseño interior bajo un mismo techo. Más fácil, más rápido, mejor resultado.</p>
                            <a href="#quote-wizard" class="btn btn-primary">Solicitar Propuesta</a>
                        </div>
                    </section>

                    <!-- R2: Sticky Tabs -->
                    <div class="homad-sticky-tabs" id="project-tabs" style="margin: 20px 0; border-bottom: 1px solid #eee;">
                        <div class="homad-container-scroll">
                            <a href="#services" class="tab-link active">Servicios</a>
                            <a href="#packages" class="tab-link">Paquetes</a>
                            <a href="#b2b" class="tab-link">B2B</a>
                            <a href="#quote-wizard" class="tab-link highlight">Cotizar</a>
                        </div>
                    </div>

                    <div class="homad-hub-content">

                        <!-- SERVICES -->
                        <section id="services" class="homad-hub-section">
                            <div class="homad-container">
                                <!-- Fallback if no CPTs yet, or query logic -->
                                <div class="homad-services-grid">
                                    <?php
                                    // Ideally we query 'service' CPT, but for now we ensure structure exists
                                    $services = new WP_Query(['post_type'=>'service', 'posts_per_page'=>-1]);
                                    if($services->have_posts()):
                                        while($services->have_posts()): $services->the_post();
                                            // ... existing loop
                                        endwhile;
                                        wp_reset_postdata();
                                    else: ?>
                                        <p>Explora nuestras soluciones personalizadas de arquitectura e interiorismo.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>

                        <!-- 2. BLOQUE PAQUETES (PACKAGES TAB) -->
                        <section id="packages" class="homad-hub-section bg-light">
                            <div class="homad-container">
                                <h2 class="section-title">Remodela sin Dolor de Cabeza</h2>

                                <!-- Hardcoded Tiers from Blueprint -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="homad-package-card">
                                            <div class="pkg-header">
                                                <h3>Pack Baño Nuevo</h3>
                                                <span class="price">Desde S/ 2,500</span>
                                            </div>
                                            <div class="pkg-body">
                                                <p>Tu baño de revista, instalado y funcionando.</p>
                                            </div>
                                            <button class="btn btn-primary full-width" onclick="HomadApp.prefillQuote('package', 'Baño Nuevo')">Ver Packs</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="homad-package-card">
                                            <div class="pkg-header">
                                                <h3>Pack Cocina Chef</h3>
                                                <span class="price">Desde S/ 6,000</span>
                                            </div>
                                            <div class="pkg-body">
                                                <p>Funcionalidad total y diseño que impresiona.</p>
                                            </div>
                                            <button class="btn btn-primary full-width" onclick="HomadApp.prefillQuote('package', 'Cocina Chef')">Ver Packs</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 3. BLOQUE B2B (EMPRESAS TAB) -->
                        <section id="b2b" class="homad-hub-section">
                            <div class="homad-container">
                                <div class="homad-b2b-banner">
                                    <div class="b2b-content">
                                        <h2>Maximiza la Rentabilidad de tu Proyecto</h2>
                                        <p>Aceleramos tus ventas equipando tus departamentos con acabados que enamoran al comprador. Calidad estandarizada, cero reclamos post-venta.</p>
                                        <button class="btn btn-dark" onclick="HomadApp.prefillQuote('b2b', 'Developer Inquiry')">Cotizar B2B</button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 4. QUOTE WIZARD (FORMULARIO PRINCIPAL) -->
                        <section id="quote-wizard" class="homad-hub-section bg-dark">
                            <div class="homad-container">
                                <div class="homad-wizard-wrapper">
                                    <h2 class="wizard-title">Hablemos de tu Proyecto</h2>

                                    <form id="homad-lead-form" class="homad-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
                                        <?php wp_nonce_field('homad_lead_action', 'security'); ?>
                                        <input type="hidden" name="action" value="homad_submit_lead">

                                        <!-- Pregunta Filtro 1 -->
                                        <div class="form-group">
                                            <label>¿Quién eres?</label>
                                            <div class="radio-group">
                                                <label><input type="radio" name="role" value="propietario"> Propietario</label>
                                                <label><input type="radio" name="role" value="inversionista"> Inversionista</label>
                                                <label><input type="radio" name="role" value="empresa"> Empresa</label>
                                            </div>
                                        </div>

                                        <!-- Pregunta Filtro 2 -->
                                        <div class="form-group">
                                            <label>¿Qué es lo más importante?</label>
                                            <div class="radio-group">
                                                <label><input type="radio" name="priority" value="design"> Tener un Diseño Único</label>
                                                <label><input type="radio" name="priority" value="speed"> Terminar Rápido</label>
                                                <label><input type="radio" name="priority" value="budget"> Maximizar Presupuesto</label>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Metros Cuadrados</label>
                                                <input type="number" name="m2" class="homad-input" placeholder="Ej: 120">
                                            </div>
                                            <div class="form-group">
                                                <label>Presupuesto</label>
                                                <select name="budget" class="homad-input">
                                                    <option value="<5k">Menos de S/ 20k</option>
                                                    <option value="5k-15k">S/ 20k - S/ 50k</option>
                                                    <option value="15k+">Más de S/ 50k</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>WhatsApp</label>
                                            <input type="tel" name="phone" class="homad-input" required placeholder="+51 999...">
                                        </div>

                                        <button type="submit" class="btn btn-primary full-width">Enviar Solicitud Gratuita</button>
                                    </form>
                                </div>
                            </div>
                        </section>

                    </div><!-- .homad-hub-content -->

                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
