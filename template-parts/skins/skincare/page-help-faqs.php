<?php
/**
 * Template Name: Help & FAQs
 * Skin: Skincare
 */

get_header();
?>

<div class="homad-skin-page page-help">
    <div class="container narrow">
        <h1 class="page-title text-center">Help & FAQs</h1>

        <div class="faq-accordion">
            <details class="faq-item">
                <summary>Where do you ship?</summary>
                <div class="faq-content">
                    <p>We ship worldwide including USA, UK, Canada, Australia, and Europe.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>How long does delivery take?</summary>
                <div class="faq-content">
                    <p>Standard shipping takes 5-7 business days. Express shipping is 2-3 days via DHL.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Are your products authentic?</summary>
                <div class="faq-content">
                    <p>Yes, 100%. We source directly from the brands in Korea.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>What is your return policy?</summary>
                <div class="faq-content">
                    <p>We accept returns within 30 days of purchase for unopened items.</p>
                </div>
            </details>
        </div>

        <div class="contact-prompt text-center mt-5">
            <p>Still have questions?</p>
            <a href="/contact-us" class="button">Contact Us</a>
        </div>
    </div>
</div>

<style>
.page-help { padding: 40px 20px; }
.faq-item {
    border-bottom: 1px solid var(--skin-color-border);
    padding: 20px 0;
}
.faq-item summary {
    font-weight: 700;
    cursor: pointer;
    list-style: none; /* Hide default triangle */
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.faq-item summary::after {
    content: '+';
    font-size: 20px;
}
.faq-item[open] summary::after {
    content: '-';
}
.faq-content {
    padding-top: 10px;
    color: var(--skin-color-text-light);
}
</style>

<?php get_footer(); ?>
