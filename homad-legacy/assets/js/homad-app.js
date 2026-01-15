/**
 * Homad App Logic
 * Handles Sticky Tabs, Quote Wizard, and Mobile Interactions.
 */

document.addEventListener('DOMContentLoaded', function() {
    const toastContainerId = 'homad-toast-container';
    const createToastContainer = () => {
        let container = document.getElementById(toastContainerId);
        if (!container) {
            container = document.createElement('div');
            container.id = toastContainerId;
            container.className = 'homad-toast-container';
            document.body.appendChild(container);
        }
        return container;
    };

    const showToast = (message, type = 'info') => {
        if (!message) return;
        const container = createToastContainer();
        const toast = document.createElement('div');
        toast.className = `homad-toast homad-toast--${type}`;
        toast.setAttribute('role', 'status');
        toast.textContent = message;
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add('is-visible');
        });

        setTimeout(() => {
            toast.classList.remove('is-visible');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    };

    window.homadToast = showToast;

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
            const payload = Object.fromEntries(formData.entries());

            const useRest = homad_vars && homad_vars.rest_url && homad_vars.lead_nonce;
            const endpoint = useRest ? `${homad_vars.rest_url}homad/v1/lead` : homad_vars.ajax_url;
            const fetchOptions = useRest
                ? {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Homad-Nonce': homad_vars.lead_nonce
                    },
                    body: JSON.stringify(payload)
                }
                : {
                    method: 'POST',
                    body: formData
                };

            fetch(endpoint, fetchOptions)
                .then(response => response.json())
                .then(data => {
                    const success = useRest ? data.success : data.success;
                    const message = useRest ? data.message : (data.data && data.data.message);
                    if (success) {
                        showToast(message || 'Solicitud enviada.', 'success');
                        leadForm.reset();
                    } else {
                        showToast(message || 'No se pudo enviar la solicitud.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Something went wrong. Please try again.', 'error');
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
