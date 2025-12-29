<?php
// ... (código anterior de CPT y Quote handler) ...

/** Manejar el formulario de contacto */
add_action('admin_post_nopriv_homad_contact_form', 'homad_handle_contact_form');
add_action('admin_post_homad_contact_form', 'homad_handle_contact_form');
function homad_handle_contact_form() {
  $reason  = sanitize_text_field($_POST['reason'] ?? '');
  $message = sanitize_textarea_field($_POST['message'] ?? '');
  $contact = sanitize_text_field($_POST['contact'] ?? '');

  // Guardar lead
  $lead_id = wp_insert_post(array(
    'post_type'   => 'lead',
    'post_title'  => 'Contact from '.$contact.' ['.current_time('mysql').']',
    'post_status' => 'publish',
    'meta_input'  => array(
      'reason'  => $reason,
      'message' => $message,
      'contact' => $contact,
    ),
  ));

  // Notificar por correo
  wp_mail(get_option('admin_email'), 'New Contact Inquiry',
    "You have a new inquiry from $contact:\n\n$message");

  // Redirigir con parámetro de éxito
  wp_redirect(home_url('/contact?submitted=1'));
  exit;
}
