<?php
/**
 * Homad Global Settings Page
 *
 * Adds a settings page to WP Admin for managing global content like
 * Splash Screen, Header Metrics, and Contact info.
 *
 * @package homad-child
 */

defined('ABSPATH') || exit;

function homad_add_admin_menu() {
    add_menu_page(
        'Homad Settings',
        'Homad Settings',
        'manage_options',
        'homad_settings',
        'homad_settings_page_html',
        'dashicons-smartphone',
        60
    );
}
add_action('admin_menu', 'homad_add_admin_menu');

function homad_settings_init() {
    // Section: Splash Screen
    add_settings_section(
        'homad_splash_section',
        'Mobile Splash Screen',
        'homad_splash_section_callback',
        'homad_settings'
    );
    register_setting('homad_options', 'homad_splash_active');
    register_setting('homad_options', 'homad_splash_title');
    register_setting('homad_options', 'homad_splash_subtitle');
    register_setting('homad_options', 'homad_splash_image'); // URL

    add_settings_field(
        'homad_splash_active',
        'Enable Splash Screen',
        'homad_checkbox_render',
        'homad_settings',
        'homad_splash_section',
        array('label_for' => 'homad_splash_active')
    );
    add_settings_field(
        'homad_splash_title',
        'Main Title',
        'homad_text_render',
        'homad_settings',
        'homad_splash_section',
        array('label_for' => 'homad_splash_title')
    );
    add_settings_field(
        'homad_splash_subtitle',
        'Subtitle',
        'homad_text_render',
        'homad_settings',
        'homad_splash_section',
        array('label_for' => 'homad_splash_subtitle')
    );
    add_settings_field(
        'homad_splash_image',
        'Image URL',
        'homad_text_render',
        'homad_settings',
        'homad_splash_section',
        array('label_for' => 'homad_splash_image')
    );

    // Section: Header & Hero
    add_settings_section(
        'homad_header_section',
        'Header & Hero Elements',
        'homad_header_section_callback',
        'homad_settings'
    );
    register_setting('homad_options', 'homad_customer_count_label');
    register_setting('homad_options', 'homad_hero_kicker');

    add_settings_field(
        'homad_customer_count_label',
        'Customer Count Label',
        'homad_text_render',
        'homad_settings',
        'homad_header_section',
        array('label_for' => 'homad_customer_count_label', 'desc' => 'E.g. +15K Customers')
    );
    add_settings_field(
        'homad_hero_kicker',
        'Hero Kicker (Small Text)',
        'homad_text_render',
        'homad_settings',
        'homad_header_section',
        array('label_for' => 'homad_hero_kicker')
    );

    // Section: Contact & Social
    add_settings_section(
        'homad_contact_section',
        'Contact & Social',
        'homad_contact_section_callback',
        'homad_settings'
    );
    register_setting('homad_options', 'homad_whatsapp_number');
    register_setting('homad_options', 'homad_contact_email');

    add_settings_field(
        'homad_whatsapp_number',
        'WhatsApp Number',
        'homad_text_render',
        'homad_settings',
        'homad_contact_section',
        array('label_for' => 'homad_whatsapp_number')
    );
    add_settings_field(
        'homad_contact_email',
        'Contact Email',
        'homad_text_render',
        'homad_settings',
        'homad_contact_section',
        array('label_for' => 'homad_contact_email')
    );
}
add_action('admin_init', 'homad_settings_init');

// --- Callbacks ---

function homad_splash_section_callback() {
    echo '<p>Configure the "App-like" splash screen appearing on mobile.</p>';
}
function homad_header_section_callback() {
    echo '<p>Labels for the custom Header and Hero areas.</p>';
}
function homad_contact_section_callback() {
    echo '<p>Global contact details used in buttons and forms.</p>';
}

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
    $desc = isset($args['desc']) ? '<p class="description">' . esc_html($args['desc']) . '</p>' : '';
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" class="regular-text">' . $desc;
}

function homad_checkbox_render($args) {
    $option = get_option($args['label_for']);
    ?>
    <input type="checkbox" id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['label_for']); ?>" value="1" <?php checked(1, $option, true); ?> />
    <?php
}
