<?php
/**
 * Care template.
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
		'title' => 'Careers en Skin Cupid',
		'subtitle' => 'Care',
		'text' => 'Únete a nuestro equipo y construye el futuro del K-Beauty.',
		'cta' => [
			'label' => 'Ver vacantes',
			'url' => '/careers/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Áreas abiertas',
		'intro' => 'Oportunidades inspiradas en nuestro portal de carreras.',
		'cards' => [
			[
				'title' => 'Customer Care',
				'text' => 'Acompaña a nuestra comunidad con soporte premium.',
				'link_label' => 'Aplicar',
				'link_url' => '/careers/',
			],
			[
				'title' => 'Marketing & Brand',
				'text' => 'Cuenta historias que conectan con amantes del skincare.',
				'link_label' => 'Aplicar',
				'link_url' => '/careers/',
			],
			[
				'title' => 'Operaciones',
				'text' => 'Optimiza pedidos, logística y experiencia postventa.',
				'link_label' => 'Aplicar',
				'link_url' => '/careers/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
