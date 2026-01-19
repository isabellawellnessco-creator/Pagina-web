# Skincare Theme & Site Kit

## Repository Layout

* `skincare-theme/`: WordPress theme and bundled site kit plugin.
* `refs/`: Reference assets and materials kept for the project.
* Legacy delivery reports, audits, and Apps Script artifacts have been removed to keep the repo focused.

## Setup Instructions

1.  **Activate Theme**: Go to Appearance > Themes and activate `skincare-theme`.
2.  **Install Plugins**:
    *   WooCommerce
    *   Elementor
    *   Elementor Pro
3.  **Activate Site Kit**:
    *   This theme comes with a bundled plugin in `skincare-theme/bundled-plugins/skincare-site-kit/`.
    *   Move this folder to `wp-content/plugins/` (or zip it) and activate it via Plugins menu.
4.  **Seed Content**:
    *   Upon activation, the plugin will automatically create Demo Products, Categories, and Pages.
5.  **Elementor Setup**:
    *   Go to Elementor > Tools > Regenerate Files.
    *   Go to Templates > Theme Builder to assign your Headers/Footers.
    *   Use the custom widgets (SK Hero, SK Product Loop) in the editor.
    *   (Optional) Import the Elementor kits from `skincare-theme/bundled-plugins/skincare-site-kit/assets/elementor-kits/` via Elementor > Tools > Import/Export Kit.

## Required Pages & Demo Content

The site kit activation step seeds the expected baseline so the theme renders correctly out of the box. Ensure the following pages exist (created by the plugin or manually if missing):

* **Home**: Uses the SK Hero Slider, featured collections, and brand slider sections.
* **Catalog / Shop**: WooCommerce shop archive with product loop styling.
* **Product Detail**: WooCommerce single product template with gallery, price, add-to-cart, and related products.
* **About / Brand Story** (optional but recommended): Brand narrative, values, and imagery.
* **Contact**: Contact form, store info, and optional map embed.

Demo content created by the site kit includes:

* **Demo Products**: A handful of skincare items with prices, images, and descriptions.
* **Product Categories**: Typical skincare groupings (e.g., Cleansers, Serums, Moisturizers).
* **Starter Pages**: Home, Shop/Catalog, and Contact (plus any sample landing pages shipped in the kit).

If you import the Elementor kits, the demo sections and imagery should align with the widgets and layouts above.

## Success Criteria

Use this checklist to confirm a successful setup:

* **Pages visible**: Home, Catalog/Shop, Product Detail, and Contact pages render without 404s.
* **Widgets active**: SK Hero Slider, SK Product Loop, and any bundled widgets render with content.
* **WooCommerce styling**: Product archive, single product, cart, and checkout inherit theme styles.
* **Elementor sections**: Imported kit sections match the expected layout with no missing assets.
* **Typography & colors**: Tokens from `assets/css/tokens.css` apply consistently across pages.
* **Header/Footer**: Theme Builder templates are assigned and visible site-wide.

## Developer Notes

*   **CSS Architecture**:
    *   `assets/css/tokens.css`: Edit branding colors/fonts here.
    *   `assets/css/components.css`: Global UI components.
*   **Widgets**:
*   Located in `skincare-theme/bundled-plugins/skincare-site-kit/inc/widgets/`.
*   Edit PHP files to change HTML structure of complex blocks.

## Elementor Quick Edit Guide

Use these references to keep sections consistent when swapping imagery in Elementor:

* **Hero Slider (SK Hero Slider)**: 1600x700 (desktop), 900x1200 (mobile).
* **Brand Slider (SK Brand Slider)**: logos 320x180 PNG/SVG with transparent background.
* **Concern Grid (SK Shop by Concern)**: 600x750 (portrait).
* **Instagram Feed (SK Instagram Feed)**: square 800x800.

Tip: You can replace every image inside Elementor’s widget panel without touching code.
