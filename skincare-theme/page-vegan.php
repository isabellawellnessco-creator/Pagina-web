<?php
/**
 * Vegan template.
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
		'title' => 'Vegan Beauty',
		'subtitle' => 'Vegan',
		'text' => 'Productos cruelty-free y formulaciones veganas.',
		'cta' => [
			'label' => 'Ver selección',
			'url' => '/vegan/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Favoritos veganos',
		'intro' => 'Inspirado en la página vegan.',
		'cards' => [
			[
				'title' => 'Skincare',
				'text' => 'Limpieza, hidratación y tratamiento sin ingredientes animales.',
				'link_label' => 'Ver skincare',
				'link_url' => '/vegan/',
			],
			[
				'title' => 'Haircare',
				'text' => 'Cuidado capilar con fórmulas veganas.',
				'link_label' => 'Ver haircare',
				'link_url' => '/vegan/',
			],
			[
				'title' => 'Body',
				'text' => 'Cuidado corporal suave y efectivo.',
				'link_label' => 'Ver body',
				'link_url' => '/vegan/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
