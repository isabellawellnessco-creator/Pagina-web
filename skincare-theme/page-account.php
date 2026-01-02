<?php
/**
 * Account template.
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
		'title' => 'Mi cuenta',
		'subtitle' => 'Account',
		'text' => 'Gestiona tus pedidos, direcciones y puntos.',
		'cta' => [
			'label' => 'Acceder',
			'url' => '/account/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Acciones rápidas',
		'intro' => 'Basado en el panel de cuenta.',
		'cards' => [
			[
				'title' => 'Mis pedidos',
				'text' => 'Revisa el estado de tus compras recientes.',
				'link_label' => 'Ver pedidos',
				'link_url' => '/account/',
			],
			[
				'title' => 'Direcciones',
				'text' => 'Gestiona tu información de envío.',
				'link_label' => 'Actualizar',
				'link_url' => '/account/',
			],
			[
				'title' => 'Mis puntos',
				'text' => 'Consulta y canjea tus puntos de rewards.',
				'link_label' => 'Ver puntos',
				'link_url' => '/rewards/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
