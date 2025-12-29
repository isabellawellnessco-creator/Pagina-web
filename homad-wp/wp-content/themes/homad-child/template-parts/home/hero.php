<?php
/**
 * Hero section.
 */
?>
<section class="homad-hero">
    <div class="homad-container homad-hero__grid">
        <div class="homad-hero__copy">
            <p class="homad-hero__kicker"><?php echo esc_html__('Arquitectura • Interiorismo • Construcción', 'homad'); ?></p>
            <p class="homad-hero__kicker"><?php echo esc_html__('Tienda + Paquetes listos', 'homad'); ?></p>
            <h1 class="homad-hero__title">
                <span><?php echo esc_html__('Encuentra el balance', 'homad'); ?></span>
                <span class="homad-hero__title-emphasis"><?php echo esc_html__('perfecto', 'homad'); ?></span>
                <span class="homad-hero__title-strong"><?php echo esc_html__('para tu espacio.', 'homad'); ?></span>
            </h1>
            <p class="homad-hero__subcopy"><?php echo esc_html__('Diseño remoto global. Ejecución e instalación: Lima, Perú (por ahora).', 'homad'); ?></p>
            <div class="homad-hero__actions">
                <a class="homad-button homad-button--primary" href="#"><?php echo esc_html__('Shop', 'homad'); ?></a>
                <a class="homad-button homad-button--ghost" href="#"><?php echo esc_html__('Request Quote', 'homad'); ?></a>
            </div>
            <a class="homad-hero__floating-cta" href="#" aria-label="<?php echo esc_attr__('Ir a la tienda', 'homad'); ?>">
                <span class="homad-hero__floating-arrow" aria-hidden="true">&#8599;</span>
                <span class="homad-hero__floating-text"><?php echo esc_html__('Go to the shop', 'homad'); ?></span>
            </a>
        </div>
        <div class="homad-hero__media">
            <div class="homad-hero__image" role="img" aria-label="<?php echo esc_attr__('Render principal del hero', 'homad'); ?>"></div>
            <div class="homad-hero__progress">
                <span class="homad-hero__progress-start">01</span>
                <span class="homad-hero__progress-line" aria-hidden="true"></span>
                <span class="homad-hero__progress-end">04</span>
            </div>
            <div class="homad-hero__scroll" aria-hidden="true">
                <span class="homad-hero__scroll-wheel"></span>
            </div>
        </div>
        <div class="homad-hero__overlays">
            <article class="homad-overlay-card">
                <div class="homad-overlay-card__thumb" aria-hidden="true"></div>
                <div class="homad-overlay-card__content">
                    <h3><?php echo esc_html__('Ropero modular — Best seller', 'homad'); ?></h3>
                    <p><?php echo esc_html__('Entrega rápida con instalación opcional.', 'homad'); ?></p>
                    <div class="homad-overlay-card__actions">
                        <a class="homad-button homad-button--white" href="#"><?php echo esc_html__('Shop Now', 'homad'); ?></a>
                        <button class="homad-icon-button homad-icon-button--white" type="button" aria-label="<?php echo esc_attr__('Añadir a favoritos', 'homad'); ?>">
                            <span aria-hidden="true">&#9829;</span>
                        </button>
                    </div>
                </div>
            </article>
            <div class="homad-side-media-card" role="img" aria-label="<?php echo esc_attr__('Producto destacado secundario', 'homad'); ?>"></div>
            <div class="homad-slider-controls">
                <button class="homad-slider-button" type="button" aria-label="<?php echo esc_attr__('Anterior', 'homad'); ?>">
                    <span aria-hidden="true">&#8592;</span>
                </button>
                <button class="homad-slider-button homad-slider-button--active" type="button" aria-label="<?php echo esc_attr__('Siguiente', 'homad'); ?>">
                    <span aria-hidden="true">&#8594;</span>
                </button>
            </div>
        </div>
    </div>
    <div class="homad-editorial-band">
        <div class="homad-container homad-editorial-band__content">
            <div class="homad-editorial-band__thumb" aria-hidden="true"></div>
            <div class="homad-editorial-band__cta" aria-hidden="true">&#8599;</div>
            <p class="homad-editorial-band__copy"><?php echo esc_html__('Eleva tu espacio con diseño integral, fabricación y ejecución en un solo lugar.', 'homad'); ?></p>
            <span class="homad-editorial-band__divider" aria-hidden="true"></span>
            <span class="homad-editorial-band__monogram" aria-hidden="true">H</span>
            <span class="homad-editorial-band__divider" aria-hidden="true"></span>
            <p class="homad-editorial-band__headline"><?php echo esc_html__('Compra piezas listas o cotiza proyectos completos con Homad.', 'homad'); ?></p>
        </div>
    </div>
</section>
