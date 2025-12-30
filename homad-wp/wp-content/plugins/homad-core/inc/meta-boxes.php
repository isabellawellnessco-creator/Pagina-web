<?php
/**
 * Custom Meta Boxes (Native).
 * Provides "Shopify-like" fields for Products, Services, Packages, Leads.
 *
 * @package Homad_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Meta Boxes.
 */
function homad_add_meta_boxes() {
    // Product Meta
    add_meta_box('homad_product_meta', 'Product Logistics', 'homad_render_product_meta', 'product', 'side', 'high');

    // Service Meta
    add_meta_box('homad_service_meta', 'Service Details', 'homad_render_service_meta', 'service', 'normal', 'high');

    // Package Meta
    add_meta_box('homad_package_meta', 'Package Configuration', 'homad_render_package_meta', 'package', 'normal', 'high');

    // Portfolio Meta
    add_meta_box('homad_portfolio_meta', 'Project Stats', 'homad_render_portfolio_meta', 'portfolio', 'side', 'default');

    // Lead Meta (CRM)
    add_meta_box('homad_lead_meta', 'Lead Information', 'homad_render_lead_meta', 'lead', 'normal', 'high');
}
add_action('add_meta_boxes', 'homad_add_meta_boxes');

/**
 * Save Meta Data.
 */
function homad_save_meta_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Product
    if (isset($_POST['homad_eta_min'])) update_post_meta($post_id, '_homad_eta_min', sanitize_text_field($_POST['homad_eta_min']));
    if (isset($_POST['homad_eta_max'])) update_post_meta($post_id, '_homad_eta_max', sanitize_text_field($_POST['homad_eta_max']));
    update_post_meta($post_id, '_homad_install_avail', isset($_POST['homad_install_avail']) ? 'yes' : 'no');

    // Service
    if (isset($_POST['homad_service_type'])) update_post_meta($post_id, '_homad_service_type', sanitize_text_field($_POST['homad_service_type']));
    if (isset($_POST['homad_deliverables'])) update_post_meta($post_id, '_homad_deliverables', sanitize_textarea_field($_POST['homad_deliverables']));

    // Package
    if (isset($_POST['homad_pkg_segment'])) update_post_meta($post_id, '_homad_pkg_segment', sanitize_text_field($_POST['homad_pkg_segment']));
    if (isset($_POST['homad_pkg_includes'])) update_post_meta($post_id, '_homad_pkg_includes', sanitize_textarea_field($_POST['homad_pkg_includes']));
    if (isset($_POST['homad_pkg_price'])) update_post_meta($post_id, '_homad_pkg_price', sanitize_text_field($_POST['homad_pkg_price']));

    // Portfolio
    if (isset($_POST['homad_pf_location'])) update_post_meta($post_id, '_homad_pf_location', sanitize_text_field($_POST['homad_pf_location']));
    if (isset($_POST['homad_pf_area'])) update_post_meta($post_id, '_homad_pf_area', sanitize_text_field($_POST['homad_pf_area']));

    // Lead
    if (isset($_POST['homad_lead_status'])) update_post_meta($post_id, '_homad_lead_status', sanitize_text_field($_POST['homad_lead_status']));
    if (isset($_POST['homad_lead_email'])) update_post_meta($post_id, '_homad_lead_email', sanitize_email($_POST['homad_lead_email']));
    if (isset($_POST['homad_lead_phone'])) update_post_meta($post_id, '_homad_lead_phone', sanitize_text_field($_POST['homad_lead_phone']));
    if (isset($_POST['homad_lead_budget'])) update_post_meta($post_id, '_homad_lead_budget', sanitize_text_field($_POST['homad_lead_budget']));
}
add_action('save_post', 'homad_save_meta_data');


/**
 * Render Functions
 */

function homad_render_product_meta($post) {
    $eta_min = get_post_meta($post->ID, '_homad_eta_min', true);
    $eta_max = get_post_meta($post->ID, '_homad_eta_max', true);
    $install = get_post_meta($post->ID, '_homad_install_avail', true);
    ?>
    <p>
        <label>ETA (Days):</label><br>
        <input type="number" name="homad_eta_min" value="<?php echo esc_attr($eta_min); ?>" placeholder="Min" style="width:45%"> -
        <input type="number" name="homad_eta_max" value="<?php echo esc_attr($eta_max); ?>" placeholder="Max" style="width:45%">
    </p>
    <p>
        <label>
            <input type="checkbox" name="homad_install_avail" value="yes" <?php checked($install, 'yes'); ?>>
            Installation Available
        </label>
    </p>
    <?php
}

function homad_render_service_meta($post) {
    $type = get_post_meta($post->ID, '_homad_service_type', true);
    $deliv = get_post_meta($post->ID, '_homad_deliverables', true);
    ?>
    <p>
        <label>Service Type:</label>
        <select name="homad_service_type" class="widefat">
            <option value="arch" <?php selected($type, 'arch'); ?>>Architecture</option>
            <option value="interior" <?php selected($type, 'interior'); ?>>Interior Design</option>
            <option value="construction" <?php selected($type, 'construction'); ?>>Construction</option>
            <option value="feasibility" <?php selected($type, 'feasibility'); ?>>Feasibility</option>
        </select>
    </p>
    <p>
        <label>Deliverables (One per line):</label>
        <textarea name="homad_deliverables" class="widefat" rows="4"><?php echo esc_textarea($deliv); ?></textarea>
    </p>
    <?php
}

function homad_render_package_meta($post) {
    $segment = get_post_meta($post->ID, '_homad_pkg_segment', true);
    $price = get_post_meta($post->ID, '_homad_pkg_price', true);
    $includes = get_post_meta($post->ID, '_homad_pkg_includes', true);
    ?>
    <p>
        <label>Segment:</label>
        <select name="homad_pkg_segment" class="widefat">
            <option value="b2c" <?php selected($segment, 'b2c'); ?>>B2C (Homeowner)</option>
            <option value="b2b" <?php selected($segment, 'b2b'); ?>>B2B (Developer)</option>
        </select>
    </p>
    <p>
        <label>Starting Price:</label>
        <input type="text" name="homad_pkg_price" value="<?php echo esc_attr($price); ?>" class="widefat">
    </p>
    <p>
        <label>Includes (One per line):</label>
        <textarea name="homad_pkg_includes" class="widefat" rows="4"><?php echo esc_textarea($includes); ?></textarea>
    </p>
    <?php
}

function homad_render_portfolio_meta($post) {
    $loc = get_post_meta($post->ID, '_homad_pf_location', true);
    $area = get_post_meta($post->ID, '_homad_pf_area', true);
    ?>
    <p><label>Location:</label> <input type="text" name="homad_pf_location" value="<?php echo esc_attr($loc); ?>" class="widefat"></p>
    <p><label>Area (m²):</label> <input type="text" name="homad_pf_area" value="<?php echo esc_attr($area); ?>" class="widefat"></p>
    <?php
}

function homad_render_lead_meta($post) {
    $status = get_post_meta($post->ID, '_homad_lead_status', true);
    $email = get_post_meta($post->ID, '_homad_lead_email', true);
    $phone = get_post_meta($post->ID, '_homad_lead_phone', true);
    $budget = get_post_meta($post->ID, '_homad_lead_budget', true);
    ?>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <p>
            <label><strong>Status:</strong></label><br>
            <select name="homad_lead_status" class="widefat">
                <option value="new" <?php selected($status, 'new'); ?>>New</option>
                <option value="contacted" <?php selected($status, 'contacted'); ?>>Contacted</option>
                <option value="qualified" <?php selected($status, 'qualified'); ?>>Qualified</option>
                <option value="won" <?php selected($status, 'won'); ?>>Won</option>
                <option value="lost" <?php selected($status, 'lost'); ?>>Lost</option>
            </select>
        </p>
        <p>
            <label><strong>Budget:</strong></label><br>
            <input type="text" name="homad_lead_budget" value="<?php echo esc_attr($budget); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Email:</strong></label><br>
            <input type="email" name="homad_lead_email" value="<?php echo esc_attr($email); ?>" class="widefat">
        </p>
        <p>
            <label><strong>Phone:</strong></label><br>
            <input type="text" name="homad_lead_phone" value="<?php echo esc_attr($phone); ?>" class="widefat">
        </p>
    </div>
    <?php
}
