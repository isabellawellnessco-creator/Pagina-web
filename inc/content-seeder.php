<?php
/**
 * Content Seeder
 * Automatically populates default Services and Legal Pages if they don't exist.
 * Context: Peru, Homad Brand.
 */

function homad_seed_content() {
    // 1. Seed Services
    $services = [
        'Diseño de Arquitectura' => 'Desarrollamos proyectos arquitectónicos integrales, fusionando estética y funcionalidad para crear espacios habitables únicos. Desde la conceptualización hasta los planos ejecutivos.',
        'Interiorismo' => 'Transformamos ambientes mediante una selección curada de mobiliario, iluminación y materiales. Nuestro enfoque busca el equilibrio perfecto entre estilo y confort.',
        'Gestión de Construcción' => 'Supervisión y ejecución de obra con altos estándares de calidad. Nos encargamos de la coordinación de gremios, control de presupuestos y plazos de entrega.',
        'Factibilidad de Proyectos' => 'Análisis técnico y económico para asegurar la viabilidad de su inversión. Evaluamos normativas, costos y potencial comercial antes de iniciar cualquier obra.'
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

    // 2. Seed Legal Pages
    $pages = [
        'Política de Envíos' => '<!-- wp:paragraph -->
<p>En <strong>Homad</strong>, nos esforzamos por llevar el diseño a cada rincón del Perú. A continuación detallamos nuestras políticas de despacho:</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Cobertura y Plazos</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Realizamos envíos a nivel nacional. Los tiempos de entrega estimados son de <strong>3 a 7 días hábiles para Lima Metropolitana</strong> y de <strong>7 a 15 días hábiles para provincias</strong>, contados a partir de la confirmación del pago.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Condiciones de Entrega</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>La entrega se realiza en la dirección indicada por el cliente. Es responsabilidad del usuario asegurar que haya alguien mayor de edad para recibir el producto. En caso de edificios o condominios, el despacho se realiza hasta la recepción o portería, salvo que se cuente con ascensor de carga habilitado.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Responsabilidad</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Homad trabaja con operadores logísticos de confianza. Sin embargo, no nos responsabilizamos por demoras ocasionadas por fuerza mayor o contingencias del operador logístico, aunque nos comprometemos a gestionar cualquier incidencia para solucionar el problema a la brevedad.</p>
<!-- /wp:paragraph -->',

        'Políticas de Devolución' => '<!-- wp:paragraph -->
<p>Queremos que esté completamente satisfecho con su compra en <strong>Homad</strong>. Si por alguna razón necesita realizar un cambio o devolución, por favor revise las siguientes condiciones:</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Plazo</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Aceptamos solicitudes de cambio o devolución dentro de los primeros <strong>7 días calendario</strong> posteriores a la recepción del producto.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Requisitos Indispensables</h3>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
<li>El producto debe estar <strong>nuevo, sin uso y en su empaque original</strong>.</li>
<li>Debe contar con todos sus accesorios, manuales y etiquetas.</li>
<li>Presentar el comprobante de pago (boleta o factura).</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Excepciones</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No se aceptan devoluciones de productos fabricados a pedido (personalizados), productos en liquidación, o aquellos que hayan sido manipulados incorrectamente por el cliente. Homad se reserva el derecho de rechazar devoluciones que no cumplan con estos estándares.</p>
<!-- /wp:paragraph -->',

        'Privacidad y Datos' => '<!-- wp:paragraph -->
<p>En <strong>Homad</strong>, tomamos muy en serio la seguridad de su información.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Uso de Información</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Los datos personales solicitados (nombre, teléfono, correo electrónico, dirección) son utilizados estrictamente para procesar sus pedidos, gestionar envíos y comunicarnos con usted sobre el estado de su compra.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Seguridad</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No compartimos ni vendemos su información a terceros con fines comerciales. Implementamos medidas de seguridad digital para proteger sus datos contra accesos no autorizados.</p>
<!-- /wp:paragraph -->',

        'Términos y Condiciones' => '<!-- wp:paragraph -->
<p>Bienvenido a Homad. Al navegar y comprar en este sitio web, usted acepta los siguientes términos y condiciones regidos por la legislación de la República del Perú.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Propiedad Intelectual</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Todo el contenido de este sitio (imágenes, textos, logotipos, diseños) es propiedad exclusiva de Homad o de sus respectivos titulares y está protegido por las leyes de derechos de autor.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Precios y Stock</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Los precios están expresados en Soles (PEN) e incluyen IGV. Homad se reserva el derecho de modificar precios y stock sin previo aviso. En caso de un error tipográfico en el precio, nos pondremos en contacto con el cliente antes de procesar el pedido.</p>
<!-- /wp:paragraph -->'
    ];

    foreach ($pages as $title => $content) {
        if (!get_page_by_title($title)) {
            wp_insert_post([
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
        }
    }
}

// Hook to admin_init so it runs once when admin visits dashboard, then we can remove it or check existence.
// Using admin_init to ensure it happens when the user logs in to check.
add_action('admin_init', 'homad_seed_content');
