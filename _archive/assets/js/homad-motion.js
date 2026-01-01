/**
 * Homad Motion
 * Handles App-like interactions: Smooth Scroll, Transitions, Animations.
 */

document.addEventListener('DOMContentLoaded', function() {

    // --- 1. Smooth Scroll (Lenis) ---
    // Assuming Lenis is loaded globally via CDN
    if (typeof Lenis !== 'undefined') {
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        // Connect Lenis to AOS
        // AOS normally listens to scroll, with lenis we might need to manually trigger
        // or just let them coexist if lenis uses native scroll emulation (it does).
        // However, standard AOS might need a refresh.
        // lenis.on('scroll', ScrollTrigger.update) // if using GSAP
    }

    // --- 2. Scroll Animations (AOS) ---
    if (typeof AOS !== 'undefined') {
        AOS.init({
            offset: 120,
            delay: 0,
            duration: 800,
            easing: 'ease-out-cubic',
            once: true, // Animates only once
            mirror: false,
            anchorPlacement: 'top-bottom',
        });
    }

    // --- 3. Page Transitions (Swup) ---
    if (typeof Swup !== 'undefined') {
        const swup = new Swup({
            containers: ['#content', '#main'], // Adjust based on Theme Structure
            plugins: [
                // new SwupFadeTheme(), // Assuming we load the theme or custom CSS
            ]
        });

        // Re-init scripts after swap
        swup.on('contentReplaced', function() {
            // Re-init AOS
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
            // Re-init other Homad scripts if necessary
            // Note: document.addEventListener('DOMContentLoaded') won't fire again.
            // We might need to extract the init logic of homad-app.js into a function and call it here.
            if(window.HomadApp && window.HomadApp.init) {
                window.HomadApp.init();
            }
        });
    }

    // --- 4. Tactile Feedback (Ripple Effect) ---
    const buttons = document.querySelectorAll('.btn, .button, .add_to_cart_button');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;

            let ripples = document.createElement('span');
            ripples.className = 'homad-ripple';
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            this.appendChild(ripples);

            setTimeout(() => {
                ripples.remove();
            }, 600);
        });
    });

});
