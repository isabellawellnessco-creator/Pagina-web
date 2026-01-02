<?php
/**
 * Store Locator template.
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
		'title' => 'Store Locator',
		'subtitle' => 'Locations',
		'text' => 'Encuentra nuestras tiendas y puntos de retiro.',
		'cta' => [
			'label' => 'Ver mapa',
			'url' => '/store-locator/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Tiendas destacadas',
		'intro' => 'Basado en nuestra sección de ubicaciones.',
		'cards' => [
			[
				'title' => 'Londres',
				'text' => 'Pop-up y eventos exclusivos.',
				'link_label' => 'Ver horarios',
				'link_url' => '/store-locator/',
			],
			[
				'title' => 'Manchester',
				'text' => 'Punto de pickup y asesoría.',
				'link_label' => 'Ver horarios',
				'link_url' => '/store-locator/',
			],
			[
				'title' => 'Online',
				'text' => 'Compra 24/7 con envíos rápidos.',
				'link_label' => 'Ir a la tienda',
				'link_url' => '/tienda/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
