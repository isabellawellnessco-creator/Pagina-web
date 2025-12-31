<?php
/**
 * Template Name: Contact Us
 * Skin: Skincare
 */

get_header();
?>

<div class="homad-skin-page page-contact">
    <div class="container narrow">
        <h1 class="page-title text-center">Contact Us</h1>
        <p class="text-center subtitle">We'd love to hear from you.</p>

        <form class="homad-contact-form skin-form">
            <div class="form-group">
                <label>Name</label>
                <input type="text" placeholder="Your Name" />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="your@email.com" />
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea rows="5" placeholder="How can we help?"></textarea>
            </div>
            <button type="submit" class="button">Send Message</button>
        </form>
    </div>
</div>

<style>
.page-contact { padding: 40px 20px; }
.skin-form {
    max-width: 500px;
    margin: 40px auto;
}
.skin-form .form-group {
    margin-bottom: 20px;
}
.skin-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
}
.skin-form input, .skin-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--skin-color-border);
    border-radius: 0;
}
.skin-form button {
    width: 100%;
}
</style>

<?php get_footer(); ?>
