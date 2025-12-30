/**
 * Homad App Logic
 * - Splash Screen (30 min logic)
 * - Bottom Navigation Active States
 * - Mobile Filter Drawer
 * - Sticky Buy Box
 */

document.addEventListener('DOMContentLoaded', function() {
    initSplash();
    initBottomNav();
    initFilterDrawer(); // In case manual implementation is needed
});

function initSplash() {
    // Only on mobile
    if (window.innerWidth > 900) return;

    var splash = document.getElementById('homad-splash-screen');
    if (!splash) return;

    // Check storage (30 mins = 1800 * 1000 ms)
    var lastSeen = localStorage.getItem('homad_splash_seen');
    var now = new Date().getTime();
    var expiry = 30 * 60 * 1000;

    if (!lastSeen || (now - lastSeen > expiry)) {
        // Show Splash
        splash.style.display = 'flex';
        // Force reflow
        splash.offsetHeight;
        splash.classList.add('visible');

        var btn = document.getElementById('homad-splash-btn');
        if(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                dismissSplash();
            });
        }
    } else {
        splash.style.display = 'none';
    }

    function dismissSplash() {
        splash.classList.remove('visible');
        splash.classList.add('hiding');
        setTimeout(function(){
            splash.style.display = 'none';
        }, 500);
        localStorage.setItem('homad_splash_seen', new Date().getTime());
    }
}

function initBottomNav() {
    var nav = document.querySelector('.homad-bottom-nav');
    if (!nav) return;

    // Highlight active link based on URL
    var currentUrl = window.location.href;
    var links = nav.querySelectorAll('a');

    links.forEach(function(link) {
        // Simple matching
        if (currentUrl.indexOf(link.getAttribute('href')) !== -1) {
             // Reset others
             links.forEach(l => l.classList.remove('active'));
             link.classList.add('active');
        }
    });
}

function initFilterDrawer() {
    // Logic mostly handled by inline onclicks in shortcode,
    // but here we can add swipe-to-close or outside click listener enhancement
    var overlay = document.querySelector('.homad-drawer-overlay');
    if(overlay) {
        overlay.addEventListener('click', function() {
            document.querySelector('.homad-filter-drawer').classList.remove('is-open');
            this.classList.remove('is-open');
        });
    }
}

// PDP Sticky Buy Box Logic (Mobile Only)
window.addEventListener('scroll', function() {
    if(window.innerWidth > 900) return;

    // Logic: If user scrolls past 300px (approx image height), show box
    var scrollPos = window.scrollY;
    var box = document.querySelector('.homad-sticky-buy-box');

    if(box) {
        if(scrollPos > 400) {
            box.classList.add('is-visible');
        } else {
            box.classList.remove('is-visible');
        }
    }
});
