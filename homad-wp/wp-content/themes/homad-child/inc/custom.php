<?php
/**
 * Custom CPTs y manejador de formularios.
 *
 * @package homad-child
 */
defined('ABSPATH') || exit;

/** CPT: Services */
function homad_register_services_cpt(){
  $args=array(
    'labels'=>array(
      'name'=>'Services',
      'singular_name'=>'Service'
    ),
    'public'=>true,
    'has_archive'=>false,
    'rewrite'=>array('slug'=>'service'),
    'supports'=>array('title','editor','thumbnail'),
    'show_in_rest'=>true,
  );
  register_post_type('service',$args);
}
add_action('init','homad_register_services_cpt');

/** CPT: Packages */
function homad_register_packages_cpt(){
  $args=array(
    'labels'=>array(
      'name'=>'Packages',
      'singular_name'=>'Package'
    ),
    'public'=>true,
    'has_archive'=>false,
    'rewrite'=>array('slug'=>'package'),
    'supports'=>array('title','editor','thumbnail'),
    'show_in_rest'=>true,
  );
  register_post_type('package',$args);
}
add_action('init','homad_register_packages_cpt');

/** CPT: Portfolio */
function homad_register_portfolio_cpt(){
  $args=array(
    'labels'=>array(
      'name'=>'Portfolio',
      'singular_name'=>'Case'
    ),
    'public'=>true,
    'has_archive'=>false,
    'rewrite'=>array('slug'=>'portfolio'),
    'supports'=>array('title','editor','thumbnail'),
    'show_in_rest'=>true,
  );
  register_post_type('portfolio',$args);
}
add_action('init','homad_register_portfolio_cpt');

/** Manejar el formulario de cotización */
add_action('admin_post_nopriv_homad_submit_quote','homad_handle_quote');
add_action('admin_post_homad_submit_quote','homad_handle_quote');
function homad_handle_quote(){
  // Recoger datos
  $country= sanitize_text_field($_POST['country'] ?? '');
  $type   = sanitize_text_field($_POST['type'] ?? '');
  $area   = sanitize_text_field($_POST['area'] ?? '');
  $budget = sanitize_text_field($_POST['budget'] ?? '');
  $contact= sanitize_text_field($_POST['contact'] ?? '');

  // Almacenar como entrada CPT Leads (o enviar correo)
  $lead_id=wp_insert_post(array(
    'post_type'=>'lead',
    'post_title'=>'Quote for '.$contact.' ['.current_time('mysql').']',
    'post_status'=>'publish',
    'meta_input'=>array(
      'country'=>$country,
      'type'=>$type,
      'area'=>$area,
      'budget'=>$budget,
      'contact'=>$contact,
    ),
  ));

  // Envía notificación por correo (ajusta destinatario)
  wp_mail(get_option('admin_email'),'New Quote Request','You have a new quote request from '.$contact);

  // Redirigir con mensaje de éxito
  wp_redirect(home_url('/projects?submitted=1'));
  exit;
}

/** CPT: Leads para cotizaciones */
function homad_register_leads_cpt(){
  $args=array(
    'labels'=>array('name'=>'Leads','singular_name'=>'Lead'),
    'public'=>false,
    'show_ui'=>true,
    'supports'=>array('title','custom-fields'),
  );
  register_post_type('lead',$args);
}
add_action('init','homad_register_leads_cpt');
