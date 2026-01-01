<?php
namespace Skincare\SiteKit\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Rewards_Dashboard extends Widget_Base {
	public function get_name() { return 'sk_rewards_dashboard'; }
	public function get_title() { return __( 'SK Rewards Dashboard', 'skincare' ); }
	public function get_icon() { return 'eicon-star'; }
	public function get_categories() { return [ 'general' ]; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<p>' . __( 'Inicia sesión para ver tus puntos.', 'skincare' ) . '</p>';
			return;
		}

		$points = get_user_meta( get_current_user_id(), '_sk_rewards_points', true );
		$history = get_user_meta( get_current_user_id(), '_sk_rewards_history', true );

		echo '<div class="sk-rewards-dashboard">';
		echo '<div class="sk-points-balance">';
		echo '<h3>' . __( 'Tus Puntos', 'skincare' ) . '</h3>';
		echo '<span class="points-value">' . intval( $points ) . '</span>';

		if ( intval( $points ) >= 500 ) {
			echo '<button id="sk-redeem-btn" class="btn sk-btn" style="margin-top:10px;">' . __( 'Canjear 500 pts por £5', 'skincare' ) . '</button>';
		}

		echo '</div>';

		if ( ! empty( $history ) ) {
			echo '<div class="sk-points-history">';
			echo '<h4>' . __( 'Historial', 'skincare' ) . '</h4>';
			echo '<ul>';
			foreach ( array_reverse( $history ) as $item ) {
				echo '<li>';
				echo '<span class="date">' . esc_html( $item['date'] ) . '</span>';
				echo '<span class="reason">' . esc_html( $item['reason'] ) . '</span>';
				$sign = $item['points'] > 0 ? '+' : '';
				echo '<span class="amount">' . $sign . esc_html( $item['points'] ) . '</span>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
		echo '</div>';

		?>
		<script>
		jQuery(document).ready(function($){
			$('#sk-redeem-btn').click(function(){
				if(!confirm('¿Canjear 500 puntos?')) return;
				$.post(sk_vars.ajax_url, { action: 'sk_redeem_points' }, function(res){
					if(res.success) {
						alert('Cupón: ' + res.data.code);
						location.reload();
					} else {
						alert(res.data.message);
					}
				});
			});
		});
		</script>
		<?php
	}
}
