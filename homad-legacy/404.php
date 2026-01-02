<?php
/**
 * The template for displaying the 404 template in the Woodmart theme.
 */

get_header(); ?>

<div class="container">
	<div class="row">
		<div class="site-content col-lg-12 col-12 col-md-12" role="main">

            <div class="homad-404-content" style="text-align: center; padding: 100px 0;">
                <h1 style="font-size: 4rem;">Plano no encontrado. 📐</h1>
                <p style="font-size: 1.5rem; margin: 20px 0;">Parece que la página que buscas está en construcción o no existe.</p>
                <a href="<?php echo home_url('/'); ?>" class="btn btn-primary btn-lg">Volver al Inicio</a>
            </div>

		</div>
	</div>
</div>

<?php get_footer(); ?>
