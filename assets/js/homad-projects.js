
jQuery(document).ready(function($) {

    // TAB SWITCHING LOGIC
    $('.homad-sticky-tabs .tab-btn').on('click', function() {
        var target = $(this).data('target');

        // 1. Update Tabs UI
        $('.homad-sticky-tabs .tab-btn').removeClass('active');
        $(this).addClass('active');

        // 2. Show/Hide Sections
        $('.hub-section').removeClass('active').hide();
        $('#' + target).addClass('active').fadeIn(300);

        // 3. Scroll to top of content (optional, for mobile feel)
        $('html, body').animate({
            scrollTop: $('.homad-hub-content').offset().top - 100
        }, 300);
    });

    // INIT: Ensure correct section is visible (default to services)
    $('#services').show();

    // GLOBAL HELPERS attached to window for inline onclicks
    window.homadScrollToQuote = function() {
        $('.homad-sticky-tabs .tab-btn[data-target="quote"]').click();
    };

    window.homadOpenQuote = function(type, detail) {
        // Switch to Quote Tab
        homadScrollToQuote();

        // Pre-fill the wizard (Simple logic accessing the Wizard instance if available)
        // This assumes quote-wizard.js exposes some method or we manipulate DOM directly.
        // We will try to manipulate the DOM of the wizard.
        setTimeout(function(){
            // Reset Wizard to Step 1
            if(typeof homadWizard !== 'undefined') {
                // Select Type
                $('input[name="quote_type"][value="'+type+'"]').prop('checked', true);

                // If detail provided, store it for Step 2 logic (custom implementation needed in wizard)
                // For now, we just ensure the type is selected.
            }
        }, 100);
    };

    // Sticky Nav Logic (Simple Class Toggle)
    var navOffset = $('.homad-sticky-tabs-wrapper').offset().top;
    $(window).scroll(function() {
        if ($(window).scrollTop() >= navOffset) {
            $('.homad-sticky-tabs-wrapper').addClass('is-sticky');
        } else {
            $('.homad-sticky-tabs-wrapper').removeClass('is-sticky');
        }
    });

});
