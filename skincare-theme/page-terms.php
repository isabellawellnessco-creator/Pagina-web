<?php
/**
 * Terms template.
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
		'title' => 'Términos y condiciones',
		'subtitle' => 'Terms',
		'text' => 'Condiciones de uso, compras y servicios de Skin Cupid.',
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Puntos clave',
		'intro' => 'Resumen basado en nuestra página legal.',
		'cards' => [
			[
				'title' => 'Pedidos y pagos',
				'text' => 'Detalles de compra y confirmación de pedidos.',
				'link_label' => 'Leer sección',
				'link_url' => '/terms/',
			],
			[
				'title' => 'Uso del sitio',
				'text' => 'Buenas prácticas y políticas de uso.',
				'link_label' => 'Leer sección',
				'link_url' => '/terms/',
			],
			[
				'title' => 'Responsabilidad',
				'text' => 'Información sobre limitaciones y garantías.',
				'link_label' => 'Leer sección',
				'link_url' => '/terms/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
