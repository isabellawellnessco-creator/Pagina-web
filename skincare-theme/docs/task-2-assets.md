# Tarea 2: Encolado y estrategia de assets (F8)

## 1) Inventario resumido de dependencias encontradas

En `refs/f8/*.html` se observaron dos tipos de dependencias:

- **Locales (`./*_files/...`)**: paquetes de CSS base/tailwind, estilos de wishlist, sliders (`glider`), ajustes de checkout, y scripts específicos como **showcase gallery**, **loyalty hero**, **jobly** y **store locator**.
- **Externas (CDN/servicios)**: scripts de Shopify (checkout/analytics), Judge.me, Klaviyo, Klarna, Stockist, y fuentes/íconos servidos desde `cdn.shopify.com`/`cdnwidget.judge.me`.

## 2) Estrategia aplicada

- **CSS/JS críticos de layout/UI** se migraron a `skincare-theme/assets/f8/` y se encolan desde `functions.php` para las páginas F8.
- **CSS específicos por página** (colecciones, vegan, store locator) se encolan condicionalmente para reducir conflictos de estilos entre plantillas.
- **Scripts específicos por página** (galería, rewards, careers, store locator) se encolan condicionalmente según la slug o plantilla.
- **Dependencias externas** se mantienen como URLs absolutas (CDN) al provenir de servicios de terceros o assets alojados por Shopify/partners. Estos no se migran por evitar duplicar servicios externos o introducir dependencias no operativas fuera del ecosistema original.

## 3) Decisiones sobre terceros (CDN)

Se optó por **mantener externos** los siguientes recursos, porque dependen de servicios de terceros y/o autenticación específica de Shopify:

- **Judge.me** (widgets de reviews).
- **Shopify checkout / analytics** (scripts de checkout, GTM, pixel, etc.).
- **Klaviyo** (marketing/forms).
- **Klarna** (fuentes/SDK de mensajería).
- **Stockist** (mapas y widgets del store locator).

## 4) Assets locales migrados

**CSS (en `assets/f8/css/`):**

- `fonts.css`, `core.css`, `custom.css`, `base.css`
- `glider.min.css`
- `collection-tile-controller.af177681.css`
- `vegan-form.css`, `vegan-main.css`, `vegan-klarna-fonts.css`
- `locator-fonts.css`, `locator-fonts-2.css`

**JS (en `assets/f8/js/`):**

- `glider.min.js`
- `showcase-gallery.js`
- `anime.min.js`, `loyalty-hero.js`
- `jobly.js`
- `store-locator-widget.js`

## 5) Orden de carga

El orden de carga se definió mediante dependencias en `wp_enqueue_style` para asegurar la secuencia aproximada de los HTML de referencia:

1. Fuentes
2. Core/tailwind
3. Base + custom
4. Estilos de slider (glider)
5. Estilos por plantilla (colecciones, vegan, locator)

Los scripts específicos se cargan después del slider base y de forma condicional según la página.
