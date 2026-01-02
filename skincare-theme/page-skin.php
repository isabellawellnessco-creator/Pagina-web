<?php
/**
 * Skin template.
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
		'title' => 'Skin Cupid en prensa',
		'subtitle' => 'Press',
		'text' => 'Explora nuestras menciones, reseñas y editoriales destacadas.',
		'cta' => [
			'label' => 'Ver artículos',
			'url' => '/blogs/press/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Destacados en medios',
		'intro' => 'Contenido clave inspirado en nuestros artículos y colaboraciones.',
		'cards' => [
			[
				'title' => 'K-Beauty en 2026',
				'text' => 'Tendencias y lanzamientos que están dando que hablar.',
				'link_label' => 'Leer más',
				'link_url' => '/blogs/press/',
			],
			[
				'title' => 'Ingredientes hero',
				'text' => 'Activos coreanos explicados por expertos en skincare.',
				'link_label' => 'Descubrir',
				'link_url' => '/blogs/press/',
			],
			[
				'title' => 'Rutinas de culto',
				'text' => 'Cómo las editoriales recomiendan cuidar tu piel.',
				'link_label' => 'Explorar',
				'link_url' => '/blogs/press/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
