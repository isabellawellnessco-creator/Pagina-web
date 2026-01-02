<?php
/**
 * Rewards template.
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
		'title' => 'Programa de Rewards',
		'subtitle' => 'Rewards',
		'text' => 'Gana puntos con cada compra y canjéalos por premios exclusivos.',
		'cta' => [
			'label' => 'Ver mi saldo',
			'url' => '/account/',
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/card-grid', null, [
		'title' => 'Cómo funciona',
		'intro' => 'Inspirado en el catálogo de recompensas.',
		'cards' => [
			[
				'title' => 'Gana puntos',
				'text' => 'Acumula puntos por compras, reseñas y referidos.',
				'link_label' => 'Ver acciones',
				'link_url' => '/rewards/',
			],
			[
				'title' => 'Canjea premios',
				'text' => 'Descuentos, regalos y acceso anticipado.',
				'link_label' => 'Ver catálogo',
				'link_url' => '/rewards/',
			],
			[
				'title' => 'Niveles',
				'text' => 'Sube de nivel y desbloquea beneficios adicionales.',
				'link_label' => 'Conocer niveles',
				'link_url' => '/rewards/',
			],
		],
	] );
	?>
	<?php
	get_template_part( 'template-parts/sections/faq', null, [
		'title' => 'FAQs de Rewards',
		'faqs' => [
			[
				'question' => '¿Cómo acumulo puntos?',
				'answer' => 'Ganas puntos por cada compra y acciones adicionales.',
			],
			[
				'question' => '¿Los puntos caducan?',
				'answer' => 'Los puntos tienen una validez de 12 meses desde su obtención.',
			],
			[
				'question' => '¿Puedo combinar recompensas?',
				'answer' => 'Puedes canjear una recompensa por pedido.',
			],
		],
	] );
	?>

</main>

<?php
get_footer();
