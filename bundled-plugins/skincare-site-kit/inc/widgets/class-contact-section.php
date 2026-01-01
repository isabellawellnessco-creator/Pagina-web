<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Contact_Section extends Widget_Base {
	public function get_name() { return 'sk_contact_section'; }
	public function get_title() { return __( 'SK Contact Section', 'skincare' ); }
	public function get_icon() { return 'eicon-form-horizontal'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		?>
		<div class="sk-contact-section">
			<div class="sk-contact-info">
				<h3><?php _e( 'Contáctanos', 'skincare' ); ?></h3>
				<p><?php _e( '¿Tienes preguntas sobre tu pedido o nuestros productos? ¡Estamos aquí para ayudar!', 'skincare' ); ?></p>
				<ul>
					<li><strong>Email:</strong> help@skincupid.co.uk</li>
					<li><strong>Horario:</strong> Lunes - Viernes, 9am - 5pm</li>
				</ul>
			</div>
			<div class="sk-contact-form-wrapper">
				<form class="sk-contact-form">
					<input type="text" placeholder="<?php _e( 'Nombre', 'skincare' ); ?>" required>
					<input type="email" placeholder="<?php _e( 'Email', 'skincare' ); ?>" required>
					<select>
						<option><?php _e( 'Consulta General', 'skincare' ); ?></option>
						<option><?php _e( 'Pedido', 'skincare' ); ?></option>
						<option><?php _e( 'Devoluciones', 'skincare' ); ?></option>
					</select>
					<textarea placeholder="<?php _e( 'Mensaje', 'skincare' ); ?>" rows="5" required></textarea>
					<button type="submit" class="btn sk-btn"><?php _e( 'Enviar Mensaje', 'skincare' ); ?></button>
				</form>
			</div>
		</div>
		<style>
			.sk-contact-section { display: flex; gap: 40px; flex-wrap: wrap; }
			.sk-contact-info { flex: 1; min-width: 300px; }
			.sk-contact-form-wrapper { flex: 1; min-width: 300px; }
			.sk-contact-form input, .sk-contact-form select, .sk-contact-form textarea {
				width: 100%; margin-bottom: 15px; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
			}
		</style>
		<?php
	}
}
