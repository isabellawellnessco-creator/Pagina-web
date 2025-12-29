<?php
/**
 * Hooks mínimos WooCommerce (sin invadir; lo fino va en Parte 3).
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

function homad_child_woocommerce_minimal_hooks() {
    if (!homad_is_woocommerce_active()) {
        return;
    }

    /**
     * Aquí NO hacemos cambios agresivos todavía.
     * Solo dejamos preparado un hook para estilos/clases si lo necesitas.
     */
}
add_action('init', 'homad_child_woocommerce_minimal_hooks');
