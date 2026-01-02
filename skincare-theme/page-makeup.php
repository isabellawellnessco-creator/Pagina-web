<?php
/**
 * Makeup template.
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
		'title' => 'Makeup',
		'subtitle' => 'Makeup',
		'text' => 'Explora maquillaje coreano con acabado natural.',
		'cta' => [
			'label' => 'Ver colección',
			'url' => '/makeup/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Categorías',
		'intro' => 'Inspirado en la sección de maquillaje.',
		'cards' => [
			[
				'title' => 'Labios',
				'text' => 'Tintes y bálsamos con efecto glossy.',
				'link_label' => 'Ver labios',
				'link_url' => '/makeup/',
			],
			[
				'title' => 'Tez',
				'text' => 'Cushions y bases ligeras.',
				'link_label' => 'Ver tez',
				'link_url' => '/makeup/',
			],
			[
				'title' => 'Ojos',
				'text' => 'Paletas suaves y delineados precisos.',
				'link_label' => 'Ver ojos',
				'link_url' => '/makeup/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
