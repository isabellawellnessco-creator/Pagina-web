<?php
/**
 * Shortcodes for Elementor/Projects Hub.
 * Renders CPT Grids and the Complex Quote Wizard.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

/**
 * [homad_services_grid]
 * Renders a grid of Service Cards.
 */
function homad_shortcode_services_grid($atts) {
    ob_start();
    $atts = shortcode_atts(
        [
            'limit' => 12,
        ],
        $atts
    );
    $limit = max(1, absint($atts['limit']));
    $args = array(
        'post_type' => 'service',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'no_found_rows' => true,
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="homad-services-grid">
            <?php while ($query->have_posts()) : $query->the_post();
                $icon = get_post_meta(get_the_ID(), 'service_icon', true) ?: 'dashicons-hammer';
                // Note: User can use ACF or Custom Fields. Fallback to excerpt.
            ?>
                <div class="homad-service-card panel-card">
                    <div class="homad-service-card__icon"><span class="dashicons <?php echo esc_attr($icon); ?>"></span></div>
                    <h3 class="homad-service-card__title"><?php the_title(); ?></h3>
                    <div class="homad-service-card__desc"><?php the_excerpt(); ?></div>
                    <button class="homad-btn homad-btn--outline" onclick="homadOpenQuote('service', '<?php echo esc_js(get_the_title()); ?>')">
                        Request Quote
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>No services found.</p>
    <?php endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('homad_services_grid', 'homad_shortcode_services_grid');

/**
 * [homad_packages_grid]
 * Renders a grid of Packages (Tiers).
 */
function homad_shortcode_packages_grid($atts) {
    ob_start();
    $atts = shortcode_atts(
        [
            'limit' => 12,
        ],
        $atts
    );
    $limit = max(1, absint($atts['limit']));
    $args = array(
        'post_type' => 'package',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'no_found_rows' => true,
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="homad-packages-grid">
            <?php while ($query->have_posts()) : $query->the_post();
                 $price = get_post_meta(get_the_ID(), 'package_price_from', true);
                 $features = get_post_meta(get_the_ID(), 'package_features', true); // Assumes new-line separated or array
            ?>
                <div class="homad-package-card panel-card">
                    <div class="homad-package-card__header">
                        <h3 class="homad-package-card__title"><?php the_title(); ?></h3>
                        <?php if($price): ?><div class="homad-package-card__price">From <?php echo esc_html($price); ?></div><?php endif; ?>
                    </div>
                    <div class="homad-package-card__body">
                        <?php the_content(); ?>
                    </div>
                    <div class="homad-package-card__footer">
                        <button class="homad-btn homad-btn--primary" onclick="homadOpenQuote('package', '<?php echo esc_js(get_the_title()); ?>')">
                            Select Package
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>No packages found.</p>
    <?php endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('homad_packages_grid', 'homad_shortcode_packages_grid');

/**
 * [homad_category_chips]
 * Horizontal scrolling pills for categories.
 */
function homad_shortcode_category_chips($atts) {
    ob_start();
    $terms = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'number' => 8,
        'parent' => 0 // Top level only
    ));

    if (!empty($terms) && !is_wp_error($terms)) : ?>
        <div class="homad-chips-container">
            <?php foreach ($terms as $term) :
                // Placeholder icon logic (can be replaced with ACF field)
                $icon_url = '';
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                if($thumbnail_id) {
                    $image = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
                    $icon_url = $image[0];
                }
            ?>
                <a href="<?php echo esc_url(get_term_link($term)); ?>" class="homad-chip">
                    <?php if($icon_url): ?><img src="<?php echo esc_url($icon_url); ?>" alt=""><?php endif; ?>
                    <?php echo esc_html($term->name); ?>
                </a>
            <?php endforeach; ?>
             <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="homad-chip">See All</a>
        </div>
    <?php endif;
    return ob_get_clean();
}
add_shortcode('homad_category_chips', 'homad_shortcode_category_chips');

/**
 * [homad_process_steps]
 * Timeline 01-04
 */
function homad_shortcode_process_steps() {
    ob_start();
    $steps = array(
        1 => array('title' => 'Brief', 'desc' => 'Tell us your vision.'),
        2 => array('title' => 'Proposal', 'desc' => 'We estimate scope.'),
        3 => array('title' => 'Design', 'desc' => 'We create the plans.'),
        4 => array('title' => 'Build', 'desc' => 'We make it real.'),
    );
    ?>
    <div class="homad-timeline">
        <div class="homad-timeline-steps">
            <?php foreach($steps as $num => $step): ?>
                <div class="homad-step">
                    <div class="homad-step__marker">0<?php echo $num; ?></div>
                    <div class="homad-step__content">
                        <h4><?php echo esc_html($step['title']); ?></h4>
                        <p><?php echo esc_html($step['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('homad_process_steps', 'homad_shortcode_process_steps');

/**
 * [homad_hub_nav]
 * Sticky Navigation for Projects Hub.
 */
function homad_shortcode_hub_nav() {
    ob_start();
    ?>
    <div class="homad-hub-nav-wrapper">
        <ul class="homad-hub-nav">
            <li><a href="#services" class="active">Services</a></li>
            <li><a href="#packages">Packages</a></li>
            <li><a href="#b2b">B2B</a></li>
            <li><a href="#quote">Quote</a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('homad_hub_nav', 'homad_shortcode_hub_nav');

/**
 * [homad_filter_drawer_start] & [homad_filter_drawer_end]
 * Wrappers for Elementor Filters to make them collapsible on Mobile.
 */
function homad_shortcode_filter_drawer_start() {
    ob_start();
    ?>
    <div class="homad-filter-wrapper">
        <button class="homad-filter-toggle-btn" onclick="document.querySelector('.homad-filter-drawer').classList.add('is-open'); document.querySelector('.homad-drawer-overlay').classList.add('is-open');">
            <span class="dashicons dashicons-filter"></span> Filters
        </button>
        <div class="homad-drawer-overlay" onclick="document.querySelector('.homad-filter-drawer').classList.remove('is-open'); this.classList.remove('is-open');"></div>
        <div class="homad-filter-drawer">
            <div class="homad-drawer-header">
                <h3>Filters</h3>
                <button class="homad-close-drawer" onclick="document.querySelector('.homad-filter-drawer').classList.remove('is-open'); document.querySelector('.homad-drawer-overlay').classList.remove('is-open');">&times;</button>
            </div>
    <?php
    return ob_get_clean();
}
add_shortcode('homad_filter_drawer_start', 'homad_shortcode_filter_drawer_start');

function homad_shortcode_filter_drawer_end() {
    return '</div></div>';
}
add_shortcode('homad_filter_drawer_end', 'homad_shortcode_filter_drawer_end');


/**
 * [homad_quote_wizard]
 * Multi-step form. Logic handled by assets/js/quote-wizard.js
 */
function homad_shortcode_quote_wizard() {
    // Enqueue script if not already (safeguard)
    wp_enqueue_script('homad-quote-wizard', get_stylesheet_directory_uri() . '/assets/js/quote-wizard.js', array('jquery'), '1.0', true);
    wp_localize_script('homad-quote-wizard', 'homadWizardVars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('homad_quote_nonce')
    ));

    ob_start();
    ?>
    <div id="homad-quote-wizard" class="homad-wizard panel-card">
        <!-- Progress -->
        <div class="homad-wizard__progress">
            <span class="step active" data-step="1">1. Type</span>
            <span class="step" data-step="2">2. Details</span>
            <span class="step" data-step="3">3. Contact</span>
        </div>

        <div class="homad-wizard__notice" role="status" aria-live="polite"></div>
        <form id="homad-quote-form">
            <!-- STEP 1: Type Selection -->
            <div class="homad-wizard__step active" data-step="1">
                <h3>What are you looking for?</h3>
                <div class="homad-type-selector">
                    <label class="type-card">
                        <input type="radio" name="quote_type" value="services" checked>
                        <span class="icon dashicons dashicons-hammer"></span>
                        <span class="label">Custom Service</span>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="quote_type" value="packages">
                        <span class="icon dashicons dashicons-box"></span>
                        <span class="label">Ready Package</span>
                    </label>
                    <label class="type-card">
                        <input type="radio" name="quote_type" value="b2b">
                        <span class="icon dashicons dashicons-building"></span>
                        <span class="label">B2B / Developer</span>
                    </label>
                </div>
                <div class="homad-wizard__actions">
                    <button type="button" class="homad-btn homad-btn--primary" onclick="homadWizard.nextStep()">Next</button>
                </div>
            </div>

            <!-- STEP 2: Conditional Fields -->
            <div class="homad-wizard__step" data-step="2">
                <h3 id="step-2-title">Project Details</h3>

                <!-- Common -->
                <div class="form-group">
                    <label>City / Location</label>
                    <input type="text" name="location" placeholder="e.g. Lima, Peru" required>
                </div>

                <!-- Service Specific -->
                <div class="conditional-group" data-for="services">
                    <div class="form-group">
                        <label>Service Needed</label>
                        <select name="service_interest">
                            <option value="">Select...</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Interior Design">Interior Design</option>
                            <option value="Construction">Construction</option>
                        </select>
                    </div>
                </div>

                <!-- Package Specific -->
                <div class="conditional-group" data-for="packages">
                     <div class="form-group">
                        <label>Interested Package</label>
                        <select name="package_interest">
                            <option value="">Select...</option>
                            <!-- Populated by JS or static -->
                            <option value="Kitchen Standard">Kitchen Standard</option>
                            <option value="Bath Luxury">Bath Luxury</option>
                        </select>
                    </div>
                </div>

                <!-- B2B Specific -->
                <div class="conditional-group" data-for="b2b">
                    <div class="form-group">
                        <label>Project Scale</label>
                        <select name="project_scale">
                            <option value="Multi-family">Multi-family Building</option>
                            <option value="Office">Office / Retail</option>
                            <option value="Hotel">Hospitality</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Approx Area (m²)</label>
                        <input type="number" name="area_m2">
                    </div>
                </div>

                <div class="homad-wizard__actions">
                    <button type="button" class="homad-btn homad-btn--outline" onclick="homadWizard.prevStep()">Back</button>
                    <button type="button" class="homad-btn homad-btn--primary" onclick="homadWizard.nextStep()">Next</button>
                </div>
            </div>

            <!-- STEP 3: Contact -->
            <div class="homad-wizard__step" data-step="3">
                <h3>Contact Information</h3>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone / WhatsApp</label>
                    <input type="tel" name="phone">
                </div>
                 <div class="homad-wizard__actions">
                    <button type="button" class="homad-btn homad-btn--outline" onclick="homadWizard.prevStep()">Back</button>
                    <button type="button" class="homad-btn homad-btn--primary" onclick="homadWizard.submitForm()">Request Quote</button>
                </div>
            </div>

             <!-- SUCCESS -->
            <div class="homad-wizard__step" data-step="success">
                <div class="success-message">
                    <span class="dashicons dashicons-yes-alt" style="font-size: 40px; color: green;"></span>
                    <h3>Request Received!</h3>
                    <p>We will contact you shortly.</p>
                </div>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('homad_quote_wizard', 'homad_shortcode_quote_wizard');
