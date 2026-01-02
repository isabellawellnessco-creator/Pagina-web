<?php
/**
 * Homad Admin Form Builder.
 * Handles the "Form Builder" settings page.
 */

defined( 'ABSPATH' ) || exit;

function homad_add_form_builder_page() {
    add_submenu_page(
        'homad_settings',
        'Form Builder',
        'Form Builder',
        'manage_options',
        'homad_form_builder',
        'homad_form_builder_page_html'
    );
}
add_action('admin_menu', 'homad_add_form_builder_page', 20);

function homad_register_form_settings() {
    register_setting('homad_form_options', 'homad_form_fields', [
        'type' => 'array',
        'sanitize_callback' => 'homad_sanitize_form_fields'
    ]);

    register_setting('homad_form_options', 'homad_google_maps_api_key');
}
add_action('admin_init', 'homad_register_form_settings');

function homad_sanitize_form_fields($input) {
    // Sanitize the array of fields
    if (!is_array($input)) return [];

    $sanitized = [];
    foreach ($input as $field) {
        $sanitized[] = [
            'label' => sanitize_text_field($field['label']),
            'name'  => sanitize_key($field['name']), // Unique ID
            'type' => sanitize_text_field($field['type']),
            'options' => sanitize_textarea_field($field['options']), // CSV for select
            'required' => isset($field['required']) ? 1 : 0,
            'condition_service' => sanitize_text_field($field['condition_service']),
            'validation_rule' => sanitize_text_field($field['validation_rule']),
        ];
    }
    return $sanitized;
}

function homad_form_builder_page_html() {
    if (!current_user_can('manage_options')) return;

    // Get existing fields
    $fields = get_option('homad_form_fields', []);
    $api_key = get_option('homad_google_maps_api_key', '');
    ?>
    <div class="wrap">
        <h1>Homad Form Builder</h1>
        <form action="options.php" method="post">
            <?php settings_fields('homad_form_options'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="homad_google_maps_api_key">Google Maps API Key</label></th>
                    <td>
                        <input name="homad_google_maps_api_key" type="text" id="homad_google_maps_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
                        <p class="description">Required for "Address/Map" field type.</p>
                    </td>
                </tr>
            </table>

            <h2>Form Questions / Fields</h2>
            <div id="homad-form-fields-container">
                <?php
                if (!empty($fields)) {
                    foreach ($fields as $index => $field) {
                        homad_render_field_row($index, $field);
                    }
                }
                ?>
            </div>

            <button class="button" id="homad-add-field">Add New Question</button>

            <script type="text/template" id="homad-field-template">
                <?php homad_render_field_row('{{index}}', []); ?>
            </script>

            <?php submit_button('Save Form Structure'); ?>
        </form>
    </div>

    <style>
        .homad-field-row { background: #fff; border: 1px solid #ccd0d4; padding: 15px; margin-bottom: 10px; border-radius: 4px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .homad-field-row label { font-weight: bold; display: block; margin-bottom: 5px; }
        .homad-field-col { flex: 1; min-width: 150px; }
        .homad-field-handle { cursor: move; color: #aaa; margin-right: 10px; }
    </style>
    <?php
}

function homad_render_field_row($index, $field) {
    $label = isset($field['label']) ? $field['label'] : '';
    $name = isset($field['name']) ? $field['name'] : '';
    $type = isset($field['type']) ? $field['type'] : 'text';
    $options = isset($field['options']) ? $field['options'] : '';
    $required = isset($field['required']) && $field['required'] ? 'checked' : '';
    $condition = isset($field['condition_service']) ? $field['condition_service'] : '';
    $validation = isset($field['validation_rule']) ? $field['validation_rule'] : '';

    ?>
    <div class="homad-field-row">
        <span class="dashicons dashicons-menu homad-field-handle"></span>

        <div class="homad-field-col">
            <label>Label</label>
            <input type="text" name="homad_form_fields[<?php echo $index; ?>][label]" value="<?php echo esc_attr($label); ?>" class="widefat" placeholder="Question Text">
        </div>

        <div class="homad-field-col">
            <label>Field Name (ID)</label>
            <input type="text" name="homad_form_fields[<?php echo $index; ?>][name]" value="<?php echo esc_attr($name); ?>" class="widefat" placeholder="e.g. service_type">
        </div>

        <div class="homad-field-col">
            <label>Type</label>
            <select name="homad_form_fields[<?php echo $index; ?>][type]" class="widefat">
                <option value="text" <?php selected($type, 'text'); ?>>Text</option>
                <option value="textarea" <?php selected($type, 'textarea'); ?>>Long Text</option>
                <option value="number" <?php selected($type, 'number'); ?>>Number</option>
                <option value="date" <?php selected($type, 'date'); ?>>Date</option>
                <option value="address" <?php selected($type, 'address'); ?>>Address (Google Maps)</option>
                <option value="select" <?php selected($type, 'select'); ?>>Select Box</option>
            </select>
        </div>

        <div class="homad-field-col">
            <label>Options (CSV for Select)</label>
            <input type="text" name="homad_form_fields[<?php echo $index; ?>][options]" value="<?php echo esc_attr($options); ?>" class="widefat" placeholder="Option 1, Option 2">
        </div>

        <div class="homad-field-col">
            <label>Show If Service Is:</label>
            <select name="homad_form_fields[<?php echo $index; ?>][condition_service]" class="widefat">
                <option value="" <?php selected($condition, ''); ?>>Always Show</option>
                <option value="architecture" <?php selected($condition, 'architecture'); ?>>Architecture</option>
                <option value="interior" <?php selected($condition, 'interior'); ?>>Interior Design</option>
                <option value="construction" <?php selected($condition, 'construction'); ?>>Construction</option>
            </select>
        </div>

        <div class="homad-field-col">
            <label>Validation</label>
            <select name="homad_form_fields[<?php echo $index; ?>][validation_rule]" class="widefat">
                <option value="" <?php selected($validation, ''); ?>>None</option>
                <option value="dni" <?php selected($validation, 'dni'); ?>>DNI (Numbers Only)</option>
                <option value="email" <?php selected($validation, 'email'); ?>>Email</option>
            </select>
        </div>

        <div class="homad-field-col" style="flex: 0 0 50px;">
            <label>Req?</label>
            <input type="checkbox" name="homad_form_fields[<?php echo $index; ?>][required]" value="1" <?php echo $required; ?>>
        </div>

        <button class="button button-link-delete homad-remove-field">Remove</button>
    </div>
    <?php
}

function homad_enqueue_admin_scripts($hook) {
    if ('homad-settings_page_homad_form_builder' !== $hook) {
        return;
    }
    wp_enqueue_script('homad-admin-forms', get_stylesheet_directory_uri() . '/assets/core/js/homad-admin-forms.js', ['jquery', 'jquery-ui-sortable'], '1.0', true);
}
add_action('admin_enqueue_scripts', 'homad_enqueue_admin_scripts');
