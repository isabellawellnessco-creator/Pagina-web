<?php
/**
 * The Template for displaying all single products
 *
 * Handles Desktop 2-Col and Mobile Bottom Sheet logic.
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="homad-pdp-container">

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="homad-pdp-grid">

            <!-- Column 1: Gallery (Sticky on Desktop) -->
            <div class="pdp-gallery">
                <?php
                /**
                 * woocommerce_before_single_product_summary hook.
                 *
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>

            <!-- Column 2: Info & Buy Box -->
            <div class="pdp-info">

                <!-- Desktop Title/Price (Hidden on Mobile if using Bottom Sheet style) -->
                <div class="pdp-header">
                    <h1 class="product_title entry-title"><?php the_title(); ?></h1>
                    <div class="product-gancho">
                        <?php echo get_post_meta(get_the_ID(), '_homad_gancho', true) ?: "La cocina de un chef, en tu propio hogar."; ?>
                    </div>
                    <p class="price"><?php echo $product->get_price_html(); ?></p>
                </div>

                <!-- Buy Box / Mobile Bottom Sheet Wrapper -->
                <div class="homad-buy-box">

                    <!-- Stock/ETA -->
                    <div class="stock-eta">
                        ⚡ Instalación Flash: Disponible en 5 días.
                    </div>

                    <!-- Add to Cart Form (Variations) -->
                    <?php woocommerce_template_single_add_to_cart(); ?>

                    <!-- Trust Strip -->
                    <div class="trust-strip">
                        <span>🛡️ Garantía Total 5 Años</span>
                        <span>💎 Resistente a Rayaduras</span>
                    </div>

                </div>

                <!-- Tabs (Persuasion) -->
                <div class="homad-pdp-tabs">
                    <details open>
                        <summary>La Experiencia</summary>
                        <p>Cajones que cierran en silencio y superficies que no se manchan con vino o café. Diseñada para ser el corazón de tu casa.</p>
                    </details>
                    <details>
                        <summary>El Material (NO Melamina)</summary>
                        <p>Usamos Tableros de Alta Resistencia (HPL). Se ven como madera natural, pero soportan el agua, el calor y el uso diario. Cantos sellados con tecnología PUR.</p>
                    </details>
                     <details>
                        <summary>Garantía</summary>
                        <p>Herrajes alemanes de cierre suave. 5 años de cobertura.</p>
                    </details>
                </div>

            </div>

        </div>

        <!-- Cross Sell -->
        <section class="homad-cross-sell">
            <h3>Completa el Look</h3>
             <?php woocommerce_output_related_products(); ?>
        </section>

    <?php endwhile; // end of the loop. ?>

</div>

<?php
get_footer( 'shop' );

/* OMITTED: Standard WooCommerce Hooks to prevent double rendering. We are building a custom layout. */
