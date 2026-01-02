<?php
/**
 * FAQs template.
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
		'title' => 'Ayuda y FAQs',
		'subtitle' => 'FAQs',
		'text' => 'Resolvemos las preguntas más comunes sobre pedidos, envíos y productos.',
		'cta' => [
			'label' => 'Contactar soporte',
			'url' => '/contact/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/faq', null, [
		'title' => 'Preguntas frecuentes',
		'faqs' => [
			[
				'question' => '¿Cuánto tarda el envío estándar?',
				'answer' => 'El envío estándar tarda entre 2 y 4 días laborables en Reino Unido.',
			],
			[
				'question' => '¿Puedo devolver un producto?',
				'answer' => 'Aceptamos devoluciones dentro de los 14 días posteriores a la entrega.',
			],
			[
				'question' => '¿Cómo uso mis puntos de rewards?',
				'answer' => 'Inicia sesión en tu cuenta y canjea puntos en el catálogo de recompensas.',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
