<?php
/**
 * Wishlist template.
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
		'title' => 'Wishlist',
		'subtitle' => 'Wishlist',
		'text' => 'Guarda tus productos favoritos para comprarlos después.',
		'cta' => [
			'label' => 'Ir a tienda',
			'url' => '/tienda/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Cómo funciona',
		'intro' => 'Inspirado en tu lista de deseos.',
		'cards' => [
			[
				'title' => 'Guardar',
				'text' => 'Añade productos con un clic.',
				'link_label' => 'Explorar productos',
				'link_url' => '/tienda/',
			],
			[
				'title' => 'Comparte',
				'text' => 'Comparte tu wishlist con amigos.',
				'link_label' => 'Compartir',
				'link_url' => '/wishlist/',
			],
			[
				'title' => 'Alertas',
				'text' => 'Recibe notificaciones de stock.',
				'link_label' => 'Configurar',
				'link_url' => '/wishlist/',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
