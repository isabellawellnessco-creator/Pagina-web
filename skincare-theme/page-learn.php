<?php
/**
 * Learn template.
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
		'title' => 'Aprende sobre skincare coreano',
		'subtitle' => 'Learn',
		'text' => 'Guías, rutinas y consejos inspirados en nuestra biblioteca.',
		'cta' => [
			'label' => 'Ver guías',
			'url' => '/learn/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Recursos clave',
		'intro' => 'Basado en el contenido educativo.',
		'cards' => [
			[
				'title' => 'Rutina de 10 pasos',
				'text' => 'Aprende a construir una rutina completa.',
				'link_label' => 'Leer guía',
				'link_url' => '/learn/',
			],
			[
				'title' => 'Ingredientes estrella',
				'text' => 'Descubre activos clave para tu piel.',
				'link_label' => 'Ver ingredientes',
				'link_url' => '/learn/',
			],
			[
				'title' => 'Consejos expertos',
				'text' => 'Tips prácticos para cada tipo de piel.',
				'link_label' => 'Ver consejos',
				'link_url' => '/learn/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
