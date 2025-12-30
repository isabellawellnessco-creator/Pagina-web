<?php
/**
 * Template Name: Projects Hub (Service + Packages + Quote)
 * The "All-in-One" conversion page.
 */

get_header(); ?>

<div class="homad-projects-hub">

    <!-- R1: Hero (Unified) -->
    <section class="homad-hero-small">
        <div class="homad-container">
            <h1 class="homad-hero-title">Start Your Project</h1>
            <p class="homad-hero-subtitle">Design, Packages, or Construction. We handle it all.</p>
            <a href="#quote-wizard" class="homad-btn homad-btn--primary">Request Quote</a>
        </div>
    </section>

    <!-- R2: Sticky Tabs -->
    <div class="homad-sticky-tabs" id="project-tabs">
        <div class="homad-container-scroll">
            <a href="#services" class="tab-link active">Services</a>
            <a href="#packages" class="tab-link">Packages</a>
            <a href="#b2b" class="tab-link">B2B</a>
            <a href="#portfolio" class="tab-link">Proof</a>
            <a href="#quote-wizard" class="tab-link highlight">Quote</a>
        </div>
    </div>

    <div class="homad-hub-content">

        <!-- R3: Services -->
        <section id="services" class="homad-hub-section">
            <div class="homad-container">
                <h2 class="section-title">Our Services</h2>
                <div class="homad-services-grid">
                    <?php
                    $services = new WP_Query(['post_type'=>'service', 'posts_per_page'=>-1]);
                    while($services->have_posts()): $services->the_post();
                        $deliv = get_post_meta(get_the_ID(), '_homad_deliverables', true);
                    ?>
                    <div class="homad-service-card">
                        <h3><?php the_title(); ?></h3>
                        <div class="service-body">
                            <?php the_excerpt(); ?>
                            <?php if($deliv): ?>
                                <ul class="check-list">
                                    <?php foreach(explode("\n", $deliv) as $item) echo '<li>'.esc_html($item).'</li>'; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <button class="homad-btn homad-btn--outline small" onclick="HomadApp.prefillQuote('service', '<?php the_title(); ?>')">Quote This</button>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>

        <!-- R4: Packages -->
        <section id="packages" class="homad-hub-section bg-light">
            <div class="homad-container">
                <h2 class="section-title">Ready-Made Packages</h2>
                <div class="homad-packages-grid">
                    <?php
                    $packages = new WP_Query(['post_type'=>'package', 'posts_per_page'=>-1]);
                    while($packages->have_posts()): $packages->the_post();
                        $price = get_post_meta(get_the_ID(), '_homad_pkg_price', true);
                        $includes = get_post_meta(get_the_ID(), '_homad_pkg_includes', true);
                    ?>
                    <div class="homad-package-card">
                        <div class="pkg-header">
                            <h3><?php the_title(); ?></h3>
                            <span class="price"><?php echo esc_html($price); ?></span>
                        </div>
                        <div class="pkg-body">
                            <ul class="check-list">
                                <?php if($includes) foreach(explode("\n", $includes) as $item) echo '<li>'.esc_html($item).'</li>'; ?>
                            </ul>
                        </div>
                        <button class="homad-btn homad-btn--primary full-width" onclick="HomadApp.prefillQuote('package', '<?php the_title(); ?>')">Select Package</button>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>

        <!-- R5: B2B -->
        <section id="b2b" class="homad-hub-section">
            <div class="homad-container">
                <div class="homad-b2b-banner">
                    <div class="b2b-content">
                        <h2>For Developers (B2B)</h2>
                        <p>High-volume execution, standardized quality, and strict SLAs for real estate projects.</p>
                        <button class="homad-btn homad-btn--dark" onclick="HomadApp.prefillQuote('b2b', 'Developer Inquiry')">Contact B2B Team</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- R6: Proof -->
        <section id="portfolio" class="homad-hub-section">
            <div class="homad-container">
                <h2 class="section-title">Recent Work</h2>
                <div class="homad-proof-grid">
                    <?php
                    $proof = new WP_Query(['post_type'=>'portfolio', 'posts_per_page'=>3]);
                    while($proof->have_posts()): $proof->the_post();
                        $loc = get_post_meta(get_the_ID(), '_homad_pf_location', true);
                    ?>
                    <div class="homad-proof-card">
                        <?php the_post_thumbnail('medium_large'); ?>
                        <div class="proof-overlay">
                            <h4><?php the_title(); ?></h4>
                            <span><?php echo esc_html($loc); ?></span>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>

        <!-- R7: Quote Wizard (The Conversion Engine) -->
        <section id="quote-wizard" class="homad-hub-section bg-dark">
            <div class="homad-container">
                <div class="homad-wizard-wrapper">
                    <h2 class="wizard-title">Get your free quote</h2>

                    <form id="homad-lead-form" class="homad-form" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
                        <?php wp_nonce_field('homad_lead_action', 'security'); ?>
                        <input type="hidden" name="action" value="homad_submit_lead">
                        <input type="hidden" name="interest_type" id="input_interest_type" value="general">
                        <input type="hidden" name="interest_name" id="input_interest_name" value="">

                        <div class="form-row">
                            <div class="form-group">
                                <label>What are you looking for?</label>
                                <select name="service_type" class="homad-input" required>
                                    <option value="service">Custom Design / Service</option>
                                    <option value="package">Ready Package</option>
                                    <option value="b2b">B2B / Construction</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estimated Budget (USD)</label>
                                <select name="budget" class="homad-input">
                                    <option value="<5k">Under $5k</option>
                                    <option value="5k-15k">$5k - $15k</option>
                                    <option value="15k-50k">$15k - $50k</option>
                                    <option value="50k+">$50k+</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="homad-input" required placeholder="you@example.com">
                            </div>
                            <div class="form-group">
                                <label>Phone / WhatsApp</label>
                                <input type="tel" name="phone" class="homad-input" placeholder="+51 999...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Project Details (Optional)</label>
                            <textarea name="details" class="homad-input" rows="3" placeholder="Tell us more about your space..."></textarea>
                        </div>

                        <button type="submit" class="homad-btn homad-btn--primary full-width">Submit Request</button>
                        <p class="form-note">We usually reply within 24 hours.</p>
                    </form>
                </div>
            </div>
        </section>

    </div><!-- .homad-hub-content -->

</div>

<?php get_footer(); ?>
