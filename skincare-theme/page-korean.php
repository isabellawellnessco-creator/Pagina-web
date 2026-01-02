<?php
/**
 * Korean Skincare template.
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
		'title' => 'Korean Skincare',
		'subtitle' => 'K-Beauty',
		'text' => 'Explora nuestras colecciones y favoritos coreanos.',
		'cta' => [
			'label' => 'Explorar colecciones',
			'url' => '/korean/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Descubre',
		'intro' => 'Inspirado en la página de Korean skincare.',
		'cards' => [
			[
				'title' => 'Rutinas esenciales',
				'text' => 'Productos para cada paso de tu rutina.',
				'link_label' => 'Ver rutina',
				'link_url' => '/korean/',
			],
			[
				'title' => 'Marcas top',
				'text' => 'Selección curada de marcas coreanas.',
				'link_label' => 'Ver marcas',
				'link_url' => '/korean/',
			],
			[
				'title' => 'Sets',
				'text' => 'Kits listos para regalar o empezar.',
				'link_label' => 'Ver sets',
				'link_url' => '/korean/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
