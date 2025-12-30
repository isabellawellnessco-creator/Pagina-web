<?php
/**
 * Seeder to create pages based on the Blueprint.
 * Usage: Run once, then disable or delete.
 */

function homad_run_seeder() {
    // Only run if admin and specifically requested (or on theme activation if desired, but let's be safe)
    if (!isset($_GET['homad_seed']) || $_GET['homad_seed'] !== 'true') {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    $pages = [
        [
            'title'    => 'Home',
            'slug'     => 'home',
            'content'  => '', // Controlled by front-page.php
            'template' => 'front-page.php'
        ],
        [
            'title'    => 'Shop',
            'slug'     => 'shop',
            'content'  => '', // Controlled by WooCommerce
            'template' => ''
        ],
        [
            'title'    => 'Projects',
            'slug'     => 'proyectos',
            'content'  => '', // Controlled by page-projects.php
            'template' => 'page-projects.php'
        ],
        [
            'title'    => 'Nosotros',
            'slug'     => 'nosotros',
            'content'  => '', // Controlled by page-nosotros.php
            'template' => 'page-nosotros.php'
        ],
        [
            'title'    => 'NFC Bio',
            'slug'     => 'bio',
            'content'  => '', // Controlled by page-bio.php
            'template' => 'page-bio.php'
        ],
        // Legal Pages
        [
            'title'    => 'Contacto',
            'slug'     => 'contacto',
            'content'  => '<!-- Contact Form Shortcode Here -->',
            'template' => 'default'
        ],
        [
            'title'    => 'Libro de Reclamaciones',
            'slug'     => 'reclamaciones',
            'content'  => 'Formulario virtual de reclamaciones...',
            'template' => 'default'
        ],
        [
            'title'    => 'Política de Envíos',
            'slug'     => 'envios',
            'content'  => 'Cobertura y condiciones...',
            'template' => 'default'
        ],
         [
            'title'    => 'Términos y Condiciones',
            'slug'     => 'terminos',
            'content'  => 'Reglas claras...',
            'template' => 'default'
        ],
    ];

    foreach ($pages as $p) {
        $page_check = get_page_by_path($p['slug']);
        if (!isset($page_check->ID)) {
            $page_id = wp_insert_post([
                'post_type'    => 'page',
                'post_title'   => $p['title'],
                'post_content' => $p['content'],
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_name'    => $p['slug']
            ]);

            if ($p['template'] && $p['template'] !== 'default') {
                update_post_meta($page_id, '_wp_page_template', $p['template']);
            }

            // Set Front Page if Home
            if ($p['slug'] === 'home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }

             // Set Shop Page if Shop (WooCommerce usually handles this but good to force)
            if ($p['slug'] === 'shop' && class_exists('WooCommerce')) {
                update_option('woocommerce_shop_page_id', $page_id);
            }

            echo "Created Page: " . $p['title'] . "<br>";
        } else {
            // Update template just in case
            if ($p['template'] && $p['template'] !== 'default') {
                update_post_meta($page_check->ID, '_wp_page_template', $p['template']);
            }
            echo "Page Exists: " . $p['title'] . "<br>";
        }
    }

    // Create Categories if they don't exist
    if(taxonomy_exists('product_cat')){
        $cats = ['Cocinas', 'Baños', 'Closets', 'Salas', 'Iluminación'];
        foreach($cats as $cat){
            if(!term_exists($cat, 'product_cat')){
                wp_insert_term($cat, 'product_cat');
                 echo "Created Cat: " . $cat . "<br>";
            }
        }
    }

    exit('Seeding Complete');
}

add_action('init', 'homad_run_seeder');
