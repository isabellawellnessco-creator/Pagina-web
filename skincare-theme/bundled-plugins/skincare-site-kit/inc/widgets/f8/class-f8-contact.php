<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Elementor\Controls_Manager;

class F8_Contact_Form extends F8_Widget_Base {

	public function get_name() { return 'f8_contact_form'; }
	public function get_title() { return __( 'F8 Contact Form', 'skincare' ); }
	public function get_icon() { return 'eicon-form-horizontal'; }

	protected function render() {
		?>
		<div class="f8-contact-form-wrapper">
			<form id="f8-contact-form" class="f8-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="sk_contact_form_submit">
				<?php wp_nonce_field( 'sk_contact_form', 'nonce' ); ?>

				<div class="f8-form-group">
					<label for="f8-name"><?php _e( 'Nombre', 'skincare' ); ?></label>
					<input type="text" id="f8-name" name="name" required>
				</div>

				<div class="f8-form-group">
					<label for="f8-email"><?php _e( 'Email', 'skincare' ); ?></label>
					<input type="email" id="f8-email" name="email" required>
				</div>

				<div class="f8-form-group">
					<label for="f8-message"><?php _e( 'Mensaje', 'skincare' ); ?></label>
					<textarea id="f8-message" name="message" rows="5" required></textarea>
				</div>

				<button type="submit" class="f8-btn f8-btn--full"><?php _e( 'Enviar', 'skincare' ); ?></button>
				<div class="f8-form-status"></div>
			</form>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$('#f8-contact-form').on('submit', function(e) {
				e.preventDefault();
				const $form = $(this);
				const $btn = $form.find('button');
				const $status = $form.find('.f8-form-status');

				$btn.addClass('is-loading').prop('disabled', true);

				$.post(sk_vars.ajax_url, $form.serialize(), function(response) {
					if(response.success) {
						$status.html('<p class="success">' + response.data + '</p>');
						$form[0].reset();
					} else {
						$status.html('<p class="error">' + response.data + '</p>');
					}
				}).fail(function() {
					$status.html('<p class="error">Error de servidor.</p>');
				}).always(function() {
					$btn.removeClass('is-loading').prop('disabled', false);
				});
			});
		});
		</script>
		<?php
	}
}
