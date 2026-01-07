<?php
/**
 * Enqueue scripts and styles.
 *
 * @package homad
 */

defined('ABSPATH') || exit;

function homad_scripts() {
    $version = wp_get_theme()->get('Version');
    $uri     = get_stylesheet_directory_uri();

    // --- CSS ---
    // 1. Tokens & Base
    wp_enqueue_style('homad-tokens', $uri . '/assets/css/tokens.css', array(), $version);
    wp_enqueue_style('homad-layout', $uri . '/assets/css/layout.css', array('homad-tokens'), $version);

    // 2. Components
    wp_enqueue_style('homad-components', $uri . '/assets/css/components.css', array('homad-layout'), $version);

    // 3. Integrations
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('homad-woocommerce', $uri . '/assets/css/woocommerce.css', array('homad-components'), $version);
    }
    wp_enqueue_style('homad-elementor', $uri . '/assets/css/elementor.css', array('homad-components'), $version);

    // 4. Features
    wp_enqueue_style('homad-projects', $uri . '/assets/css/projects-hub.css', array('homad-components'), $version);
    wp_enqueue_style('homad-motion', $uri . '/assets/css/motion.css', array('homad-layout'), $version);

    // 5. Mobile Specific
    wp_enqueue_style('homad-mobile', $uri . '/assets/css/mobile.css', array('homad-components'), $version);

    // 6. Main Style (Empty but standard)
    wp_enqueue_style('homad-style', get_stylesheet_uri(), array('homad-mobile'), $version);

    // --- JS ---
    // Dependencies usually include jquery, but for modern app-like feel we might minimize it.
    // keeping jquery for compatibility with plugins.

    wp_enqueue_script('homad-app-shell', $uri . '/assets/js/app-shell.js', array('jquery'), $version, true);
    wp_enqueue_script('homad-app', $uri . '/assets/js/homad-app.js', array('homad-app-shell'), $version, true);
    wp_enqueue_script('homad-motion', $uri . '/assets/js/homad-motion.js', array('homad-app'), $version, true);

    // Conditional JS
    if (is_page_template('page-projects.php')) {
        wp_enqueue_script('homad-projects-tabs', $uri . '/assets/js/projects-tabs.js', array('homad-app'), $version, true);
        wp_enqueue_script('homad-projects-core', $uri . '/assets/js/homad-projects.js', array('homad-app'), $version, true);
    }

    if (is_page_template('page-logistics-platform.php')) {
        wp_enqueue_style('homad-logistics-platform', $uri . '/assets/css/logistics-platform.css', array('homad-components'), $version);
        wp_enqueue_script('homad-logistics-platform', $uri . '/assets/js/logistics-platform.js', array('homad-app'), $version, true);
    }

    if (is_page('quote') || is_page('cotizar')) { // Example check for quote wizard
        wp_enqueue_script('homad-quote-wizard', $uri . '/assets/js/quote-wizard.js', array('homad-app'), $version, true);
    }

    // Localize script for AJAX
    wp_localize_script('homad-app', 'homad_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('homad_nonce'),
        'home_url' => home_url('/'),
        'theme_uri' => $uri
    ));
}
add_action('wp_enqueue_scripts', 'homad_scripts');

/**
 * Admin Enqueue
 */
function homad_admin_scripts() {
    $uri = get_stylesheet_directory_uri();
    // Enqueue admin specific styles if needed, or re-use tokens
    wp_enqueue_style('homad-admin-tokens', $uri . '/assets/css/tokens.css');
}
add_action('admin_enqueue_scripts', 'homad_admin_scripts');
