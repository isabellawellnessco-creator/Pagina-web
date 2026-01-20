<?php
namespace Skincare\SiteKit\Widgets\F8;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Skincare\SiteKit\Widgets\Base\F8_Widget_Base;
use Skincare\SiteKit\Admin\Rewards_Master;

class F8_Rewards_Dashboard extends F8_Widget_Base {

	public function get_name() { return 'f8_rewards_dashboard'; }
	public function get_title() { return __( 'F8 Rewards Dashboard', 'skincare' ); }
	public function get_icon() { return 'eicon-price-list'; }

	protected function render() {
		if ( ! is_user_logged_in() ) {
			echo '<div class="f8-rewards-login-msg"><a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '">Inicia sesión</a> para ver tus puntos.</div>';
			return;
		}

		$user_id = get_current_user_id();
		$points = Rewards_Master::get_user_balance( $user_id );
		$history = Rewards_Master::get_user_history( $user_id );

		?>
		<div class="f8-rewards-dashboard">
			<div class="f8-rewards-header">
				<h3><?php _e( 'Tus Puntos', 'skincare' ); ?></h3>
				<div class="f8-rewards-balance"><?php echo number_format_i18n( $points ); ?></div>
			</div>

			<div class="f8-rewards-history">
				<h4><?php _e( 'Historial', 'skincare' ); ?></h4>
				<?php if ( empty( $history ) ) : ?>
					<p><?php _e( 'No hay actividad reciente.', 'skincare' ); ?></p>
				<?php else : ?>
					<ul class="f8-rewards-list">
						<?php foreach ( $history as $entry ) : ?>
							<li>
								<span class="date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $entry['date'] ) ); ?></span>
								<span class="reason"><?php echo esc_html( $entry['reason'] ); ?></span>
								<span class="points <?php echo $entry['points'] > 0 ? 'positive' : 'negative'; ?>">
									<?php echo ( $entry['points'] > 0 ? '+' : '' ) . $entry['points']; ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
