<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards_Master {
	const LEDGER_TABLE = 'sk_points_ledger';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
		add_action( 'admin_post_sk_rewards_adjust', [ __CLASS__, 'handle_points_adjustment' ] );
		add_action( 'init', [ __CLASS__, 'maybe_create_ledger_table' ] );
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'handle_order_status' ], 10, 4 );
		add_action( 'sk_rewards_expire_points', [ __CLASS__, 'expire_points' ] );
		self::schedule_expiration();
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Rewards Master', 'skincare' ),
			__( 'Rewards Master', 'skincare' ),
			'manage_woocommerce',
			'sk-rewards-master',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function register_settings() {
		register_setting( 'sk_rewards_master', 'sk_rewards_rules', [ __CLASS__, 'sanitize_rules' ] );

		add_settings_section(
			'sk_rewards_rules_section',
			__( 'Rewards Rules', 'skincare' ),
			'__return_null',
			'sk-rewards-master'
		);

		add_settings_field(
			'points_per_currency',
			__( 'Points per currency unit', 'skincare' ),
			[ __CLASS__, 'render_number_field' ],
			'sk-rewards-master',
			'sk_rewards_rules_section',
			[ 'id' => 'points_per_currency', 'default' => 5, 'min' => 0, 'step' => 1 ]
		);

		add_settings_field(
			'award_on_status',
			__( 'Award points when order is', 'skincare' ),
			[ __CLASS__, 'render_select_field' ],
			'sk-rewards-master',
			'sk_rewards_rules_section',
			[ 'id' => 'award_on_status', 'options' => [ 'completed' => __( 'Completed', 'skincare' ), 'sk-delivered' => __( 'Entregado', 'skincare' ) ], 'default' => 'sk-delivered' ]
		);

		add_settings_field(
			'points_expiry_days',
			__( 'Points expire after (days)', 'skincare' ),
			[ __CLASS__, 'render_number_field' ],
			'sk-rewards-master',
			'sk_rewards_rules_section',
			[ 'id' => 'points_expiry_days', 'default' => 365, 'min' => 0, 'step' => 1 ]
		);

		add_settings_field(
			'redeem_points',
			__( 'Points needed to redeem', 'skincare' ),
			[ __CLASS__, 'render_number_field' ],
			'sk-rewards-master',
			'sk_rewards_rules_section',
			[ 'id' => 'redeem_points', 'default' => 500, 'min' => 1, 'step' => 1 ]
		);

		add_settings_field(
			'redeem_amount',
			__( 'Coupon amount for redemption', 'skincare' ),
			[ __CLASS__, 'render_number_field' ],
			'sk-rewards-master',
			'sk_rewards_rules_section',
			[ 'id' => 'redeem_amount', 'default' => 5, 'min' => 0.01, 'step' => 0.01 ]
		);
	}

	public static function sanitize_rules( $input ) {
		return [
			'points_per_currency' => isset( $input['points_per_currency'] ) ? absint( $input['points_per_currency'] ) : 5,
			'award_on_status' => isset( $input['award_on_status'] ) ? sanitize_text_field( $input['award_on_status'] ) : 'sk-delivered',
			'points_expiry_days' => isset( $input['points_expiry_days'] ) ? absint( $input['points_expiry_days'] ) : 365,
			'redeem_points' => isset( $input['redeem_points'] ) ? absint( $input['redeem_points'] ) : 500,
			'redeem_amount' => isset( $input['redeem_amount'] ) ? (float) $input['redeem_amount'] : 5,
		];
	}

	public static function render_number_field( $args ) {
		$options = get_option( 'sk_rewards_rules', [] );
		$value = isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ] : $args['default'];
		printf(
			'<input type="number" class="small-text" name="sk_rewards_rules[%1$s]" value="%2$s" min="%3$s" step="%4$s">',
			esc_attr( $args['id'] ),
			esc_attr( $value ),
			esc_attr( $args['min'] ),
			esc_attr( $args['step'] )
		);
	}

	public static function render_select_field( $args ) {
		$options = get_option( 'sk_rewards_rules', [] );
		$value = isset( $options[ $args['id'] ] ) ? $options[ $args['id'] ] : $args['default'];
		echo '<select name="sk_rewards_rules[' . esc_attr( $args['id'] ) . ']">';
		foreach ( $args['options'] as $key => $label ) {
			echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
	}

	public static function render_page() {
		$rules = get_option( 'sk_rewards_rules', [] );
		$ledger = self::get_recent_ledger();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Rewards Master', 'skincare' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'sk_rewards_master' );
				do_settings_sections( 'sk-rewards-master' );
				submit_button();
				?>
			</form>
			<hr>
			<h2><?php esc_html_e( 'Adjust points manually', 'skincare' ); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="sk_rewards_adjust">
				<?php wp_nonce_field( 'sk_rewards_adjust', 'sk_rewards_adjust_nonce' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="sk-user-identifier"><?php esc_html_e( 'User ID or email', 'skincare' ); ?></label></th>
						<td><input type="text" class="regular-text" id="sk-user-identifier" name="user_identifier"></td>
					</tr>
					<tr>
						<th scope="row"><label for="sk-points"><?php esc_html_e( 'Points (+/-)', 'skincare' ); ?></label></th>
						<td><input type="number" id="sk-points" name="points" value="0" step="1"></td>
					</tr>
					<tr>
						<th scope="row"><label for="sk-note"><?php esc_html_e( 'Note', 'skincare' ); ?></label></th>
						<td><input type="text" class="regular-text" id="sk-note" name="note"></td>
					</tr>
				</table>
				<?php submit_button( __( 'Apply points', 'skincare' ) ); ?>
			</form>
			<hr>
			<h2><?php esc_html_e( 'Recent points activity', 'skincare' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'User', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Order', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Points', 'skincare' ); ?></th>
						<th><?php esc_html_e( 'Note', 'skincare' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $ledger ) : ?>
						<?php foreach ( $ledger as $entry ) : ?>
							<tr>
								<td><?php echo esc_html( $entry->created_at ); ?></td>
								<td><?php echo esc_html( $entry->user_id ); ?></td>
								<td><?php echo esc_html( $entry->order_id ?: '—' ); ?></td>
								<td><?php echo esc_html( $entry->points ); ?></td>
								<td><?php echo esc_html( $entry->note ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="5">—</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function handle_points_adjustment() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'Unauthorized' );
		}

		if ( ! isset( $_POST['sk_rewards_adjust_nonce'] ) || ! wp_verify_nonce( $_POST['sk_rewards_adjust_nonce'], 'sk_rewards_adjust' ) ) {
			wp_die( 'Invalid nonce' );
		}

		$user_identifier = isset( $_POST['user_identifier'] ) ? sanitize_text_field( wp_unslash( $_POST['user_identifier'] ) ) : '';
		$points = isset( $_POST['points'] ) ? (int) $_POST['points'] : 0;
		$note = isset( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : '';

		$user = is_numeric( $user_identifier ) ? get_user_by( 'id', absint( $user_identifier ) ) : get_user_by( 'email', $user_identifier );
		if ( ! $user ) {
			wp_redirect( admin_url( 'admin.php?page=sk-rewards-master&error=user' ) );
			exit;
		}

		self::record_ledger_entry( $user->ID, 0, $points, $note ?: __( 'Manual adjustment', 'skincare' ) );
		$balance = (int) get_user_meta( $user->ID, '_sk_rewards_points', true );
		update_user_meta( $user->ID, '_sk_rewards_points', $balance + $points );
		self::append_history( $user->ID, $points, $note ?: __( 'Manual adjustment', 'skincare' ) );

		wp_redirect( admin_url( 'admin.php?page=sk-rewards-master&updated=1' ) );
		exit;
	}

	public static function handle_order_status( $order_id, $old_status, $new_status, $order ) {
		$rules = get_option( 'sk_rewards_rules', [] );
		$award_status = isset( $rules['award_on_status'] ) ? $rules['award_on_status'] : 'sk-delivered';

		if ( ! $order instanceof \WC_Order ) {
			$order = wc_get_order( $order_id );
		}

		if ( ! $order ) {
			return;
		}

		if ( in_array( $new_status, [ 'cancelled', 'refunded' ], true ) ) {
			self::revoke_points_for_order( $order, __( 'Order refund/cancel', 'skincare' ) );
			return;
		}

		if ( $new_status !== $award_status ) {
			return;
		}

		self::award_points_for_order( $order );
	}

	public static function award_points_for_order( $order ) {
		if ( ! $order instanceof \WC_Order ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order ) {
			return false;
		}

		$order_id = $order->get_id();
		if ( get_post_meta( $order_id, '_sk_rewards_awarded', true ) ) {
			return false;
		}

		$rules = get_option( 'sk_rewards_rules', [] );
		$points_per_currency = isset( $rules['points_per_currency'] ) ? absint( $rules['points_per_currency'] ) : 5;
		$total = (float) $order->get_total();
		$points = (int) round( $total * $points_per_currency );
		$user_id = $order->get_user_id();

		if ( ! $user_id || $points <= 0 ) {
			return false;
		}

		self::record_ledger_entry( $user_id, $order_id, $points, __( 'Order reward', 'skincare' ) );
		$balance = (int) get_user_meta( $user_id, '_sk_rewards_points', true );
		update_user_meta( $user_id, '_sk_rewards_points', $balance + $points );
		update_post_meta( $order_id, '_sk_rewards_awarded', $points );
		self::append_history( $user_id, $points, sprintf( 'Pedido #%s', $order->get_order_number() ) );

		return true;
	}

	public static function revoke_points_for_order( $order, $note = '' ) {
		if ( ! $order instanceof \WC_Order ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order ) {
			return false;
		}

		$order_id = $order->get_id();
		$user_id = $order->get_user_id();
		if ( ! $user_id ) {
			return false;
		}

		$awarded = (int) $order->get_meta( '_sk_rewards_awarded', true );
		if ( $awarded <= 0 ) {
			return false;
		}

		if ( $order->get_meta( '_sk_rewards_reversed', true ) ) {
			return false;
		}

		$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );
		$new_points = max( 0, $current_points - $awarded );
		update_user_meta( $user_id, '_sk_rewards_points', $new_points );
		update_post_meta( $order_id, '_sk_rewards_reversed', 1 );

		self::record_ledger_entry(
			$user_id,
			$order_id,
			-$awarded,
			$note ? $note : __( 'Order refund/cancel', 'skincare' )
		);
		self::append_history( $user_id, -$awarded, sprintf( 'Reverso del pedido #%s', $order->get_order_number() ) );

		return true;
	}

	private static function get_recent_ledger() {
		global $wpdb;
		$table = $wpdb->prefix . self::LEDGER_TABLE;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) !== $table ) {
			return [];
		}

		return $wpdb->get_results( "SELECT * FROM {$table} ORDER BY id DESC LIMIT 30" );
	}

	public static function record_ledger_entry( $user_id, $order_id, $points, $note ) {
		global $wpdb;
		$table = $wpdb->prefix . self::LEDGER_TABLE;
		$wpdb->insert(
			$table,
			[
				'user_id' => $user_id,
				'order_id' => $order_id,
				'points' => $points,
				'note' => $note,
				'created_at' => current_time( 'mysql' ),
				'is_expired' => 0,
			],
			[ '%d', '%d', '%d', '%s', '%s', '%d' ]
		);
	}

	public static function maybe_create_ledger_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . self::LEDGER_TABLE;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) === $table_name ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			order_id bigint(20) unsigned DEFAULT 0,
			points int(11) NOT NULL,
			note varchar(255) DEFAULT '',
			created_at datetime NOT NULL,
			is_expired tinyint(1) NOT NULL DEFAULT 0,
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY order_id (order_id)
		) {$charset_collate};";
		dbDelta( $sql );
	}

	private static function append_history( $user_id, $points, $reason ) {
		$history = get_user_meta( $user_id, '_sk_rewards_history', true );
		if ( ! is_array( $history ) ) {
			$history = [];
		}

		$history[] = [
			'date' => current_time( 'mysql' ),
			'points' => (int) $points,
			'reason' => $reason,
		];

		update_user_meta( $user_id, '_sk_rewards_history', $history );
	}

	private static function revoke_awarded_points( $order ) {
		self::revoke_points_for_order( $order, __( 'Order refund/cancel', 'skincare' ) );
	}

	private static function schedule_expiration() {
		if ( wp_next_scheduled( 'sk_rewards_expire_points' ) ) {
			return;
		}
		wp_schedule_event( time(), 'daily', 'sk_rewards_expire_points' );
	}

	public static function expire_points() {
		$rules = get_option( 'sk_rewards_rules', [] );
		$expiry_days = isset( $rules['points_expiry_days'] ) ? absint( $rules['points_expiry_days'] ) : 0;
		if ( $expiry_days <= 0 ) {
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . self::LEDGER_TABLE;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) !== $table ) {
			return;
		}

		$cutoff = gmdate( 'Y-m-d H:i:s', strtotime( '-' . $expiry_days . ' days' ) );
		$entries = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT id, user_id, points FROM {$table} WHERE is_expired = 0 AND points > 0 AND created_at < %s",
				$cutoff
			)
		);

		if ( empty( $entries ) ) {
			return;
		}

		foreach ( $entries as $entry ) {
			$user_id = (int) $entry->user_id;
			$points = (int) $entry->points;
			$current_points = (int) get_user_meta( $user_id, '_sk_rewards_points', true );
			$new_points = max( 0, $current_points - $points );
			update_user_meta( $user_id, '_sk_rewards_points', $new_points );
			$wpdb->update( $table, [ 'is_expired' => 1 ], [ 'id' => (int) $entry->id ], [ '%d' ], [ '%d' ] );
			self::append_history( $user_id, -$points, __( 'Puntos expirados', 'skincare' ) );
		}
	}
}
