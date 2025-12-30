/**
 * Homad App Logic
 * - Splash Screen
 * - Bottom Navigation Active States
 */

document.addEventListener('DOMContentLoaded', function() {
    initSplash();
    initBottomNav();
});

function initSplash() {
    // Only on mobile
    if (window.innerWidth > 900) return;

    var splash = document.getElementById('homad-splash-screen');
    if (!splash) return;

    // Check storage (30 mins = 1800000 ms)
    var lastSeen = localStorage.getItem('homad_splash_seen');
    var now = new Date().getTime();

    if (!lastSeen || (now - lastSeen > 1800000)) {
        // Show Splash
        splash.style.display = 'flex';

        // Auto hide after 3s or button click
        setTimeout(function() {
            dismissSplash();
        }, 3500); // 3.5s fallback

        var btn = splash.querySelector('.homad-splash-btn');
        if(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                dismissSplash();
            });
        }
    } else {
        // Hide immediately (just in case CSS didn't)
        splash.style.display = 'none';
    }

    function dismissSplash() {
        splash.classList.add('hiding'); // CSS transition
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
        if (link.href === currentUrl) {
            link.parentElement.classList.add('active');
        }
    });
}

// Hub Nav Logic
document.addEventListener('click', function(e){
    if(e.target.closest('.homad-hub-nav a')) {
        e.preventDefault();
        var targetId = e.target.closest('a').getAttribute('href');
        var target = document.querySelector(targetId);

        // Update active class
        document.querySelectorAll('.homad-hub-nav a').forEach(function(el){ el.classList.remove('active'); });
        e.target.closest('a').classList.add('active');

        // Scroll
        if(target) {
            var offset = 150; // header + nav height
            window.scrollTo({
                top: target.offsetTop - offset,
                behavior: 'smooth'
            });
        }
    }
});

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
