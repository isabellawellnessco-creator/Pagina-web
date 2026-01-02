<?php
/**
 * Login template.
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
		'title' => 'Iniciar sesión',
		'subtitle' => 'Login',
		'text' => 'Accede a tu cuenta para ver pedidos y recompensas.',
		'cta' => [
			'label' => 'Crear cuenta',
			'url' => '/account/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Beneficios de tu cuenta',
		'intro' => 'Inspirado en el área de acceso.',
		'cards' => [
			[
				'title' => 'Seguimiento',
				'text' => 'Controla cada entrega desde tu panel.',
				'link_label' => 'Ver pedidos',
				'link_url' => '/account/',
			],
			[
				'title' => 'Puntos',
				'text' => 'Acumula puntos y desbloquea beneficios.',
				'link_label' => 'Ver rewards',
				'link_url' => '/rewards/',
			],
			[
				'title' => 'Wishlist',
				'text' => 'Guarda tus favoritos para futuras compras.',
				'link_label' => 'Ver wishlist',
				'link_url' => '/wishlist/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
