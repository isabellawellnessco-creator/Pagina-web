<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Sk_Contact_Page_Section extends Widget_Base {
	public function get_name() { return 'sk_contact_page_section'; }
	public function get_title() { return __( 'Contact: Page Section', 'skincare' ); }
	public function get_icon() { return 'eicon-mail'; }
	public function get_categories() { return [ 'skincare-contact' ]; }

	protected function render() {
		?>
		<div class="sk-contact-section">
			<div class="sk-contact-grid">
				<!-- Contact Info -->
				<div class="sk-contact-info">
					<h2 class="sk-section-title"><?php esc_html_e( 'Get in touch', 'skincare' ); ?></h2>
					<p><?php esc_html_e( 'Have a question? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.', 'skincare' ); ?></p>

					<div class="sk-contact-details">
						<div class="sk-contact-item">
							<h4><?php esc_html_e( 'Email', 'skincare' ); ?></h4>
							<p><a href="mailto:hello@skincupid.co.uk">hello@skincupid.co.uk</a></p>
						</div>
						<div class="sk-contact-item">
							<h4><?php esc_html_e( 'Hours', 'skincare' ); ?></h4>
							<p><?php esc_html_e( 'Monday - Friday: 9am - 5pm', 'skincare' ); ?></p>
						</div>
					</div>

					<div class="sk-contact-social">
						<a href="https://www.instagram.com/skincupid.official/" target="_blank" class="sk-social-icon"><i class="fab fa-instagram"></i></a>
						<a href="https://www.tiktok.com/@skincupid" target="_blank" class="sk-social-icon"><i class="fab fa-tiktok"></i></a>
						<a href="https://www.youtube.com/@skincupid" target="_blank" class="sk-social-icon"><i class="fab fa-youtube"></i></a>
					</div>
				</div>

				<!-- Contact Form -->
				<div class="sk-contact-form-wrapper">
					<form id="sk-contact-form" class="sk-contact-form">
						<div class="sk-form-group">
							<label for="sk-name"><?php esc_html_e( 'Name', 'skincare' ); ?></label>
							<input type="text" id="sk-name" name="name" required placeholder="<?php esc_attr_e( 'Your Name', 'skincare' ); ?>">
						</div>
						<div class="sk-form-group">
							<label for="sk-email"><?php esc_html_e( 'Email', 'skincare' ); ?></label>
							<input type="email" id="sk-email" name="email" required placeholder="<?php esc_attr_e( 'Your Email', 'skincare' ); ?>">
						</div>
						<div class="sk-form-group">
							<label for="sk-subject"><?php esc_html_e( 'Subject', 'skincare' ); ?></label>
							<input type="text" id="sk-subject" name="subject" placeholder="<?php esc_attr_e( 'Subject', 'skincare' ); ?>">
						</div>
						<div class="sk-form-group">
							<label for="sk-message"><?php esc_html_e( 'Message', 'skincare' ); ?></label>
							<textarea id="sk-message" name="message" rows="5" required placeholder="<?php esc_attr_e( 'How can we help?', 'skincare' ); ?>"></textarea>
						</div>
						<button type="submit" class="sk-btn sk-btn--primary"><?php esc_html_e( 'Send Message', 'skincare' ); ?></button>
						<div class="sk-form-message"></div>
					</form>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('#sk-contact-form').on('submit', function(e) {
				e.preventDefault();
				var form = $(this);
				var btn = form.find('button[type="submit"]');
				var messageBox = form.find('.sk-form-message');

				btn.addClass('is-loading').prop('disabled', true);
				messageBox.removeClass('sk-msg--success sk-msg--error').text('');

				$.ajax({
					url: sk_vars.ajax_url,
					type: 'POST',
					data: form.serialize() + '&action=sk_contact_submit&nonce=' + sk_vars.nonce,
					success: function(response) {
						if (response.success) {
							messageBox.addClass('sk-msg--success').text(response.data.message);
							form[0].reset();
						} else {
							messageBox.addClass('sk-msg--error').text(response.data.message || 'Error sending message.');
						}
					},
					error: function() {
						messageBox.addClass('sk-msg--error').text('<?php esc_html_e( 'An error occurred. Please try again.', 'skincare' ); ?>');
					},
					complete: function() {
						btn.removeClass('is-loading').prop('disabled', false);
					}
				});
			});
		});
		</script>
		<?php
	}
}
