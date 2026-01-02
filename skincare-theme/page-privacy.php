<?php
/**
 * Privacy template.
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
		'title' => 'Política de privacidad',
		'subtitle' => 'Privacy',
		'text' => 'Conoce cómo protegemos y gestionamos tus datos.',
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Resumen de privacidad',
		'intro' => 'Basado en nuestra política oficial.',
		'cards' => [
			[
				'title' => 'Datos recopilados',
				'text' => 'Información necesaria para procesar pedidos.',
				'link_label' => 'Ver detalle',
				'link_url' => '/privacy/',
			],
			[
				'title' => 'Cookies',
				'text' => 'Uso de cookies para mejorar la experiencia.',
				'link_label' => 'Ver detalle',
				'link_url' => '/privacy/',
			],
			[
				'title' => 'Tus derechos',
				'text' => 'Acceso, rectificación y eliminación de datos.',
				'link_label' => 'Ver detalle',
				'link_url' => '/privacy/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
