<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;

class Account_Dashboard extends Widget_Base {
	public function get_name() { return 'sk_account_dashboard'; }
	public function get_title() { return __( 'SK Account Dashboard', 'skincare' ); }
	public function get_icon() { return 'eicon-person'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<p>' . __( 'Por favor inicia sesión.', 'skincare' ) . '</p>';
			return;
		}
		$current_user = wp_get_current_user();
		?>
		<div class="sk-account-dashboard">
			<div class="sk-account-header">
				<h2><?php printf( __( 'Hola, %s', 'skincare' ), esc_html( $current_user->display_name ) ); ?></h2>
				<p><?php _e( 'Bienvenido a tu cuenta.', 'skincare' ); ?></p>
			</div>
			<div class="sk-account-tabs">
				<button class="active"><?php _e( 'Mis Pedidos', 'skincare' ); ?></button>
				<button><?php _e( 'Direcciones', 'skincare' ); ?></button>
				<button><?php _e( 'Detalles', 'skincare' ); ?></button>
			</div>
			<div class="sk-account-content" style="padding:20px; border:1px solid #eee; border-radius:8px; margin-top:20px;">
				<p><?php _e( 'Aquí aparecerá tu historial de pedidos.', 'skincare' ); ?></p>
			</div>
		</div>
		<style>
			.sk-account-tabs { display: flex; gap: 10px; margin-top: 20px; }
			.sk-account-tabs button { background: transparent; border: 1px solid #0F3062; color: #0F3062; padding: 10px 20px; border-radius: 20px; cursor: pointer; }
			.sk-account-tabs button.active { background: #0F3062; color: #fff; }
		</style>
		<?php
	}
}
