<?php
/**
 * Template Name: Projects Hub
 *
 * This is the template that displays the "Projects" hub page, consolidating
 * services, packages, and B2B offerings.
 *
 * @package Homad
 */

get_header(); ?>

<main id="main" class="site-main">

    <div class="projects-hub">

        <?php
        // Section 1: Projects Hero
        ?>
        <section class="projects-hero">
            <div class="container">
                <h1><?php the_title(); ?></h1>
                <p><?php esc_html_e( 'Single message + Dominant CTA: "Request Quote"', 'homad' ); ?></p>
            </div>
        </section>

        <?php
        // Section 2: Sticky Tab Selector
        ?>
        <nav class="projects-tabs">
            <div class="container">
                <ul>
                    <li><a href="#services-section"><?php esc_html_e( 'Services', 'homad' ); ?></a></li>
                    <li><a href="#packages-section"><?php esc_html_e( 'Packages', 'homad' ); ?></a></li>
                    <li><a href="#b2b-section"><?php esc_html_e( 'B2B', 'homad' ); ?></a></li>
                    <li><a href="#quote-wizard"><?php esc_html_e( 'Request Quote', 'homad' ); ?></a></li>
                </ul>
            </div>
        </nav>

        <?php
        // Section 3: Services Section
        ?>
        <section id="services-section" class="projects-section">
            <div class="container">
                <h2><?php esc_html_e( 'Services', 'homad' ); ?></h2>
                <div class="services-grid">
                    <?php
                    $args_services = array(
                        'post_type' => 'service',
                        'posts_per_page' => -1, // Display all services
                    );
                    $services_query = new WP_Query( $args_services );
                    if ( $services_query->have_posts() ) :
                        while ( $services_query->have_posts() ) : $services_query->the_post();
                            get_template_part( 'template-parts/service-card' );
                        endwhile;
                    else :
                        echo '<p>' . esc_html__( 'No services found.', 'homad' ) . '</p>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>

        <?php
        // Section 4: Packages Section
        ?>
        <section id="packages-section" class="projects-section">
            <div class="container">
                <h2><?php esc_html_e( 'Packages', 'homad' ); ?></h2>
                <div class="packages-grid">
                    <?php
                    $args_packages = array(
                        'post_type' => 'package',
                        'posts_per_page' => -1, // Display all packages
                    );
                    $packages_query = new WP_Query( $args_packages );
                    if ( $packages_query->have_posts() ) :
                        while ( $packages_query->have_posts() ) : $packages_query->the_post();
                            get_template_part( 'template-parts/package-card' );
                        endwhile;
                    else :
                        echo '<p>' . esc_html__( 'No packages found.', 'homad' ) . '</p>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>

        <?php
        // Section 5: B2B Section
        ?>
        <section id="b2b-section" class="projects-section">
            <div class="container">
                <h2><?php esc_html_e( 'B2B Solutions', 'homad' ); ?></h2>
                <p><?php esc_html_e( 'Benefits, typologies, SLA, volume.', 'homad' ); ?></p>
            </div>
        </section>

        <?php
        // Section 6: Quote Wizard (Form)
        ?>
        <section id="quote-wizard" class="projects-section quote-wizard">
            <div class="container">
                <h2><?php esc_html_e( 'Request a Quote', 'homad' ); ?></h2>
                <form class="quote-form">
                    <div class="form-row">
                        <label for="country-city"><?php esc_html_e( 'Country/City', 'homad' ); ?></label>
                        <input type="text" id="country-city" name="country_city" required>
                    </div>
                    <div class="form-row">
                        <label for="project-type"><?php esc_html_e( 'Project Type', 'homad' ); ?></label>
                        <select id="project-type" name="project_type" required>
                            <option value=""><?php esc_html_e( 'Select type...', 'homad' ); ?></option>
                            <option value="service"><?php esc_html_e( 'Service', 'homad' ); ?></option>
                            <option value="package"><?php esc_html_e( 'Package', 'homad' ); ?></option>
                            <option value="b2b"><?php esc_html_e( 'B2B', 'homad' ); ?></option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="area-sqm"><?php esc_html_e( 'Area (m²)', 'homad' ); ?></label>
                        <input type="number" id="area-sqm" name="area_sqm">
                    </div>
                    <div class="form-row">
                        <label for="budget-range"><?php esc_html_e( 'Budget Range', 'homad' ); ?></label>
                        <input type="text" id="budget-range" name="budget_range">
                    </div>
                    <div class="form-row">
                        <label for="contact-info"><?php esc_html_e( 'Email / WhatsApp', 'homad' ); ?></label>
                        <input type="text" id="contact-info" name="contact_info" required>
                    </div>
                    <div class="form-row">
                        <button type="submit"><?php esc_html_e( 'Submit Request', 'homad' ); ?></button>
                    </div>
                </form>
            </div>
        </section>

    </div><!-- .projects-hub -->

</main><!-- #main -->

<?php get_footer();
