<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Skincare_Seeder {

    public function run() {
        // Force run even if seeded before to update structure
        // if ( get_option( 'skincare_content_seeded' ) ) return;

        // Ensure Spanish Language Settings
        update_option( 'WPLANG', 'es_ES' );

        // Permalink Structure
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );

        $this->create_categories();
        $this->create_attributes(); // This might fail if tax not registered, but we prepare data
        $this->create_products();
        $this->create_pages();
        $this->create_blog_posts();

        // Mark as seeded
        update_option( 'skincare_content_seeded', true );
        $wp_rewrite->flush_rules();
    }

    private function create_categories() {
        $categories = [
            'Limpiadores',
            'Tónicos',
            'Sérums',
            'Hidratantes',
            'Protector solar',
            'Mascarillas',
            'Exfoliantes',
            'Contorno de ojos',
            'Sets regalo',
            'Limpieza corporal',
            'Maquillaje',
            'Cabello y Cuerpo',
            'Fragancias',
            'Lifestyle'
        ];

        foreach ( $categories as $cat ) {
            if ( ! term_exists( $cat, 'product_cat' ) ) {
                wp_insert_term( $cat, 'product_cat' );
            }
        }
    }

    private function create_attributes() {
        // In a real scenario, we would register attributes via wc_create_attribute first.
        // Since this runs in a theme context where Woo might not be fully active or configurable via code easily without admin,
        // we will simulate them as custom taxonomies or skip complex attribute creation.
        // For demo purposes, we will rely on categories or just simple tags if attributes fail.
    }

    private function create_products() {
        // List of 20 products based on brief
        $demo_products = [
            ['Anua Heartleaf 77% Soothing Toner (250ml)', 'Tónicos', 22.00],
            ['COSRX Advanced Snail 96 Mucin Power Essence', 'Sérums', 24.00],
            ['Beauty of Joseon Relief Sun: Rice + Probiotics', 'Protector solar', 18.00],
            ['Some By Mi AHA BHA PHA 30 Days Miracle Toner', 'Tónicos', 20.00],
            ['Laneige Lip Sleeping Mask Berry', 'Mascarillas', 19.00],
            ['Innisfree Green Tea Seed Hyaluronic Serum', 'Sérums', 27.00],
            ['Dr.Jart+ Cicapair Tiger Grass Color Correcting Treatment', 'Hidratantes', 40.00],
            ['Klairs Freshly Juiced Vitamin Drop', 'Sérums', 23.00],
            ['Banila Co Clean It Zero Cleansing Balm Original', 'Limpiadores', 18.00],
            ['Etude House SoonJung 2x Barrier Intensive Cream', 'Hidratantes', 15.00],
            ['Missha Time Revolution The First Essence 5x', 'Esencias', 35.00],
            ['Sulwhasoo First Care Activating Serum', 'Sérums', 89.00],
            ['Round Lab 1025 Dokdo Toner', 'Tónicos', 17.00],
            ['Skin1004 Madagascar Centella Ampoule', 'Sérums', 19.00],
            ['Isntree Hyaluronic Acid Watery Sun Gel', 'Protector solar', 21.00],
            ['Tirtir Mask Fit Red Cushion', 'Maquillaje', 25.00],
            ['Rom&nd Juicy Lasting Tint', 'Maquillaje', 13.00],
            ['Mediheal Tea Tree Care Solution Essential Mask', 'Mascarillas', 3.00],
            ['Aestura Atobarrier 365 Cream', 'Hidratantes', 28.00],
            ['Hair & Body Spa Set Deluxe', 'Sets regalo', 55.00]
        ];

        foreach ( $demo_products as $prod ) {
            $title = $prod[0];
            $cat = $prod[1];
            $price = $prod[2];

            if ( ! get_page_by_title( $title, OBJECT, 'product' ) ) {
                $post_id = wp_insert_post( [
                    'post_title'   => $title,
                    'post_content' => 'Descripción detallada del producto ' . $title . '. Formulado con ingredientes de alta calidad para mejorar tu piel.',
                    'post_excerpt' => 'Breve descripción del ' . $title,
                    'post_status'  => 'publish',
                    'post_type'    => 'product',
                ] );

                if ( $post_id ) {
                    update_post_meta( $post_id, '_price', $price );
                    update_post_meta( $post_id, '_regular_price', $price );
                    update_post_meta( $post_id, '_visibility', 'visible' );
                    update_post_meta( $post_id, '_stock_status', 'instock' );

                    // Assign category
                    $term = term_exists( $cat, 'product_cat' );
                    if ( $term ) {
                        wp_set_object_terms( $post_id, (int)$term['term_id'], 'product_cat' );
                    }

                    // Add dummy attributes (conceptual)
                    update_post_meta( $post_id, '_product_attributes', array() );
                }
            }
        }
    }

    private function create_pages() {
        $pages = [
            'Inicio' => ['slug' => 'home', 'template' => 'front-page.php', 'content' => ''],
            'Tienda' => ['slug' => 'tienda', 'template' => '', 'content' => ''],
            'Marcas' => ['slug' => 'brands', 'template' => '', 'content' => 'Listado de Marcas A-Z'],
            'Carrito' => ['slug' => 'carrito', 'template' => '', 'content' => '[woocommerce_cart]'],
            'Finalizar compra' => ['slug' => 'finalizar-compra', 'template' => '', 'content' => '[woocommerce_checkout]'],
            'Mi cuenta' => ['slug' => 'mi-cuenta', 'template' => '', 'content' => '[woocommerce_my_account]'],
            'Lista de Deseos' => ['slug' => 'wishlist', 'template' => '', 'content' => '[yith_wcwl_wishlist]'],
            'Blog' => ['slug' => 'blog', 'template' => '', 'content' => ''],
            'Contacto' => ['slug' => 'contacto', 'template' => 'page.php', 'content' => '[elementor-template id="XXXX"]'],
            'Ayuda / FAQ' => ['slug' => 'faq', 'template' => 'page.php', 'content' => 'Preguntas Frecuentes'],
            'Envíos y devoluciones' => ['slug' => 'envios-y-devoluciones', 'template' => 'page.php', 'content' => 'Política de envíos...'],
            'Política de privacidad' => ['slug' => 'politica-de-privacidad', 'template' => 'page.php', 'content' => 'Texto legal...'],
            'Términos y condiciones' => ['slug' => 'terminos-y-condiciones', 'template' => 'page.php', 'content' => 'Términos...'],
            'Store Locator' => ['slug' => 'store-locator', 'template' => 'page.php', 'content' => 'Mapa de tiendas'],
            'Programa de Fidelidad' => ['slug' => 'loyalty', 'template' => 'page.php', 'content' => 'Únete al club...'],
            'Subscription Box' => ['slug' => 'subscription-box', 'template' => 'page.php', 'content' => 'Suscríbete a nuestra caja mensual'],
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

                if($args['slug'] === 'tienda') update_option( 'woocommerce_shop_page_id', $pid );
                if($args['slug'] === 'carrito') update_option( 'woocommerce_cart_page_id', $pid );
                if($args['slug'] === 'finalizar-compra') update_option( 'woocommerce_checkout_page_id', $pid );
                if($args['slug'] === 'mi-cuenta') update_option( 'woocommerce_myaccount_page_id', $pid );
                if($args['slug'] === 'terminos-y-condiciones') update_option( 'woocommerce_terms_page_id', $pid );

                if($args['slug'] === 'home') {
                    update_option( 'show_on_front', 'page' );
                    update_option( 'page_on_front', $pid );
                }
                if($args['slug'] === 'blog') {
                    update_option( 'page_for_posts', $pid );
                }
            }
        }
    }

    private function create_blog_posts() {
        $posts = [
            'La rutina coreana de 10 pasos explicada',
            'Top 5 sérums con vitamina C',
            '¿Por qué debes usar protección solar todo el año?',
            'Ingredientes K‑beauty: Centella asiática y sus beneficios',
            'Errores comunes en el cuidado de la piel',
            'Comparativa de tónicos hidratantes'
        ];

        foreach ( $posts as $title ) {
            if(!get_page_by_title($title, OBJECT, 'post')) {
                 wp_insert_post( [
                    'post_title'   => $title,
                    'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'post_status'  => 'publish',
                    'post_type'    => 'post',
                ] );
            }
        }
    }
}

$seeder = new Skincare_Seeder();
$seeder->run();
