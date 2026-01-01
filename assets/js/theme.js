document.addEventListener('DOMContentLoaded', function() {
    // Focus Trap Helper
    const focusTrap = (element) => {
        const focusableElements = element.querySelectorAll('a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select');
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        element.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) { /* shift + tab */
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else { /* tab */
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }
            if (e.key === 'Escape') {
               // Close logic here if needed
            }
        });
    };

    // Generic Drawer Logic
    const toggles = document.querySelectorAll('[data-toggle="drawer"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = toggle.getAttribute('data-target');
            const drawer = document.querySelector(targetId);
            if(drawer) {
                const isOpen = drawer.getAttribute('aria-hidden') === 'false';
                drawer.setAttribute('aria-hidden', !isOpen);
                document.body.classList.toggle('sk-drawer-open', !isOpen);
                if(!isOpen) focusTrap(drawer);
            }
        });
    });

    // Close on overlay click
    document.addEventListener('click', (e) => {
        if(e.target.classList.contains('sk-drawer-overlay')) {
             const openDrawer = document.querySelector('.sk-drawer[aria-hidden="false"]');
             if(openDrawer) {
                 openDrawer.setAttribute('aria-hidden', 'true');
                 document.body.classList.remove('sk-drawer-open');
             }
        }
    });
});
