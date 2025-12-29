<?php
/**
 * Quote form template placeholder.
 */
?>
<section class="homad-quote-form">
    <h2><?php echo esc_html__('Request a Quote', 'homad'); ?></h2>
    <form method="post">
        <?php wp_nonce_field('homad_quote_form', 'homad_quote_nonce'); ?>
        <div class="homad-form-field">
            <label for="homad-motivo"><?php echo esc_html__('Motivo', 'homad'); ?></label>
            <select id="homad-motivo" name="homad_motivo">
                <option value="services"><?php echo esc_html__('Services', 'homad'); ?></option>
                <option value="packages"><?php echo esc_html__('Packages', 'homad'); ?></option>
                <option value="b2b"><?php echo esc_html__('B2B', 'homad'); ?></option>
            </select>
        </div>
        <div class="homad-form-field">
            <label for="homad-location"><?php echo esc_html__('País / Ciudad', 'homad'); ?></label>
            <input id="homad-location" name="homad_location" type="text" placeholder="Country, City">
        </div>
        <div class="homad-form-field">
            <label for="homad-area"><?php echo esc_html__('m2', 'homad'); ?></label>
            <input id="homad-area" name="homad_area" type="number" min="0" step="1">
        </div>
        <div class="homad-form-field">
            <label for="homad-budget"><?php echo esc_html__('Presupuesto (rango)', 'homad'); ?></label>
            <input id="homad-budget" name="homad_budget" type="text" placeholder="Budget range">
        </div>
        <div class="homad-form-field">
            <label for="homad-timeline"><?php echo esc_html__('Timeline', 'homad'); ?></label>
            <input id="homad-timeline" name="homad_timeline" type="text" placeholder="Project timeline">
        </div>
        <div class="homad-form-field">
            <label for="homad-name"><?php echo esc_html__('Nombre', 'homad'); ?></label>
            <input id="homad-name" name="homad_name" type="text" placeholder="Full name">
        </div>
        <div class="homad-form-field">
            <label for="homad-email"><?php echo esc_html__('Email', 'homad'); ?></label>
            <input id="homad-email" name="homad_email" type="email" placeholder="name@example.com">
        </div>
        <div class="homad-form-field">
            <label for="homad-whatsapp"><?php echo esc_html__('WhatsApp', 'homad'); ?></label>
            <input id="homad-whatsapp" name="homad_whatsapp" type="text" placeholder="+00 000 000 000">
        </div>
        <div class="homad-form-field">
            <label for="homad-message"><?php echo esc_html__('Mensaje', 'homad'); ?></label>
            <textarea id="homad-message" name="homad_message" rows="4"></textarea>
        </div>
        <button type="submit"><?php echo esc_html__('Send request', 'homad'); ?></button>
    </form>
</section>
