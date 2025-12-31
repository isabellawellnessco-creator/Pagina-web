/**
 * Homad Skincare Skin - App Logic
 */
jQuery(document).ready(function($) {
    // 1. Mobile Bottom Nav Active State
    var currentUrl = window.location.href;
    $('.homad-bottom-nav .nav-item').each(function() {
        if (this.href === currentUrl) {
            $(this).addClass('active');
        }
    });

    // 2. Mobile Menu Drawer Logic
    // Create the drawer HTML dynamically if not present (simplified for this task)
    // In a real theme, this would be in header.php, but we are patching via JS/CSS for the skin
    if ($('#mobile-menu-drawer').length === 0) {
        $('body').append(`
            <div id="mobile-menu-drawer" class="mobile-drawer">
                <div class="drawer-header">
                    <span class="drawer-title">Menu</span>
                    <button class="drawer-close">&times;</button>
                </div>
                <div class="drawer-content">
                    <ul class="drawer-menu">
                        <!-- Simulated Menu Data -->
                        <li class="has-children">
                            <div class="menu-label">
                                <span>Skincare</span>
                                <span class="toggle">+</span>
                            </div>
                            <ul class="sub-menu">
                                <li><a href="/shop?cat=cleansers">Cleansers</a></li>
                                <li><a href="/shop?cat=toners">Toners</a></li>
                                <li><a href="/shop?cat=serums">Serums</a></li>
                                <li><a href="/shop?cat=moisturisers">Moisturisers</a></li>
                            </ul>
                        </li>
                        <li class="has-children">
                            <div class="menu-label">
                                <span>Make Up</span>
                                <span class="toggle">+</span>
                            </div>
                            <ul class="sub-menu">
                                <li><a href="/shop?cat=face">Face</a></li>
                                <li><a href="/shop?cat=eyes">Eyes</a></li>
                                <li><a href="/shop?cat=lips">Lips</a></li>
                            </ul>
                        </li>
                        <li><a href="/brands">Brands</a></li>
                        <li><a href="/shop">New In</a></li>
                        <li><a href="/rewards" style="color:var(--skin-color-primary)">Loyalty</a></li>
                    </ul>
                </div>
            </div>
            <div id="mobile-menu-overlay" class="drawer-overlay"></div>
        `);
    }

    // Trigger (We need to bind this to the Search or a new "Menu" icon if present)
    // For now, let's assume the "Search" icon in bottom nav might double as a menu or we add a specific trigger.
    // Given the request, let's bind it to a custom trigger we inject into the header or bottom nav.
    // Actually, typical App UI: "Category" or "Menu" might be in bottom nav.
    // Reference: SkinCupid has "Shop" in bottom nav which opens this drawer.
    // I will hijack the "Search" or add a "Menu" item.
    // Let's bind it to any element with class .mobile-menu-trigger

    $(document).on('click', '.mobile-menu-trigger, .nav-item[href*="shop"]', function(e) {
        if ($(window).width() < 769) {
            e.preventDefault();
            $('#mobile-menu-drawer').addClass('open');
            $('#mobile-menu-overlay').addClass('open');
        }
    });

    $('.drawer-close, .drawer-overlay').on('click', function() {
        $('#mobile-menu-drawer').removeClass('open');
        $('#mobile-menu-overlay').removeClass('open');
    });

    // Accordion Logic for Submenus
    $('.drawer-menu .menu-label').on('click', function() {
        var $parent = $(this).parent();
        $parent.toggleClass('open');
        $parent.find('.sub-menu').slideToggle(300);
        $(this).find('.toggle').text($parent.hasClass('open') ? '-' : '+');
    });
});
