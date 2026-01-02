(function($) {
    'use strict';

    if ( ! window.homadCursorSettings || ! window.homadCursorSettings.imageUrl ) {
        return;
    }

    // Add class to body to hide default cursor via CSS
    $('body').addClass('has-custom-cursor');

    var cursorUrl = window.homadCursorSettings.imageUrl;

    // Create the cursor element
    var $cursor = $('<div id="homad-custom-cursor"></div>').css({
        'position': 'fixed',
        'width': '50px',
        'height': '50px',
        'background-image': 'url(' + cursorUrl + ')',
        'background-size': 'contain',
        'background-repeat': 'no-repeat',
        'pointer-events': 'none',
        'z-index': '99999',
        'transform': 'translate(-50%, -50%)',
        'transition': 'transform 0.1s ease-out',
        'top': '-100px', // Hide initially
        'left': '-100px'
    }).appendTo('body');

    // Mouse move logic
    $(document).on('mousemove', function(e) {
        $cursor.css({
            'top': e.clientY + 'px',
            'left': e.clientX + 'px'
        });
    });

    // Hover effects (optional - scale up)
    $('a, button, .btn').on('mouseenter', function() {
        $cursor.css('transform', 'translate(-50%, -50%) scale(1.2)');
    }).on('mouseleave', function() {
        $cursor.css('transform', 'translate(-50%, -50%) scale(1)');
    });

})(jQuery);
