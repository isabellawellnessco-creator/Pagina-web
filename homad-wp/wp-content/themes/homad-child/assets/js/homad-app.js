/**
 * Homad App Logic
 * Handles Sticky Tabs, Quote Wizard, and Mobile Interactions.
 */

document.addEventListener('DOMContentLoaded', function() {

    // --- Mobile Splash Screen Logic ---
    const splash = document.getElementById('homad-splash');
    if (splash) {
        const lastSeen = localStorage.getItem('homad_splash_seen');
        const now = new Date().getTime();
        const thirtyMin = 30 * 60 * 1000;

        if (!lastSeen || (now - lastSeen > thirtyMin)) {
            // Show splash
            splash.style.display = 'flex';
        } else {
            // Hide immediately
            splash.style.display = 'none';
        }

        // Dismiss button
        const btn = document.getElementById('homad-splash-dismiss');
        if(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                splash.style.opacity = '0';
                setTimeout(() => splash.style.display = 'none', 500);
                localStorage.setItem('homad_splash_seen', new Date().getTime());
            });
        }
    }

    // --- Quote Wizard (Projects Hub) ---
    const leadForm = document.getElementById('homad-lead-form');
    if (leadForm) {
        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = leadForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Sending...';
            btn.disabled = true;

            const formData = new FormData(leadForm);

            // Native Fetch API
            fetch(homad_vars.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.data.message);
                    leadForm.reset();
                } else {
                    alert('Error: ' + data.data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong. Please try again.');
            })
            .finally(() => {
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    }

    // --- Global: Prefill Quote Helper ---
    window.HomadApp = {
        prefillQuote: function(type, name) {
            const form = document.getElementById('homad-lead-form');
            if (form) {
                // Scroll to form
                form.scrollIntoView({ behavior: 'smooth' });

                // Set values
                const typeSelect = form.querySelector('select[name="service_type"]');
                if(typeSelect) typeSelect.value = type;

                const hiddenType = document.getElementById('input_interest_type');
                if(hiddenType) hiddenType.value = type;

                const hiddenName = document.getElementById('input_interest_name');
                if(hiddenName) hiddenName.value = name;
            }
        }
    };

    // --- Sticky Tabs Highlighting ---
    const tabs = document.querySelectorAll('.tab-link');
    if (tabs.length > 0) {
        window.addEventListener('scroll', function() {
            let fromTop = window.scrollY + 150; // Offset

            tabs.forEach(link => {
                let section = document.querySelector(link.hash);
                if (section &&
                    section.offsetTop <= fromTop &&
                    section.offsetTop + section.offsetHeight > fromTop) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    }

    // --- Mobile Filter Drawer ---
    const filterTrigger = document.getElementById('homad-mobile-filter-trigger');
    const drawer = document.querySelector('.homad-shop-sidebar'); // Using sidebar as drawer on mobile
    const closeDrawer = document.querySelector('.close-drawer');

    if (filterTrigger && drawer) {
        filterTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            drawer.classList.add('open');
            document.body.style.overflow = 'hidden'; // Lock scroll
        });
    }
    if (closeDrawer && drawer) {
        closeDrawer.addEventListener('click', function(e) {
            e.preventDefault();
            drawer.classList.remove('open');
            document.body.style.overflow = ''; // Unlock
        });
    }

});
