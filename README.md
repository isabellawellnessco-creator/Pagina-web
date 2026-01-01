# Skincare Theme & Site Kit

## Setup Instructions

1.  **Activate Theme**: Go to Appearance > Themes and activate `skincare-theme-child`.
2.  **Install Plugins**:
    *   WooCommerce
    *   Elementor
    *   Elementor Pro
3.  **Activate Site Kit**:
    *   This theme comes with a bundled plugin in `bundled-plugins/skincare-site-kit/`.
    *   Move this folder to `wp-content/plugins/` (or Zip it) and activate it via Plugins menu.
4.  **Seed Content**:
    *   Upon activation, the plugin will automatically create Demo Products, Categories, and Pages.
5.  **Elementor Setup**:
    *   Go to Elementor > Tools > Regenerate Files.
    *   Go to Templates > Theme Builder to assign your Headers/Footers.
    *   Use the custom widgets (SK Hero, SK Product Loop) in the editor.

## Developer Notes

*   **CSS Architecture**:
    *   `assets/css/tokens.css`: Edit branding colors/fonts here.
    *   `assets/css/components.css`: Global UI components.
*   **Widgets**:
    *   Located in `plugins/skincare-site-kit/includes/elementor/widgets/`.
    *   Edit PHP files to change HTML structure of complex blocks.
