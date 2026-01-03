# Skincare Theme & Site Kit

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
