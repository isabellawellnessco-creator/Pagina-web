<?php
/**
 * Custom Logic & Shortcodes.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Trust Badge Shortcode [homad_trust_mini]
 */
function homad_shortcode_trust_mini() {
    ob_start();
    ?>
    <div class="homad-trust-strip-mini">
        <div class="trust-item"><span class="dashicons dashicons-saved"></span> Garantía Directa</div>
        <div class="trust-item"><span class="dashicons dashicons-truck"></span> Envíos Lima</div>
        <div class="trust-item"><span class="dashicons dashicons-hammer"></span> Instalación</div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('homad_trust_mini', 'homad_shortcode_trust_mini');

/**
 * Filter Drawer Trigger [homad_filter_trigger]
 */
function homad_shortcode_filter_trigger() {
    return '<button class="homad-btn homad-btn--outline" id="homad-filter-trigger">Filters</button>';
}
add_shortcode('homad_filter_trigger', 'homad_shortcode_filter_trigger');
