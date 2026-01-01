<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Seeder {

    public function run() {
        // Only run if not already seeded
        if ( get_option( 'skincare_content_seeded' ) ) return;

        // Ensure Spanish Language Settings
        update_option( 'WPLANG', 'es_ES' );

        // Permalink Structure
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );

        $this->create_terms();
        $this->create_products();
        $this->create_pages();
        $this->create_menus();
        $this->create_posts();

        update_option( 'skincare_content_seeded', true );
        $wp_rewrite->flush_rules();
    }

    private function create_terms() {
        $categories = ['Limpiadores', 'Tónicos', 'Serums', 'Hidratantes', 'Protector Solar', 'Mascarillas', 'Sets'];
        foreach ( $categories as $cat ) {
            if ( ! term_exists( $cat, 'product_cat' ) ) {
                wp_insert_term( $cat, 'product_cat' );
            }
        }

        $attributes = [
            'Tipo de Piel' => ['Normal', 'Seca', 'Mixta', 'Grasa', 'Sensible'],
            'Preocupación' => ['Acné', 'Manchas', 'Deshidratación', 'Antiedad', 'Poros', 'Rojez'],
            'Ingrediente' => ['Niacinamida', 'Ácido Hialurónico', 'Retinol', 'Vitamina C', 'Centella']
        ];
        // Note: Creating attributes properly requires taxonomy registration which is usually done by Woo.
        // We will skip deep taxonomy creation to avoid errors without Woo active, but keeping structure ready.
    }

    private function create_products() {
        // Create 12 dummy products in Spanish
        for ( $i = 1; $i <= 12; $i++ ) {
            $post_id = wp_insert_post( [
                'post_title'   => 'Producto Demo ' . $i,
                'post_content' => 'Descripción completa del producto demo para skincare. Ingredientes naturales, cruelty free. Ideal para todo tipo de piel.',
                'post_status'  => 'publish',
                'post_type'    => 'product',
            ] );

            if ( $post_id ) {
                update_post_meta( $post_id, '_price', rand(20, 100) );
                update_post_meta( $post_id, '_regular_price', rand(20, 100) );
                update_post_meta( $post_id, '_visibility', 'visible' );
                update_post_meta( $post_id, '_stock_status', 'instock' );
                wp_set_object_terms( $post_id, 'Limpiadores', 'product_cat' );
            }
        }
    }

    private function create_pages() {
        $pages = [
            'Inicio' => ['slug' => 'home', 'template' => 'front-page.php', 'content' => ''],
            'Tienda' => ['slug' => 'tienda', 'template' => '', 'content' => ''], // Shop
            'Carrito' => ['slug' => 'carrito', 'template' => '', 'content' => '[woocommerce_cart]'],
            'Finalizar Compra' => ['slug' => 'finalizar-compra', 'template' => '', 'content' => '[woocommerce_checkout]'],
            'Mi Cuenta' => ['slug' => 'mi-cuenta', 'template' => '', 'content' => '[woocommerce_my_account]'],
            'Blog' => ['slug' => 'blog', 'template' => '', 'content' => ''],
            'Sobre Nosotros' => ['slug' => 'sobre-nosotros', 'template' => 'page.php', 'content' => 'Contenido de Nosotros'],
            'Contacto' => ['slug' => 'contacto', 'template' => 'page.php', 'content' => 'Contenido de Contacto'],
            'FAQ' => ['slug' => 'faq', 'template' => 'page.php', 'content' => 'Preguntas Frecuentes'],
            'Envíos y Devoluciones' => ['slug' => 'envios-y-devoluciones', 'template' => 'page.php', 'content' => 'Política de envíos'],
            'Política de Privacidad' => ['slug' => 'politica-de-privacidad', 'template' => 'page.php', 'content' => 'Privacidad'],
            'Términos y Condiciones' => ['slug' => 'terminos-y-condiciones', 'template' => 'page.php', 'content' => 'Términos'],
        ];

        foreach ( $pages as $title => $args ) {
            $page_check = get_page_by_title( $title );
            if ( ! $page_check ) {
                $pid = wp_insert_post( [
                    'post_title'    => $title,
                    'post_name'     => $args['slug'],
                    'post_content'  => $args['content'],
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'page_template' => $args['template']
                ] );

                // Woo Assignments
                if($args['slug'] === 'tienda') update_option( 'woocommerce_shop_page_id', $pid );
                if($args['slug'] === 'carrito') update_option( 'woocommerce_cart_page_id', $pid );
                if($args['slug'] === 'finalizar-compra') update_option( 'woocommerce_checkout_page_id', $pid );
                if($args['slug'] === 'mi-cuenta') update_option( 'woocommerce_myaccount_page_id', $pid );
                if($args['slug'] === 'terminos-y-condiciones') update_option( 'woocommerce_terms_page_id', $pid );

                // Front page
                if($args['slug'] === 'home') {
                    update_option( 'show_on_front', 'page' );
                    update_option( 'page_on_front', $pid );
                }
                // Blog page
                if($args['slug'] === 'blog') {
                    update_option( 'page_for_posts', $pid );
                }
            }
        }
    }

    private function create_menus() {
        // Primary Menu
        $menu_name = 'Menú Principal';
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        if ( ! $menu_exists ) {
            $menu_id = wp_create_nav_menu( $menu_name );

            // Add Items
            $items = [
                'Tienda' => '/tienda/',
                'Categorías' => '#',
                'Blog' => '/blog/',
                'Sobre Nosotros' => '/sobre-nosotros/',
                'Contacto' => '/contacto/'
            ];

            foreach($items as $label => $url) {
                wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title' => $label,
                    'menu-item-url' => $url,
                    'menu-item-status' => 'publish'
                ]);
            }

            // Assign to location
            $locations = get_theme_mod( 'nav_menu_locations' );
            $locations['primary'] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }

        // Footer Menu
        $footer_menu_name = 'Menú Legal';
        if ( ! wp_get_nav_menu_object( $footer_menu_name ) ) {
             $f_menu_id = wp_create_nav_menu( $footer_menu_name );
             wp_update_nav_menu_item( $f_menu_id, 0, [
                'menu-item-title' => 'Política de Privacidad',
                'menu-item-url' => '/politica-de-privacidad/',
                'menu-item-status' => 'publish'
             ]);
             wp_update_nav_menu_item( $f_menu_id, 0, [
                'menu-item-title' => 'Términos',
                'menu-item-url' => '/terminos-y-condiciones/',
                'menu-item-status' => 'publish'
             ]);

             $locations = get_theme_mod( 'nav_menu_locations' );
             $locations['footer'] = $f_menu_id;
             set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

    private function create_posts() {
         $titles = ['Rutina Básica de Skincare', 'Guía de Protector Solar', 'Ingredientes Clave 2024'];
         foreach ( $titles as $title ) {
            if(!get_page_by_title($title, OBJECT, 'post')) {
                 wp_insert_post( [
                    'post_title'   => $title,
                    'post_content' => 'Contenido demo del blog en español. Consejos sobre cuidado de la piel.',
                    'post_status'  => 'publish',
                    'post_type'    => 'post',
                ] );
            }
        }
    }
}

// Instantiate and run
$seeder = new Skincare_Seeder();
$seeder->run();
