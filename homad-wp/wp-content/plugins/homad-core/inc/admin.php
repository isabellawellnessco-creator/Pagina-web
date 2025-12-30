<?php
/**
 * Homad Admin Settings & Dashboard.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add Admin Menu.
 */
function homad_add_admin_menu() {
    add_menu_page(
        'Homad Settings',
        'Homad Settings',
        'manage_options',
        'homad_settings',
        'homad_settings_page_html',
        'dashicons-smartphone',
        59 // High priority
    );
}
add_action('admin_menu', 'homad_add_admin_menu');

/**
 * Register Settings.
 */
function homad_settings_init() {
    // --- General / Splash Section ---
    add_settings_section('homad_splash_section', 'Mobile Splash Screen', 'homad_splash_section_callback', 'homad_settings');

    register_setting('homad_options', 'homad_splash_active');
    register_setting('homad_options', 'homad_splash_title');
    register_setting('homad_options', 'homad_splash_subtitle');
    register_setting('homad_options', 'homad_splash_image');

    add_settings_field('homad_splash_active', 'Enable Splash Screen', 'homad_checkbox_render', 'homad_settings', 'homad_splash_section', ['label_for' => 'homad_splash_active']);
    add_settings_field('homad_splash_title', 'Main Title', 'homad_text_render', 'homad_settings', 'homad_splash_section', ['label_for' => 'homad_splash_title']);
    add_settings_field('homad_splash_subtitle', 'Subtitle', 'homad_text_render', 'homad_settings', 'homad_splash_section', ['label_for' => 'homad_splash_subtitle']);
    add_settings_field('homad_splash_image', 'Splash Image URL', 'homad_image_render', 'homad_settings', 'homad_splash_section', ['label_for' => 'homad_splash_image']);

    // --- Header & Brand Assets ---
    add_settings_section('homad_brand_section', 'Brand Assets & Header', 'homad_brand_section_callback', 'homad_settings');

    register_setting('homad_options', 'homad_logo_url');
    register_setting('homad_options', 'homad_primary_color');
    register_setting('homad_options', 'homad_accent_color');
    register_setting('homad_options', 'homad_customer_count_label');

    add_settings_field('homad_logo_url', 'Custom Logo URL', 'homad_image_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_logo_url']);
    add_settings_field('homad_primary_color', 'Primary Color', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_primary_color']);
    add_settings_field('homad_accent_color', 'Accent Color', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_accent_color']);
    add_settings_field('homad_customer_count_label', 'Customer Count Label', 'homad_text_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_customer_count_label']);

    // --- Footer & Contact ---
    add_settings_section('homad_footer_section', 'Footer & Contact', 'homad_footer_section_callback', 'homad_settings');

    register_setting('homad_options', 'homad_contact_email');
    register_setting('homad_options', 'homad_whatsapp_number');
    register_setting('homad_options', 'homad_footer_text');
    register_setting('homad_options', 'homad_social_instagram');
    register_setting('homad_options', 'homad_social_facebook');

    add_settings_field('homad_contact_email', 'Contact Email', 'homad_text_render', 'homad_settings', 'homad_footer_section', ['label_for' => 'homad_contact_email']);
    add_settings_field('homad_whatsapp_number', 'WhatsApp Number', 'homad_text_render', 'homad_settings', 'homad_footer_section', ['label_for' => 'homad_whatsapp_number']);
    add_settings_field('homad_footer_text', 'Footer Copyright Text', 'homad_textarea_render', 'homad_settings', 'homad_footer_section', ['label_for' => 'homad_footer_text']);
    add_settings_field('homad_social_instagram', 'Instagram URL', 'homad_text_render', 'homad_settings', 'homad_footer_section', ['label_for' => 'homad_social_instagram']);
    add_settings_field('homad_social_facebook', 'Facebook URL', 'homad_text_render', 'homad_settings', 'homad_footer_section', ['label_for' => 'homad_social_facebook']);
}
add_action('admin_init', 'homad_settings_init');

/**
 * Dashboard Widget: Quick Links (Shopify-like feel)
 */
function homad_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'homad_quick_links_widget',
        'Homad Store Overview',
        'homad_dashboard_widget_function'
    );
}
add_action('wp_dashboard_setup', 'homad_add_dashboard_widgets');

function homad_dashboard_widget_function() {
    echo '<div style="display:flex; gap:10px; flex-wrap:wrap;">';
    echo '<a href="'.admin_url('admin.php?page=wc-admin').'" class="button button-primary button-hero">View Sales Analytics</a>';
    echo '<a href="'.admin_url('edit.php?post_type=shop_order').'" class="button button-secondary button-hero">Manage Orders</a>';
    echo '<a href="'.admin_url('admin.php?page=homad_settings').'" class="button button-secondary button-hero">Store Settings</a>';
    echo '</div>';
    echo '<p style="margin-top:15px;"><strong>Quick Stats:</strong></p>';
    // Simple stat placeholders (real implementation would query wc_get_orders)
    $order_count = wp_count_posts('shop_order')->wc-processing;
    echo '<p>Orders Processing: <strong>' . ($order_count ? $order_count : 0) . '</strong></p>';
}


// --- Callbacks & Renderers ---

function homad_splash_section_callback() { echo '<p>Configure the mobile "App-like" splash screen.</p>'; }
function homad_brand_section_callback() { echo '<p>Global visual settings. Colors will update the site CSS variables.</p>'; }
function homad_footer_section_callback() { echo '<p>Manage footer content and contact details.</p>'; }

function homad_settings_page_html() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('homad_options');
            do_settings_sections('homad_settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function homad_text_render($args) {
    $option = get_option($args['label_for']);
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" class="regular-text">';
}

function homad_textarea_render($args) {
    $option = get_option($args['label_for']);
    echo '<textarea id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" class="large-text" rows="3">' . esc_textarea($option) . '</textarea>';
}

function homad_checkbox_render($args) {
    $option = get_option($args['label_for']);
    ?>
    <input type="checkbox" id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['label_for']); ?>" value="1" <?php checked(1, $option, true); ?> />
    <?php
}

function homad_color_render($args) {
    $option = get_option($args['label_for']);
    echo '<input type="color" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '">';
}

function homad_image_render($args) {
    $option = get_option($args['label_for']);
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" class="regular-text"> ';
    echo '<p class="description">Enter image URL directly (Media Uploader integration pending).</p>';
}
