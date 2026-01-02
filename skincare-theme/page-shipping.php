<?php
/**
 * Shipping template.
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
		'title' => 'Envíos y devoluciones',
		'subtitle' => 'Shipping',
		'text' => 'Detalles de entrega, costos y política de devoluciones.',
		'cta' => [
			'label' => 'Contactar soporte',
			'url' => '/contact/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Opciones de envío',
		'intro' => 'Basado en nuestra tabla de servicios.',
		'cards' => [
			[
				'title' => 'Estándar',
				'text' => 'Entrega en 2-4 días laborables.',
				'link_label' => 'Ver detalles',
				'link_url' => '/shipping/',
			],
			[
				'title' => 'Express',
				'text' => 'Entrega en 1-2 días laborables.',
				'link_label' => 'Ver detalles',
				'link_url' => '/shipping/',
			],
			[
				'title' => 'Internacional',
				'text' => 'Entrega en 5-10 días laborables.',
				'link_label' => 'Ver detalles',
				'link_url' => '/shipping/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
