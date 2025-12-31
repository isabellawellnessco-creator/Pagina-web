<?php
/**
 * Content Seeder
 * Automatically populates default Pages, Products, Menus, and Settings.
 * Context: Peru, Homad Brand.
 */

function homad_seed_content() {

    // --- 1. Pages (Home, Shop, Projects, Contact, Legal) ---
    $pages = [
        'Home' => [
            'content' => '', // Content is in front-page.php
            'template' => 'front-page.php'
        ],
        'Shop' => [
            'content' => '', // WooCommerce handles this
            'template' => 'default'
        ],
        'Proyectos' => [
            'content' => '<!-- wp:shortcode -->[homad_projects_hub]<!-- /wp:shortcode -->',
            'template' => 'page-projects.php'
        ],
        'Contacto' => [
            'content' => '<!-- wp:paragraph --><p>Contáctanos para iniciar tu proyecto.</p><!-- /wp:paragraph -->',
            'template' => 'default'
        ],
        // Legal Pages
        'Política de Envíos' => [
            'content' => '<!-- wp:paragraph --><p>En <strong>Homad</strong>, nos esforzamos por llevar el diseño a cada rincón del Perú.</p><!-- /wp:paragraph -->',
            'template' => 'default'
        ],
        'Políticas de Devolución' => [
            'content' => '<!-- wp:paragraph --><p>Queremos que esté completamente satisfecho con su compra en <strong>Homad</strong>.</p><!-- /wp:paragraph -->',
            'template' => 'default'
        ],
        'Privacidad y Datos' => [
            'content' => '<!-- wp:paragraph --><p>En <strong>Homad</strong>, tomamos muy en serio la seguridad de su información.</p><!-- /wp:paragraph -->',
            'template' => 'default'
        ],
        'Términos y Condiciones' => [
            'content' => '<!-- wp:paragraph --><p>Bienvenido a Homad. Al navegar y comprar en este sitio web, usted acepta los términos.</p><!-- /wp:paragraph -->',
            'template' => 'default'
        ]
    ];

    foreach ($pages as $title => $data) {
        $page_check = get_page_by_title($title);
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'   => $title,
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'page_template' => $data['template']
            ]);

            // Set static front page if it's 'Home'
            if ($title === 'Home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
            // Set Shop page for WooCommerce if it's 'Shop'
            if ($title === 'Shop' && class_exists('WooCommerce')) {
                update_option('woocommerce_shop_page_id', $page_id);
            }
        }
    }

    // --- 2. Seed Services (CPT: service) ---
    $services = [
        'Diseño de Arquitectura' => 'Desarrollamos proyectos arquitectónicos integrales, fusionando estética y funcionalidad.',
        'Interiorismo' => 'Transformamos ambientes mediante una selección curada de mobiliario, iluminación y materiales.',
        'Gestión de Construcción' => 'Supervisión y ejecución de obra con altos estándares de calidad.',
        'Factibilidad de Proyectos' => 'Análisis técnico y económico para asegurar la viabilidad de su inversión.'
    ];

    foreach ($services as $title => $content) {
        if (!get_page_by_title($title, OBJECT, 'service')) {
            wp_insert_post([
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'service',
            ]);
        }
    }

    // --- 3. WooCommerce Dummy Data ---
    if (class_exists('WooCommerce')) {
        // Categories
        $categories = ['Cocinas', 'Baños', 'Closets', 'Salas', 'Iluminación'];
        foreach ($categories as $cat_name) {
            if (!term_exists($cat_name, 'product_cat')) {
                wp_insert_term($cat_name, 'product_cat');
            }
        }

        // Dummy Products
        $products = [
            [
                'name' => 'Sofá Modular Cloud',
                'cat' => 'Salas',
                'price' => '4500',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Lámpara de Pie Arco',
                'cat' => 'Iluminación',
                'price' => '1200',
                'image' => 'https://images.unsplash.com/photo-1513506003011-3b032f737104?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Mesa de Centro Mármol',
                'cat' => 'Salas',
                'price' => '2800',
                'image' => 'https://images.unsplash.com/photo-1532372320572-cda25653a26d?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Sistema Closet Walk-in',
                'cat' => 'Closets',
                'price' => '8500',
                'image' => 'https://images.unsplash.com/photo-1551488852-081bd4c9a633?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Vanitorio Flotante Roble',
                'cat' => 'Baños',
                'price' => '3200',
                'image' => 'https://images.unsplash.com/photo-1584622050111-993a426fbf0a?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Isla de Cocina Cuarzo',
                'cat' => 'Cocinas',
                'price' => '12000',
                'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&q=80&w=800'
            ],
             [
                'name' => 'Silla Comedor Eames',
                'cat' => 'Salas',
                'price' => '450',
                'image' => 'https://images.unsplash.com/photo-1517705008128-361805f42e86?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'name' => 'Espejo Circular LED',
                'cat' => 'Baños',
                'price' => '890',
                'image' => 'https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&q=80&w=800'
            ],
        ];

        foreach ($products as $p) {
            if (!get_page_by_title($p['name'], OBJECT, 'product')) {
                $product = new WC_Product_Simple();
                $product->set_name($p['name']);
                $product->set_status('publish');
                $product->set_catalog_visibility('visible');
                $product->set_price($p['price']);
                $product->set_regular_price($p['price']);
                $product->set_description("Producto de demostración para Homad. Calidad premium y diseño exclusivo.");
                $product->set_short_description("Diseño moderno y materiales duraderos.");

                // Set Category
                $cat_term = get_term_by('name', $p['cat'], 'product_cat');
                if ($cat_term) {
                    $product->set_category_ids([$cat_term->term_id]);
                }

                // Note: Image sideloading is complex in a seeder without allow_url_fopen sometimes,
                // but we can try to set a placeholder or just rely on the frontend to handle missing images if we can't upload.
                // For this task, we will just create the product data.
                // Advanced: We could sideload media, but let's stick to data first to ensure stability.

                $product->save();
            }
        }
    }

    // --- 4. Navigation Menu ---
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        // Add Items
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => 'Inicio',
            'menu-item-object' => 'page',
            'menu-item-object-id' => get_page_by_title('Home')->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ]);

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => 'Tienda',
            'menu-item-object' => 'page',
            'menu-item-object-id' => get_page_by_title('Shop')->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ]);

         wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => 'Proyectos',
            'menu-item-object' => 'page',
            'menu-item-object-id' => get_page_by_title('Proyectos')->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ]);

        // Assign to location 'primary' (assuming theme registers 'primary')
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id; // Check functions.php if 'primary' is the key
        set_theme_mod('nav_menu_locations', $locations);
    }

}

add_action('init', 'homad_seed_content');
