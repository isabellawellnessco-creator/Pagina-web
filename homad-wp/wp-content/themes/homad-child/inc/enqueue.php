<?php
/**
 * Enqueue CSS/JS del tema hijo.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

function homad_child_enqueue_assets() {
    /**
     * 1) Encolar CSS del tema padre WoodMart
     * WoodMart normalmente expone su propio handle, pero por compatibilidad
     * encolamos directamente el style.css del tema padre.
     */
    $parent_style_path = get_template_directory() . '/style.css';
    wp_enqueue_style(
        'woodmart-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        file_exists($parent_style_path) ? filemtime($parent_style_path) : null
    );

    /**
     * 2) Encolar CSS del tema hijo en orden (tokens -> layout -> components -> elementor -> woocommerce)
     */
    $css_files = array(
        'tokens.css',
        'layout.css',
        'components.css',
        'woocommerce.css',
    );

    $deps = array('woodmart-parent-style');

    foreach ($css_files as $file) {
        $path = HOMAD_CHILD_DIR . '/assets/css/' . $file;
        if (file_exists($path)) {
            $handle = 'homad-' . basename($file, '.css');
            wp_enqueue_style(
                $handle,
                HOMAD_CHILD_URI . '/assets/css/' . $file,
                $deps,
                filemtime($path)
            );
            $deps = array($handle);
        }
    }

    /**
     * 3) JS base del tema hijo
     */
    $js_files = array(
        'homad-app.js',
    );

    foreach ($js_files as $file) {
        $path = HOMAD_CHILD_DIR . '/assets/js/' . $file;
        if (file_exists($path)) {
            $handle = 'homad-' . basename($file, '.js');
            wp_enqueue_script(
                $handle,
                HOMAD_CHILD_URI . '/assets/js/' . $file,
                array(),
                filemtime($path),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'homad_child_enqueue_assets', 20);
