<?php
/**
 * Shortcodes del theme hijo.
 *
 * Importante:
 * - La lógica “real” y reusable la haremos en el plugin homad-core (Parte 2).
 * - Aquí dejamos solo “wrappers” seguros por si Elementor los usa antes de activar el plugin.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

/**
 * [homad_trust_mini] — bloque compacto de confianza (fallback).
 */
function homad_shortcode_trust_mini() {
    ob_start();
    ?>
    <div class="homad-trust-mini" role="note" aria-label="Trust information">
        <div class="homad-trust-mini__item">Envíos / ETA claros</div>
        <div class="homad-trust-mini__item">Devoluciones y garantía</div>
        <div class="homad-trust-mini__item">Soporte rápido</div>
    </div>
    <?php
    return (string) ob_get_clean();
}
add_shortcode('homad_trust_mini', 'homad_shortcode_trust_mini');
