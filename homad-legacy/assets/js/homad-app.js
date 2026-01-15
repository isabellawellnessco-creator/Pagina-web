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

            // Enforce REST
            const endpoint = `${homad_vars.rest_url}skincare/v1/quote`;
            const fetchOptions = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': homad_vars.rest_nonce
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            };

            fetch(endpoint, fetchOptions)
                .then(response => response.json().then(data => ({ ok: response.ok, data })))
                .then(result => {
                    // Normalize result
                    const success = result.ok && result.data && result.data.success;
                    const message = result.data && result.data.message;

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

    // --- Contact Form ---
    const contactForm = document.querySelector('.homad-contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = contactForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Sending...';
            btn.disabled = true;

            const formData = new FormData(contactForm);
            const payload = Object.fromEntries(formData.entries());
            // REST is mandatory now
            if (!homad_vars || !homad_vars.rest_url || !homad_vars.rest_nonce) {
                showToast('Error de configuración REST.', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
                return;
            }

            const contact = payload.contact || '';
            const email = contact.includes('@') ? contact : '';
            const reason = payload.reason ? `Motivo: ${payload.reason}\n` : '';

            fetch(`${homad_vars.rest_url}skincare/v1/forms/contact`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': homad_vars.rest_nonce
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    name: contact || 'Contacto',
                    email: email,
                    contact: contact,
                    message: reason + (payload.message || '')
                })
            })
                .then(response => response.json().then(data => ({ ok: response.ok, data })))
                .then(result => {
                    if (result.ok && result.data && result.data.success) {
                        showToast(result.data.message || 'Mensaje enviado.', 'success');
                        contactForm.reset();
                    } else {
                        const message = result.data && result.data.message ? result.data.message : 'No se pudo enviar el mensaje.';
                        showToast(message, 'error');
                    }
                })
                .catch(() => {
                    showToast('No se pudo enviar el mensaje.', 'error');
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
