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
    add_meta_box('homad_product_cro', 'Conversion Features', 'homad_render_product_cro', 'product', 'normal', 'high');

    // Service Meta
    add_meta_box('homad_service_meta', 'Service Details', 'homad_render_service_meta', 'service', 'normal', 'high');

    // Package Meta
    add_meta_box('homad_package_meta', 'Package Configuration', 'homad_render_package_meta', 'package', 'normal', 'high');

    // Visual Configurator Layers (For Products)
    add_meta_box('homad_configurator_layers', 'Visual Configurator Layers', 'homad_render_configurator_layers', 'product', 'normal', 'high');

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

    // Product CRO
    if (isset($_POST['homad_eta_text'])) update_post_meta($post_id, '_homad_eta_text', sanitize_text_field($_POST['homad_eta_text']));
    if (isset($_POST['homad_discount_deadline'])) update_post_meta($post_id, '_homad_discount_deadline', sanitize_text_field($_POST['homad_discount_deadline']));
    if (isset($_POST['homad_return_policy'])) update_post_meta($post_id, '_homad_return_policy', wp_kses_post($_POST['homad_return_policy']));

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

    // Configurator Layers (Array)
    if (isset($_POST['homad_config_layers'])) {
        $layers = array_map(function($layer) {
            return [
                'name' => sanitize_text_field($layer['name']),
                'group' => sanitize_text_field($layer['group']),
                'image' => esc_url_raw($layer['image']),
                'price' => floatval($layer['price']),
                'zindex' => intval($layer['zindex'])
            ];
        }, $_POST['homad_config_layers']);
        update_post_meta($post_id, '_homad_configurator_layers', $layers);
    }
}
add_action('save_post', 'homad_save_meta_data');


/**
 * Render Functions
 */
function homad_render_configurator_layers($post) {
    $layers = get_post_meta($post->ID, '_homad_configurator_layers', true);
    if (!is_array($layers)) $layers = [];
    ?>
    <div id="homad-layers-wrapper">
        <p class="description">Upload transparent PNGs that stack on top of the main product image.</p>
        <div id="homad-layers-container">
            <?php foreach ($layers as $i => $layer) : ?>
                <div class="homad-layer-row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; background:#f9f9f9;">
                    <p>
                        <label>Name:</label> <input type="text" name="homad_config_layers[<?php echo $i; ?>][name]" value="<?php echo esc_attr($layer['name']); ?>">
                        <label>Group (e.g. Color):</label> <input type="text" name="homad_config_layers[<?php echo $i; ?>][group]" value="<?php echo esc_attr($layer['group']); ?>">
                    </p>
                    <p>
                        <label>Image URL:</label> <input type="text" name="homad_config_layers[<?php echo $i; ?>][image]" value="<?php echo esc_attr($layer['image']); ?>" class="widefat">
                    </p>
                    <p>
                        <label>Price (+):</label> <input type="number" step="0.01" name="homad_config_layers[<?php echo $i; ?>][price]" value="<?php echo esc_attr($layer['price']); ?>">
                        <label>Z-Index:</label> <input type="number" name="homad_config_layers[<?php echo $i; ?>][zindex]" value="<?php echo esc_attr($layer['zindex']); ?>">
                    </p>
                    <button type="button" class="button homad-remove-layer">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button button-primary" id="homad-add-layer">Add Layer</button>
    </div>
    <script>
        jQuery(document).ready(function($){
            $('#homad-add-layer').click(function(){
                var index = $('#homad-layers-container .homad-layer-row').length;
                var html = '<div class="homad-layer-row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px; background:#f9f9f9;">' +
                    '<p><label>Name:</label> <input type="text" name="homad_config_layers['+index+'][name]"> ' +
                    '<label>Group:</label> <input type="text" name="homad_config_layers['+index+'][group]"></p>' +
                    '<p><label>Image URL:</label> <input type="text" name="homad_config_layers['+index+'][image]" class="widefat"></p>' +
                    '<p><label>Price (+):</label> <input type="number" step="0.01" name="homad_config_layers['+index+'][price]"> ' +
                    '<label>Z-Index:</label> <input type="number" name="homad_config_layers['+index+'][zindex]"></p>' +
                    '<button type="button" class="button homad-remove-layer">Remove</button></div>';
                $('#homad-layers-container').append(html);
            });
            $(document).on('click', '.homad-remove-layer', function(){
                $(this).closest('.homad-layer-row').remove();
            });
        });
    </script>
    <?php
}

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

function homad_render_product_cro($post) {
    $eta_text = get_post_meta($post->ID, '_homad_eta_text', true);
    $deadline = get_post_meta($post->ID, '_homad_discount_deadline', true);
    $policy = get_post_meta($post->ID, '_homad_return_policy', true);
    ?>
    <p>
        <label><strong>Custom ETA Text:</strong></label><br>
        <input type="text" name="homad_eta_text" value="<?php echo esc_attr($eta_text); ?>" class="widefat" placeholder="e.g. Ships in 3-5 days">
    </p>
    <p>
        <label><strong>Discount Deadline:</strong></label><br>
        <input type="datetime-local" name="homad_discount_deadline" value="<?php echo esc_attr($deadline); ?>" class="widefat">
    </p>
    <p>
        <label><strong>Return Policy Override:</strong></label><br>
        <textarea name="homad_return_policy" class="widefat" rows="3"><?php echo esc_textarea($policy); ?></textarea>
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
