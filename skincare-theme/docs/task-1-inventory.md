# Tarea 1: Inventario técnico y mapeo de referencias (F8)

## 1) Mapa de plantillas WordPress ↔ HTML de referencia

Cada plantilla en `template-parts/f8` declara el HTML fuente en el comentario de cabecera. Mapeo completo:

| Plantilla WordPress | HTML de referencia | Tipo de página (estimado) |
| --- | --- | --- |
| `template-parts/f8/page-account.php` | `refs/f8/Accou.html` | Cuenta (dashboard) |
| `template-parts/f8/page-login.php` | `refs/f8/Acc.html` | Login | 
| `template-parts/f8/page-vegan.php` | `refs/f8/Ve.html` | Producto (Vegan Kombucha) |
| `template-parts/f8/page-terms.php` | `refs/f8/Ter.html` | Términos |
| `template-parts/f8/page-korean.php` | `refs/f8/Korean.html` | Colección/landing (Korean) |
| `template-parts/f8/page-makeup.php` | `refs/f8/Mak.html` | Colección/landing (Makeup) |
| `template-parts/f8/page-store-locator.php` | `refs/f8/loct.html` | Store Locator |
| `template-parts/f8/page-shipping.php` | `refs/f8/Shi.html` | Shipping |
| `template-parts/f8/page-wishlist.php` | `refs/f8/Wis.html` | Wishlist |
| `template-parts/f8/page-faqs.php` | `refs/f8/FAQs.html` | FAQs |
| `template-parts/f8/page-privacy.php` | `refs/f8/Priv.html` | Privacy |
| `template-parts/f8/page-rewards.php` | `refs/f8/Rewa.html` | Rewards |
| `template-parts/f8/page-care.php` | `refs/f8/Care.html` | Careers/Care |
| `template-parts/f8/page-skin.php` | `refs/f8/Skin.html` | Skin | 
| `template-parts/f8/page-learn.php` | `refs/f8/Lear.html` | Learn |
| `template-parts/f8/page-contact.php` | `refs/f8/Cont.html` | Contact |
| `template-parts/f8/template-landing.php` | `refs/f8/2026.html` | Landing 2026 |

## 2) Inventario de carpetas de assets de referencia

Carpetas detectadas en `refs/f8/*_files` (cada una corresponde a un HTML):

- `2026_files`
- `Acc_files`
- `Accou_files`
- `Care_files`
- `Cont_files`
- `FAQs_files`
- `Korean_files`
- `Lear_files`
- `Mak_files`
- `Priv_files`
- `Rewa_files`
- `Shi_files`
- `Skin_files`
- `Ter_files`
- `Ve_files`
- `Wis_files`
- `loct_files`

## 3) Hallazgo clave: rutas locales en plantillas

Se encontraron numerosas referencias a rutas locales del estilo `./<X>_files/...` dentro de las plantillas de `template-parts/f8`. Esto implica que **sin migración/rewrite de assets** las imágenes/JS/CSS no cargarán en WordPress.

Ejemplo (solo muestra, no exhaustivo):
- `page-vegan.php` usa múltiples rutas `./Ve_files/...` para imágenes, CSS y SVG.
- `template-landing.php` usa `./2026_files/...` para imágenes y scripts.

## 4) Clasificación de assets por criticidad (general)

**Críticos (rompen layout o funcionalidad si faltan):**
- CSS principales (`base.css`, `core.css`, `custom.css`, `main.css` o equivalentes dentro de cada carpeta).
- JS de comportamiento UI (`bundle.min.js`, `main.js`, `product.js`, `glider.min.js`, `storefront-*.js`, etc.).
- Imágenes hero/banners principales.

**Importantes (afectan UX/UI pero no rompen navegación):**
- SVGs de iconos y badges.
- Imágenes secundarias de producto (hover/galería).

**Opcionales (degradación leve):**
- Assets duplicados o versiones alternativas de imágenes.
- Recursos analytics/trackers que no se usan en WP.

## 5) Resultado de la Tarea 1

- Se completó el **mapeo total** de plantillas WordPress a HTML de referencia.
- Se identificaron todas las carpetas de assets que deben migrarse o reescribirse.
- Se confirmó la presencia de **rutas locales** en todas las plantillas relevantes.

**Siguiente paso (Tarea 2):** elegir estrategia global de assets (migración interna al tema o reescritura a CDN) y ejecutar actualización de rutas.
