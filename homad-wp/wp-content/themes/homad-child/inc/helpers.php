<?php
/**
 * Helpers del theme (funciones pequeñas y seguras).
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

function homad_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

function homad_is_elementor_active() {
    return did_action('elementor/loaded') || defined('ELEMENTOR_VERSION');
}

/**
 * Devuelve el slug actual si es una página singular.
 */
function homad_get_current_slug() {
    if (is_singular()) {
        global $post;
        if ($post && !empty($post->post_name)) {
            return sanitize_title($post->post_name);
        }
    }
    return '';
}

/**
 * Inject Custom CSS Variables from Admin Settings.
 * Hooked to wp_head.
 */
function homad_inject_custom_vars() {
    $primary = get_option('homad_primary_color', '#333333');
    $accent  = get_option('homad_accent_color', '#65d5a3');
    ?>
    <style id="homad-custom-vars">
        :root {
            --homad-primary: <?php echo esc_attr($primary); ?>;
            --homad-accent: <?php echo esc_attr($accent); ?>;
            /* Derived colors can be added here */
        }
    </style>
    <?php
}
add_action('wp_head', 'homad_inject_custom_vars', 5);

/**
 * Helper para clases estándar de sección (para Elementor).
 */
function homad_section_class($extra = '') {
    $base = 'homad-section';
    $extra = trim((string) $extra);
    return $extra ? $base . ' ' . $extra : $base;
}
