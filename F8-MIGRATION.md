# F8 Migration Guide

This guide documents the implementation of the F8 Page System in the Skin Cupid theme.

## Overview

The F8 system provides a set of modular Elementor widgets and assets to replicate the design and functionality of the F8 HTML references.

### 1. New Assets
All assets extracted from the F8 references are located in:
`skincare-theme/assets/f8/`
- `images/`: Extracted images
- `css/f8.bundle.css`: Consolidated styles
- `js/f8.bundle.js`: Consolidated scripts

### 2. New Elementor Widgets
The following custom widgets have been added to the "F8 Sections" category in Elementor:

*   **F8 Hero**: Configurable Hero section with title, subtitle, image, and CTA.
*   **F8 Store Locator**: Frontend interface for the Store Locator, connected to the `sk_store` CPT.
*   **F8 FAQ**: Repeater-based FAQ accordion.
*   **F8 Breadcrumbs**: Breadcrumb navigation wrapper.
*   **F8 Banner**: Simple banner text.
*   **F8 Rewards Dashboard**: Displays user points and history (requires login).
*   **F8 Contact Form**: AJAX contact form submitting to `admin-post.php`.
*   **F8 Product Steps**: Step-by-step visual guide.
*   **F8 Embed**: Wrapper for raw HTML/Embeds from references.
*   **F8 Grid**: Generic grid container.
*   **F8 Account Dashboard**: Wrapper for WooCommerce My Account.

### 3. Backend Modules
*   **Store Locator CPT**: A new Post Type `sk_store` is available in the admin to manage store locations.
*   **Wishlist REST API**: Endpoints added to `skincare/v1/wishlist` for frontend interaction.

### 4. Page Seeder
The `Skincare\SiteKit\Modules\Seeder` class has been updated to automatically generate the following pages with the correct F8 widget structure:
- Contact
- FAQ
- Shipping
- Wishlist
- Rewards
- Account
- Login
- Store Locator
- Careers
- Press
- Korean Skincare
- Makeup
- Vegan
- Learn

To run the seeder/repair:
1. Go to **WP Admin > Skin Cupid > Onboarding**.
2. Run the "Repair Content" or "Setup" process.

## How to Edit Pages
1. Navigate to the page in WP Admin.
2. Click "Edit with Elementor".
3. Use the Navigator to select F8 widgets.
4. Update content via the Elementor controls sidebar.

## How to Add New Pages
1. Create a new Page in WordPress.
2. Edit with Elementor.
3. Drag and drop widgets from the "F8 Sections" category.

## Developer Notes
- **Assets**: If you need to add more assets, place them in `skincare-theme/assets/f8/` and update `f8.bundle.css` or `f8.bundle.js`.
- **Extraction**: A script `tools/extract_f8_assets.py` is available to re-run asset extraction from `refs/f8/`.

## QA Checklist
- [ ] Check `skincare-theme/assets/f8/` contains assets.
- [ ] Verify "F8 Sections" appear in Elementor.
- [ ] Verify Store Locator CPT exists in Admin.
- [ ] Run Seeder and check if pages are created.
- [ ] Test Contact Form submission.
- [ ] Test Store Locator search (add a dummy store first).
- [ ] Check Wishlist functionality.
