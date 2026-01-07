<?php
/**
 * Template Name: Logistics Platform (Full App)
 */

get_header(); ?>

<div class="main-page-wrapper">
    <div class="container">
        <div class="row content-layout-wrapper">
            <div class="site-content col-lg-12 col-12 col-md-12" role="main">

                <div class="homad-logistics-platform">

                    <section class="platform-hero">
                        <div class="homad-container">
                            <span class="eyebrow">Plataforma 360 de Envíos</span>
                            <h1>Todo el flujo de almacén, delivery y pagos en una sola pantalla.</h1>
                            <p>Centraliza la operación con trazabilidad completa: desde la preparación del pedido hasta el seguimiento del pago, con WhatsApp integrado y responsables visibles en cada paso.</p>
                            <div class="platform-hero__actions">
                                <a href="#platform-shell" class="homad-btn homad-btn--primary">Ver la Plataforma</a>
                                <a href="#platform-details" class="homad-btn homad-btn--outline">Ver módulos</a>
                            </div>
                        </div>
                    </section>

                    <section id="platform-shell" class="platform-shell">
                        <div class="platform-shell__header">
                            <div>
                                <h2>Vista Operativa Unificada</h2>
                                <p>Panel maestro con todo lo crítico en tiempo real.</p>
                            </div>
                            <div class="platform-shell__filters">
                                <span class="platform-pill is-active">Hoy</span>
                                <span class="platform-pill">Semana</span>
                                <span class="platform-pill">Mes</span>
                            </div>
                        </div>

                        <div class="platform-shell__grid">
                            <div class="platform-metrics">
                                <div class="platform-metric-card">
                                    <p class="metric-label">Pedidos en preparación</p>
                                    <p class="metric-value">18</p>
                                    <span class="metric-meta">Almacén principal</span>
                                </div>
                                <div class="platform-metric-card">
                                    <p class="metric-label">En ruta</p>
                                    <p class="metric-value">32</p>
                                    <span class="metric-meta">5 motorizados activos</span>
                                </div>
                                <div class="platform-metric-card">
                                    <p class="metric-label">Pagos por confirmar</p>
                                    <p class="metric-value">7</p>
                                    <span class="metric-meta">S/ 4,350 en revisión</span>
                                </div>
                                <div class="platform-metric-card">
                                    <p class="metric-label">WhatsApp abiertos</p>
                                    <p class="metric-value">12</p>
                                    <span class="metric-meta">Chatbot + asesores</span>
                                </div>
                            </div>

                            <div class="platform-timeline">
                                <h3>Seguimiento de Envíos</h3>
                                <div class="timeline-item is-active">
                                    <span class="timeline-badge">01</span>
                                    <div>
                                        <h4>Pedido creado</h4>
                                        <p>Factura, método de pago y SLA registrados automáticamente.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <span class="timeline-badge">02</span>
                                    <div>
                                        <h4>Almacén en preparación</h4>
                                        <p>Checklist de picking + foto de verificación antes de salida.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <span class="timeline-badge">03</span>
                                    <div>
                                        <h4>Delivery asignado</h4>
                                        <p>Nombre, teléfono, unidad y contraseña de entrega visible.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <span class="timeline-badge">04</span>
                                    <div>
                                        <h4>En tránsito</h4>
                                        <p>Mapa, ubicación GPS y notificaciones automáticas por WhatsApp.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <span class="timeline-badge">05</span>
                                    <div>
                                        <h4>Entrega + pago confirmado</h4>
                                        <p>Firma digital, foto de entrega y seguimiento de pago cerrado.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="platform-details" class="platform-details">
                        <div class="platform-details__header">
                            <h2>Módulos Integrados en una sola vista</h2>
                            <p>Cada módulo vive en el mismo espacio para que tu equipo no salte entre pantallas.</p>
                        </div>

                        <div class="platform-details__body">
                            <aside class="platform-tabs" aria-label="Módulos de la plataforma">
                                <button class="platform-tab is-active" type="button" data-platform-target="overview">Resumen Ejecutivo</button>
                                <button class="platform-tab" type="button" data-platform-target="warehouse">Almacén &amp; Preparación</button>
                                <button class="platform-tab" type="button" data-platform-target="whatsapp">WhatsApp &amp; Atención</button>
                                <button class="platform-tab" type="button" data-platform-target="delivery">Delivery &amp; Contraseña</button>
                                <button class="platform-tab" type="button" data-platform-target="payments">Método &amp; Seguimiento de Pago</button>
                                <button class="platform-tab" type="button" data-platform-target="analytics">Indicadores &amp; Alertas</button>
                            </aside>

                            <div class="platform-panels">
                                <div class="platform-panel is-active" id="platform-overview">
                                    <h3>Resumen Ejecutivo en 60 segundos</h3>
                                    <p>Dashboard con prioridades del día, cargas pendientes, entregas críticas y estados de pago. Todo visible por cliente, por ruta o por almacén.</p>
                                    <ul class="platform-list">
                                        <li>Vista de pedidos con semáforo SLA.</li>
                                        <li>Filtros por responsable y estado.</li>
                                        <li>Alertas de pagos vencidos y entregas críticas.</li>
                                    </ul>
                                </div>

                                <div class="platform-panel" id="platform-warehouse">
                                    <h3>Almacén &amp; Preparación</h3>
                                    <p>Control visual de stock y preparación del pedido con checklist y evidencia.</p>
                                    <div class="platform-panel__grid">
                                        <div class="platform-mini-card">
                                            <h4>Picking guiado</h4>
                                            <p>Ruta óptima de recolección con códigos y ubicación exacta.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Validación en 2 pasos</h4>
                                            <p>Foto del paquete + confirmación del supervisor.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Estado de almacén</h4>
                                            <p>Disponibilidad, lotes críticos y reabastecimiento sugerido.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="platform-panel" id="platform-whatsapp">
                                    <h3>WhatsApp &amp; Atención</h3>
                                    <p>Comunicación centralizada con plantillas, chatbots y seguimiento automático.</p>
                                    <ul class="platform-list">
                                        <li>Mensajes automáticos para salida, entrega y cobro.</li>
                                        <li>Historial único por pedido con adjuntos y notas internas.</li>
                                        <li>Escalamiento a asesor cuando hay incidencias.</li>
                                    </ul>
                                </div>

                                <div class="platform-panel" id="platform-delivery">
                                    <h3>Delivery &amp; Contraseña</h3>
                                    <p>Asignación del delivery con credenciales visibles y control de seguridad.</p>
                                    <div class="platform-panel__grid">
                                        <div class="platform-mini-card">
                                            <h4>Responsable visible</h4>
                                            <p>Nombre, teléfono, placa y turno del motorizado.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Contraseña de entrega</h4>
                                            <p>Token dinámico que se comparte con el cliente.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Prueba de entrega</h4>
                                            <p>Firma digital, foto y geolocalización al cerrar.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="platform-panel" id="platform-payments">
                                    <h3>Método &amp; Seguimiento de Pago</h3>
                                    <p>Conciliación total del pago con status visible para operaciones y finanzas.</p>
                                    <ul class="platform-list">
                                        <li>Visa, Yape, transferencia o contraentrega.</li>
                                        <li>Seguimiento de pagos parciales y pendientes.</li>
                                        <li>Alertas automáticas cuando falta confirmación.</li>
                                    </ul>
                                </div>

                                <div class="platform-panel" id="platform-analytics">
                                    <h3>Indicadores &amp; Alertas</h3>
                                    <p>Decisiones rápidas con métricas claras y notificaciones priorizadas.</p>
                                    <div class="platform-panel__grid">
                                        <div class="platform-mini-card">
                                            <h4>Entregas críticas</h4>
                                            <p>Pedidos fuera de SLA con motivo y acción recomendada.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Pagos por cobrar</h4>
                                            <p>Lista inteligente con probabilidad de mora.</p>
                                        </div>
                                        <div class="platform-mini-card">
                                            <h4>Rendimiento por ruta</h4>
                                            <p>Tiempo promedio y satisfacción del cliente.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="platform-cta">
                        <div class="platform-cta__content">
                            <h2>Una sola página. Cero fricción.</h2>
                            <p>Convierte tu operación logística en una experiencia fluida, detallada e interactiva.</p>
                            <a href="<?php echo home_url('/contacto'); ?>" class="homad-btn homad-btn--primary">Solicitar demo</a>
                        </div>
                    </section>

                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
