<?php
/**
 * Contact template.
 *
 * @package SkincareThemeChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<main id="main" class="site-main">
	<?php
	get_template_part( 'template-parts/sections/hero', null, [
		'title' => 'Contáctanos',
		'subtitle' => 'Contact',
		'text' => 'Estamos listos para ayudarte con pedidos, productos y recomendaciones.',
		'cta' => [
			'label' => 'Abrir chat',
			'url' => '/contact/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Canales de soporte',
		'intro' => 'Información inspirada en nuestro formulario de contacto.',
		'cards' => [
			[
				'title' => 'Email',
				'text' => 'help@skincupid.co.uk',
				'link_label' => 'Escribir',
				'link_url' => 'mailto:help@skincupid.co.uk',
			],
			[
				'title' => 'Pedidos',
				'text' => 'Seguimiento, cambios y devoluciones.',
				'link_label' => 'Ver pedidos',
				'link_url' => '/account/',
			],
			[
				'title' => 'FAQs',
				'text' => 'Respuestas rápidas a dudas frecuentes.',
				'link_label' => 'Ir a FAQs',
				'link_url' => '/faqs/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
