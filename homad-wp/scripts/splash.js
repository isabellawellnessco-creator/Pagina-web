document.addEventListener('DOMContentLoaded', function() {
    const splashScreen = document.getElementById('splash-screen');
    const closeSplash = document.getElementById('close-splash');
    const splashSeenCookie = document.cookie.split('; ').find(row => row.startsWith('homad_splash_seen='));

    if (!splashSeenCookie) {
        if (splashScreen) {
            splashScreen.style.display = 'flex';
        }
    }

    if (closeSplash) {
        closeSplash.addEventListener('click', function() {
            if (splashScreen) {
                splashScreen.style.display = 'none';
            }
            // Set a cookie that expires in 1 day
            const d = new Date();
            d.setTime(d.getTime() + (24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = "homad_splash_seen=true;" + expires + ";path=/";
        });
    }
});
