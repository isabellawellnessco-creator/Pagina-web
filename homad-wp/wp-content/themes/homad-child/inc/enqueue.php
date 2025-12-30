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
     */
    $parent_style_path = get_template_directory() . '/style.css';
    wp_enqueue_style(
        'woodmart-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        file_exists($parent_style_path) ? filemtime($parent_style_path) : null
    );

    /**
     * 2) Encolar CSS del tema hijo en orden (tokens -> layout -> components -> woocommerce)
     */
    $css_files = array(
        'tokens.css',
        'layout.css',
        'components.css',
        'woocommerce.css',
        'print.css', // Print specific styles
        'projects-hub.css', // New Hub styles
        'motion.css' // New Motion styles
    );

    $deps = array('woodmart-parent-style');

    foreach ($css_files as $file) {
        $path = HOMAD_CHILD_DIR . '/assets/css/' . $file;
        if (file_exists($path)) {
            $handle = 'homad-' . basename($file, '.css');
            $media  = ($file === 'print.css') ? 'print' : 'all';

            wp_enqueue_style(
                $handle,
                HOMAD_CHILD_URI . '/assets/css/' . $file,
                $deps,
                filemtime($path),
                $media
            );
            if ($media === 'all') {
                $deps = array($handle);
            }
        }
    }

    // Enqueue AOS CSS (Animation Library)
    wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1');


    /**
     * 3) JS base del tema hijo & Motion Libraries
     */

    // External Motion Libraries
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js', array(), '1.0.29', true);
    wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);
    wp_enqueue_script('swup', 'https://unpkg.com/swup@4/dist/Swup.umd.js', array(), '4.0.0', true);

    $js_files = array(
        'homad-app.js',
        'homad-motion.js', // New Motion Logic
    );

    foreach ($js_files as $file) {
        $path = HOMAD_CHILD_DIR . '/assets/js/' . $file;
        if (file_exists($path)) {
            $handle = 'homad-' . basename($file, '.js');
            // Depend on external libs if loading motion
            $js_deps = ($file === 'homad-motion.js') ? array('lenis', 'aos', 'swup') : array();

            wp_enqueue_script(
                $handle,
                HOMAD_CHILD_URI . '/assets/js/' . $file,
                $js_deps,
                filemtime($path),
                true
            );
            // Localize script for AJAX
            wp_localize_script($handle, 'homad_vars', array(
                'ajax_url' => admin_url('admin-ajax.php')
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'homad_child_enqueue_assets', 20);
