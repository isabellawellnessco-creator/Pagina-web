<?php
/**
 * Mobile Header (App Style)
 * Layout:
 * Row 1: Title + Notification Bell
 * Row 2: Search Input + Filter Button
 */
$title = "Homad";
if (is_front_page()) $title = "Bring Home Elegance";
elseif (is_shop()) $title = "Shop Furniture";
elseif (is_page('projects')) $title = "Projects & Quotes";
elseif (is_product()) $title = get_the_title();
?>
<div class="homad-app-header">

    <!-- Row 1 -->
    <div class="homad-app-header__top">
        <h1 class="homad-app-title"><?php echo esc_html($title); ?></h1>
        <button class="homad-icon-btn">
            <span class="dashicons dashicons-bell"></span>
        </button>
    </div>

    <!-- Row 2 -->
    <div class="homad-app-header__bottom">
        <form role="search" method="get" class="homad-search-pill" action="<?php echo home_url('/'); ?>">
            <span class="dashicons dashicons-search"></span>
            <input type="search" class="homad-search-input" placeholder="Search furniture..." value="<?php echo get_search_query(); ?>" name="s">
        </form>

        <?php if (is_shop() || is_product_category()): ?>
        <button class="homad-filter-btn" id="homad-mobile-filter-trigger">
            <span class="dashicons dashicons-filter"></span>
        </button>
        <?php endif; ?>
    </div>

</div>
