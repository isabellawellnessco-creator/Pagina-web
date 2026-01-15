<?php
/**
 * Seeder to create pages based on the Blueprint.
 * Usage: Run once via ?homad_seed=true
 */

function homad_run_seeder() {
    if (!isset($_GET['homad_seed']) || $_GET['homad_seed'] !== 'true') return;
    if (!current_user_can('manage_options')) return;

    // --- Content Definitions from Blueprint ---

    $contact_content = '
    <!-- Contact Form Section -->
    <h2>Contáctanos</h2>
    <p>Estamos aquí para ayudarte. Elige el motivo de tu consulta:</p>
    <ul>
        <li><strong>Soporte Tienda:</strong> Para dudas sobre pedidos existentes.</li>
        <li><strong>Prensa:</strong> Consultas de medios.</li>
        <li><strong>Trabaja con nosotros:</strong> Únete al equipo.</li>
    </ul>
    <!-- Placeholder for Contact Form Shortcode -->
    [contact-form-7 id="1234" title="Contact form 1"]

    <hr>
    <h3>Canales Directos</h3>
    <p>Prioridad a WhatsApp: Canal automático configurado.</p>
    ';

    $claims_content = '
    <h2>Libro de Reclamaciones</h2>
    <p>Conforme a lo establecido en el Código de Protección y Defensa del Consumidor de Perú, ponemos a su disposición este Libro de Reclamaciones Virtual.</p>

    <!-- Placeholder for Claims Form -->
    <div style="border: 1px solid #ccc; padding: 20px; background: #f9f9f9;">
        <p>[Formulario Virtual de Reclamaciones]</p>
        <p>Al enviar este formulario, usted recibirá una copia de su reclamación en el correo electrónico proporcionado.</p>
    </div>
    ';

    $shipping_content = '
    <h2>Política de Envíos</h2>
    <h3>Cobertura</h3>
    <p>Realizamos envíos a todo Lima Metropolitana y Callao. Para provincias, coordinamos el despacho hasta la agencia de transporte de su preferencia.</p>

    <h3>Condiciones de Entrega</h3>
    <ul>
        <li><strong>Subida por escaleras:</strong> El servicio incluye subida hasta el 2do piso por escaleras anchas. A partir del 3er piso, aplica un costo adicional o requiere ascensor de carga.</li>
        <li><strong>Horarios:</strong> Lunes a Sábado de 9:00 am a 6:00 pm.</li>
    </ul>
    ';

    $terms_content = '
    <h2>Términos y Condiciones</h2>
    <p>Bienvenido a Homad. Al realizar una compra o contratar un servicio, usted acepta las siguientes reglas claras para evitar reclamos.</p>

    <h3>1. Productos y Materiales</h3>
    <p>Garantizamos el uso de Tableros de Alta Resistencia (HPL) y tecnología PUR en nuestros muebles. Las imágenes son referenciales.</p>

    <h3>2. Tiempos de Entrega</h3>
    <p>Los tiempos de entrega son estimados y pueden variar según la demanda. Productos en stock: 24-48 horas. Fabricación a medida: 15-20 días hábiles.</p>

    <h3>3. Garantía</h3>
    <p>Ofrecemos 5 años de garantía contra defectos de fabricación. No cubre daños por mal uso o golpes.</p>
    ';

    $bio_content = '
    <!-- NFC Landing Content (Fallback if template fails or for SEO) -->
    <div style="text-align: center;">
        <h1>Homad Bio</h1>
        <p>CEO & Founder | Arquitecto</p>
        <p><a href="/portfolio">Ver Portafolio Rápido 📂</a></p>
        <p><span>Hablar por WhatsApp 💬 (Automático)</span></p>
        <p><a href="/shop">Visitar Tienda Online 🛒</a></p>
    </div>
    ';

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
            'content'  => '',
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
            'content'  => $bio_content,
            'template' => 'page-bio.php'
        ],
        [
            'title'    => 'Contacto',
            'slug'     => 'contacto',
            'content'  => $contact_content,
            'template' => 'default'
        ],
        [
            'title'    => 'Libro de Reclamaciones',
            'slug'     => 'reclamaciones',
            'content'  => $claims_content,
            'template' => 'default'
        ],
        [
            'title'    => 'Política de Envíos',
            'slug'     => 'envios',
            'content'  => $shipping_content,
            'template' => 'default'
        ],
         [
            'title'    => 'Términos y Condiciones',
            'slug'     => 'terminos',
            'content'  => $terms_content,
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
             // Front Page Logic
            if ($p['slug'] === 'home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
             // Shop Page Logic
            if ($p['slug'] === 'shop' && class_exists('WooCommerce')) {
                update_option('woocommerce_shop_page_id', $page_id);
            }

            echo "Created Page: " . $p['title'] . "<br>";
        } else {
             // UPDATE EXISTING CONTENT (Important for this step!)
            $update_args = [
                'ID'           => $page_check->ID,
                'post_content' => $p['content'] // Force update content
            ];
            // Don't overwrite Home/Projects/Shop content if they rely on templates,
            // but for legal pages we want to ensure the text is there.
            if( !in_array($p['slug'], ['home', 'proyectos', 'nosotros', 'shop']) ) {
                wp_update_post($update_args);
                echo "Updated Content: " . $p['title'] . "<br>";
            }

            if ($p['template'] && $p['template'] !== 'default') {
                update_post_meta($page_check->ID, '_wp_page_template', $p['template']);
            }
            echo "Page Exists (Refreshed): " . $p['title'] . "<br>";
        }
    }

    // Categories
    if(taxonomy_exists('product_cat')){
        $cats = ['Cocinas', 'Baños', 'Closets', 'Salas', 'Iluminación'];
        foreach($cats as $cat){
            if(!term_exists($cat, 'product_cat')){
                wp_insert_term($cat, 'product_cat');
            }
        }
    }

    exit('Seeding Refreshed');
}

add_action('init', 'homad_run_seeder');
