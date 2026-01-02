/**
 * Homad Skincare Skin - App Logic
 */
jQuery(document).ready(function($) {
    // Mobile Bottom Nav Active State Logic
    var currentUrl = window.location.href;
    $('.homad-bottom-nav .nav-item').each(function() {
        if (this.href === currentUrl) {
            $(this).addClass('active');
        }
    });

    // Cursor Logic will be injected here if enabled
});
