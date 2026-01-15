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
        'Skincare App',
        'Skincare App',
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

    // --- Header & Brand Assets (Visual Identity) ---
    add_settings_section('homad_brand_section', 'Brand Assets & Global Style', 'homad_brand_section_callback', 'homad_settings');

    register_setting('homad_options', 'homad_logo_url');
    register_setting('homad_options', 'homad_primary_color');
    register_setting('homad_options', 'homad_accent_color');
    register_setting('homad_options', 'homad_panel_bg_start'); // Gradient start
    register_setting('homad_options', 'homad_panel_bg_end');   // Gradient end
    register_setting('homad_options', 'homad_customer_count_label');

    add_settings_field('homad_logo_url', 'Custom Logo URL', 'homad_image_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_logo_url']);
    add_settings_field('homad_primary_color', 'Primary Color (Buttons)', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_primary_color']);
    add_settings_field('homad_accent_color', 'Accent Color (Highlights)', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_accent_color']);
    add_settings_field('homad_panel_bg_start', 'Panel Gradient Start', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_panel_bg_start']);
    add_settings_field('homad_panel_bg_end', 'Panel Gradient End', 'homad_color_render', 'homad_settings', 'homad_brand_section', ['label_for' => 'homad_panel_bg_end']);
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
    echo '<div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">';
    echo '<a href="'.admin_url('admin.php?page=wc-admin').'" class="button button-primary button-hero">Analytics</a>';
    echo '<a href="'.admin_url('edit.php?post_type=shop_order').'" class="button button-secondary button-hero">Orders</a>';
    echo '<a href="'.admin_url('edit.php?post_type=lead').'" class="button button-secondary button-hero">Leads</a>';
    echo '<a href="'.admin_url('admin.php?page=homad_settings').'" class="button button-secondary button-hero">Settings</a>';
    echo '</div>';

    // Quick Stats
    $new_leads = count(get_posts(['post_type'=>'lead', 'meta_key'=>'_homad_lead_status', 'meta_value'=>'new']));

    // Check WooCommerce status
    $processing_orders = 0;
    if (class_exists('WooCommerce')) {
         $processing_orders = wc_orders_count('processing');
    }

    echo '<div style="background:#f0f0f1; padding:15px; border-radius:8px;">';
    echo '<p style="margin:0 0 5px;"><strong>Action Items:</strong></p>';
    echo '<ul style="list-style:disc; margin-left:20px;">';
    echo '<li>Orders to fulfill: <strong>' . intval($processing_orders) . '</strong></li>';
    echo '<li>New Leads: <strong>' . intval($new_leads) . '</strong></li>';
    echo '</ul>';
    echo '</div>';
}


// --- Callbacks & Renderers ---

function homad_splash_section_callback() { echo '<p>Configure the mobile "App-like" splash screen.</p>'; }
function homad_brand_section_callback() { echo '<p>Global visual settings. These define the "Panel" look.</p>'; }
function homad_footer_section_callback() { echo '<p>Manage footer content and contact details.</p>'; }

function homad_settings_page_html() {
    if (!current_user_can('manage_options')) return;
    $tabs = homad_get_admin_tabs();
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'dashboard';
    if (!isset($tabs[$active_tab])) {
        $active_tab = 'dashboard';
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p class="description">Centralized control panel for the Skincare experience. Navigate tabs without losing context.</p>

        <nav class="nav-tab-wrapper homad-admin-tabs" role="tablist">
            <?php foreach ($tabs as $tab_id => $tab_label) : ?>
                <a
                    href="<?php echo esc_url(add_query_arg('tab', $tab_id, admin_url('admin.php?page=homad_settings'))); ?>"
                    class="nav-tab homad-admin-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>"
                    data-tab="<?php echo esc_attr($tab_id); ?>"
                    role="tab"
                    aria-selected="<?php echo $active_tab === $tab_id ? 'true' : 'false'; ?>"
                    aria-controls="homad-tab-<?php echo esc_attr($tab_id); ?>"
                >
                    <?php echo esc_html($tab_label); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="homad-admin-panels">
            <section id="homad-tab-dashboard" class="homad-admin-panel <?php echo $active_tab === 'dashboard' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-grid">
                    <div class="homad-panel-card">
                        <h2>Panel Overview</h2>
                        <p>Use this dashboard to configure the visual identity and UX entry points for the Skincare app.</p>
                    </div>
                    <div class="homad-panel-card">
                        <h2>Quick Actions</h2>
                        <ul class="homad-panel-list">
                            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=shop_order')); ?>">Review Orders</a></li>
                            <li><a href="<?php echo esc_url(admin_url('admin.php?page=wc-admin')); ?>">WooCommerce Analytics</a></li>
                            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=lead')); ?>">Leads Inbox</a></li>
                        </ul>
                    </div>
                </div>

                <div class="homad-panel-card">
                    <h2>Branding & Experience Settings</h2>
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('homad_options');
                        do_settings_sections('homad_settings');
                        submit_button('Save Dashboard Settings');
                        ?>
                    </form>
                </div>

                <div class="homad-panel-card">
                    <h2>Skin Settings</h2>
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('homad_skin_options');
                        if (class_exists('Homad_Skin_Settings')) {
                            Homad_Skin_Settings::render_fields();
                        }
                        submit_button('Save Skin Settings');
                        ?>
                    </form>
                </div>
            </section>

            <section id="homad-tab-orders" class="homad-admin-panel <?php echo $active_tab === 'orders' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Pedidos & Tracking</h2>
                    <p class="description">Centralize order status mapping, tracking timelines, and fulfillment visibility.</p>
                    <div class="homad-empty-state">
                        <strong>No tracking enhancements configured yet.</strong>
                        <p>Define WooCommerce status mapping, add custom steps, and enable guest tracking here.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-rewards" class="homad-admin-panel <?php echo $active_tab === 'rewards' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Recompensas / Puntos</h2>
                    <p class="description">Configure points logic, ledger visibility, and redemption UX.</p>
                    <div class="homad-empty-state">
                        <strong>No reward rules configured yet.</strong>
                        <p>Set the points ratio, earning states, and expiration policies.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-coupons" class="homad-admin-panel <?php echo $active_tab === 'coupons' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Cupones & Reglas</h2>
                    <p class="description">Build automatic coupon rules from purchase behavior.</p>
                    <div class="homad-empty-state">
                        <strong>No automatic rules created yet.</strong>
                        <p>Create simple reward rules for reactivation, order value, or purchase count.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-notifications" class="homad-admin-panel <?php echo $active_tab === 'notifications' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Notificaciones (Email + WhatsApp)</h2>
                    <p class="description">Centralize message templates, previews, and delivery logs.</p>
                    <div class="homad-empty-state">
                        <strong>Notifications are not configured.</strong>
                        <p>Enable events, edit templates, and connect WhatsApp Cloud API credentials.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-fulfillment" class="homad-admin-panel <?php echo $active_tab === 'fulfillment' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Fulfillment</h2>
                    <p class="description">Control warehouse status, packing flow, and delivery visibility.</p>
                    <div class="homad-empty-state">
                        <strong>No fulfillment workflows set.</strong>
                        <p>Define SLA, delivery partners, and packing instructions here.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-migration" class="homad-admin-panel <?php echo $active_tab === 'migration' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>Importación / Migración</h2>
                    <p class="description">Export and import the full Skincare configuration for fast installs.</p>
                    <div class="homad-empty-state">
                        <strong>No migration packages found.</strong>
                        <p>Export a JSON snapshot or import a manifest to auto-configure the app.</p>
                    </div>
                </div>
            </section>

            <section id="homad-tab-system" class="homad-admin-panel <?php echo $active_tab === 'system' ? 'is-active' : ''; ?>" role="tabpanel">
                <div class="homad-panel-card">
                    <h2>System & Logs</h2>
                    <p class="description">Monitor environment health, integrations, and logs.</p>
                </div>
                <div class="homad-panel-card">
                    <h3>Form Builder (Leads Intake)</h3>
                    <?php if (function_exists('homad_form_builder_contents')) : ?>
                        <?php homad_form_builder_contents(); ?>
                    <?php else : ?>
                        <div class="homad-empty-state">
                            <strong>Form builder unavailable.</strong>
                            <p>Ensure the form builder module is loaded.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    <style>
        .homad-admin-tabs { margin-top: 10px; }
        .homad-admin-panel { display: none; margin-top: 20px; }
        .homad-admin-panel.is-active { display: block; }
        .homad-panel-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .homad-panel-card { background: #fff; border: 1px solid #dcdcde; border-radius: 10px; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .homad-panel-card h2 { margin-top: 0; }
        .homad-panel-list { margin: 0; padding-left: 18px; }
        .homad-empty-state { background: #f6f7f7; border: 1px dashed #c3c4c7; border-radius: 8px; padding: 16px; margin-top: 10px; }
    </style>
    <script>
        (function() {
            const tabs = document.querySelectorAll('.homad-admin-tab');
            const panels = document.querySelectorAll('.homad-admin-panel');
            if (!tabs.length) {
                return;
            }

            const activateTab = (tabId) => {
                tabs.forEach((tab) => {
                    const isActive = tab.dataset.tab === tabId;
                    tab.classList.toggle('nav-tab-active', isActive);
                    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
                panels.forEach((panel) => {
                    panel.classList.toggle('is-active', panel.id === `homad-tab-${tabId}`);
                });
            };

            tabs.forEach((tab) => {
                tab.addEventListener('click', (event) => {
                    event.preventDefault();
                    const tabId = tab.dataset.tab;
                    activateTab(tabId);
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tabId);
                    window.history.replaceState({}, '', url);
                });
            });

            const currentUrl = new URL(window.location.href);
            const initialTab = currentUrl.searchParams.get('tab') || 'dashboard';
            activateTab(initialTab);
        })();
    </script>
    <?php
}

function homad_get_admin_tabs() {
    return [
        'dashboard' => 'Dashboard',
        'orders' => 'Pedidos & Tracking',
        'rewards' => 'Recompensas / Puntos',
        'coupons' => 'Cupones & Reglas',
        'notifications' => 'Notificaciones',
        'fulfillment' => 'Fulfillment',
        'migration' => 'Importación / Migración',
        'system' => 'Sistema & Logs',
    ];
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
    echo '<p class="description">Enter image URL directly.</p>';
}
