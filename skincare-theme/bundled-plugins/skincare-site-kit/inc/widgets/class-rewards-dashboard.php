<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;

class Rewards_Dashboard extends Shortcode_Renderer {
	public function get_name() { return 'sk_rewards_dashboard'; }
	public function get_title() { return __( 'Panel de recompensas', 'skincare' ); }
	public function get_icon() { return 'eicon-star'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<div class="sk-empty-state sk-empty-state--compact">';
			echo '<span class="sk-empty-state__icon">🔒</span>';
			echo '<div>';
			echo '<h4>' . __( 'Inicia sesión para ver tus puntos', 'skincare' ) . '</h4>';
			echo '<p>' . __( 'Accede a tu cuenta para consultar saldo, historial y canjes.', 'skincare' ) . '</p>';
			echo '</div>';
			echo '</div>';
			return;
		}

		$points = 0;
		$history = [];
		$redeem_points = 500;
		$redeem_amount = 5;
		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			$points = \Skincare\SiteKit\Admin\Rewards_Master::get_user_balance( get_current_user_id() );
			$history = \Skincare\SiteKit\Admin\Rewards_Master::get_user_history( get_current_user_id(), 20 );
			$rules = \Skincare\SiteKit\Admin\Rewards_Master::get_rules();
			$redeem_points = $rules['redeem_points'];
			$redeem_amount = $rules['redeem_amount'];
		}

		echo '<div class="sk-rewards-dashboard">';
		echo '<div class="sk-card sk-points-balance">';
		echo '<h3>' . __( 'Tus Puntos', 'skincare' ) . '</h3>';
		echo '<span class="points-value">' . intval( $points ) . '</span>';
		echo '<p class="sk-rewards-subtitle">' . __( 'Cada compra suma. Canjea cuando alcances el mínimo.', 'skincare' ) . '</p>';

		echo '<div class="sk-rewards-actions">';
		if ( intval( $points ) >= $redeem_points ) {
			$amount_label = function_exists( 'wc_price' ) ? wc_price( $redeem_amount ) : $redeem_amount;
			$label = sprintf( __( 'Canjear %1$s pts por %2$s', 'skincare' ), $redeem_points, $amount_label );
			echo '<button id="sk-redeem-btn" class="btn sk-btn sk-btn--loading" data-loading-text="' . esc_attr__( 'Canjeando...', 'skincare' ) . '">' . esc_html( $label ) . '</button>';
			echo '<span class="sk-helper-text">' . __( 'El cupón se mostrará aquí al confirmar.', 'skincare' ) . '</span>';
		} else {
			echo '<div class="sk-alert sk-alert--info">';
			echo '<strong>' . __( 'Te faltan puntos', 'skincare' ) . '</strong>';
			echo '<p>' . __( 'Compra tus favoritos para llegar al mínimo de 500 puntos y canjear descuentos.', 'skincare' ) . '</p>';
			echo '</div>';
		}
		echo '<div class="sk-inline-message" role="status" aria-live="polite"></div>';
		echo '</div>';

		echo '</div>';

		if ( ! empty( $history ) ) {
			echo '<div class="sk-points-history">';
			echo '<h4>' . __( 'Historial', 'skincare' ) . '</h4>';
			echo '<ul>';
			foreach ( $history as $item ) {
				echo '<li>';
				echo '<span class="date">' . esc_html( $item['date'] ) . '</span>';
				echo '<span class="reason">' . esc_html( $item['reason'] ) . '</span>';
				$sign = $item['points'] > 0 ? '+' : '';
				echo '<span class="amount">' . $sign . esc_html( $item['points'] ) . '</span>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</div>';
		} else {
			echo '<div class="sk-empty-state sk-empty-state--compact">';
			echo '<span class="sk-empty-state__icon">✨</span>';
			echo '<div>';
			echo '<h4>' . __( 'Aún no tienes movimientos', 'skincare' ) . '</h4>';
			echo '<p>' . __( 'Realiza tu primera compra para empezar a acumular puntos.', 'skincare' ) . '</p>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}
