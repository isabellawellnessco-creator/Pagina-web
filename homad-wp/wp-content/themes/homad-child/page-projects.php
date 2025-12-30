<?php
/**
 * Template Name: Projects Hub (Fluid)
 * Description: One-page hub for Services, Packages, B2B, and Quoting.
 *
 * @package homad-child
 */

get_header();
?>

<div class="homad-projects-hub">

    <!-- Hero Section -->
    <div class="homad-projects-hero">
        <div class="container">
            <h1>Start Your Project</h1>
            <p>Design, Renovation, and Furniture Packages. Remote design globally, execution in Lima.</p>
            <button class="homad-btn homad-btn--primary" onclick="homadScrollToQuote()">Request Quote</button>
        </div>
    </div>

    <!-- Sticky Navigation (JS Tabs) -->
    <div class="homad-sticky-tabs-wrapper">
        <div class="container">
            <nav class="homad-sticky-tabs">
                <button class="tab-btn active" data-target="services">Services</button>
                <button class="tab-btn" data-target="packages">Packages</button>
                <button class="tab-btn" data-target="b2b">B2B</button>
                <button class="tab-btn" data-target="proof">Portfolio</button>
                <button class="tab-btn" data-target="quote">Quote</button>
            </nav>
        </div>
    </div>

    <div class="container homad-hub-content">

        <!-- SERVICES SECTION -->
        <section id="services" class="hub-section active">
            <h2>Our Services</h2>
            <p class="section-intro">Custom architecture and interior design tailored to your needs.</p>
            <?php echo do_shortcode('[homad_services_grid]'); ?>

            <div class="homad-deliverables-accordion">
                <details open>
                    <summary>Standard Deliverables</summary>
                    <p>Floor plans, 3D Renders, Material List, Budget Estimation.</p>
                </details>
            </div>
        </section>

        <!-- PACKAGES SECTION -->
        <section id="packages" class="hub-section">
            <h2>Ready-to-Go Packages</h2>
            <p class="section-intro">Curated solutions for specific rooms. Fast and efficient.</p>
            <?php echo do_shortcode('[homad_packages_grid]'); ?>
        </section>

        <!-- B2B SECTION -->
        <section id="b2b" class="hub-section">
            <h2>For Developers (B2B)</h2>
            <div class="homad-b2b-layout">
                <div class="b2b-info">
                    <h3>Volume & Consistency</h3>
                    <ul>
                        <li>Standardized finishes for multi-family units.</li>
                        <li>Strict SLA adherence.</li>
                        <li>Dedicated account manager.</li>
                    </ul>
                    <button class="homad-btn homad-btn--outline" onclick="homadOpenQuote('b2b', 'Developer Inquiry')">Get B2B Proposal</button>
                </div>
                <div class="b2b-media">
                    <!-- Placeholder for B2B image -->
                    <div class="placeholder-box">B2B Image</div>
                </div>
            </div>
        </section>

        <!-- PROOF SECTION -->
        <section id="proof" class="hub-section">
            <h2>Recent Work</h2>
            <!-- Reuse Service Grid logic or a new Portfolio Grid shortcode -->
            <p>See our latest transformations.</p>
            <div class="homad-proof-grid">
                 <!-- Placeholder content -->
                 <div class="proof-card">Project A</div>
                 <div class="proof-card">Project B</div>
                 <div class="proof-card">Project C</div>
            </div>
        </section>

        <!-- QUOTE SECTION -->
        <section id="quote" class="hub-section">
            <h2>Request a Quote</h2>
            <?php echo do_shortcode('[homad_quote_wizard]'); ?>
        </section>

    </div><!-- .container -->

</div>

<?php
// Enqueue the specific JS for this page
wp_enqueue_script('homad-projects-js', get_stylesheet_directory_uri() . '/assets/js/homad-projects.js', array('jquery'), '1.0', true);

get_footer();
?>
