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
