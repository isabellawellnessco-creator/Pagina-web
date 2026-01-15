<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rewards_Admin {
	const NONCE_ACTION = 'sk_rewards_admin_action';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'handle_actions' ] );
		add_action( 'admin_notices', [ __CLASS__, 'render_notice' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Rewards Control', 'skincare' ),
			__( 'Rewards Control', 'skincare' ),
			'manage_woocommerce',
			'sk-rewards-control',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function handle_actions() {
		if ( ! isset( $_GET['page'] ) || 'sk-rewards-control' !== $_GET['page'] ) {
			return;
		}

		if ( empty( $_GET['sk_action'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		check_admin_referer( self::NONCE_ACTION );

		$action = sanitize_text_field( wp_unslash( $_GET['sk_action'] ) );
		$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;

		if ( 'recalculate_points' === $action && $order_id ) {
			$result = self::recalculate_points( $order_id );
			$notice = $result ? 'updated' : 'error';
			wp_safe_redirect( add_query_arg( 'sk_rewards_notice', $notice, self::base_url() ) );
			exit;
		}
	}

	private static function recalculate_points( $order_id ) {
		if ( class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			return \Skincare\SiteKit\Admin\Rewards_Master::recalculate_order_points( $order_id );
		}

		return false;
	}

	public static function render_notice() {
		if ( empty( $_GET['sk_rewards_notice'] ) ) {
			return;
		}

		$notice = sanitize_text_field( wp_unslash( $_GET['sk_rewards_notice'] ) );
		if ( 'updated' === $notice ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Rewards recalculated successfully.', 'skincare' ) . '</p></div>';
		} elseif ( 'error' === $notice ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Unable to recalculate rewards for this order.', 'skincare' ) . '</p></div>';
		}
	}

	public static function render_page() {
		$filters = self::get_filters();
		$orders = self::get_orders( $filters );
		$totals = self::summarize_points( $orders );
		$sort = self::get_sorting();

		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Rewards Control Center', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Revisa puntos por pedido, valida compras reales y ajusta rápidamente cualquier inconsistencia.', 'skincare' ); ?></p>

			<div class="sk-admin-hero">
				<div class="sk-admin-grid">
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Puntos otorgados', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $totals['awarded'] ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Puntos revertidos', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $totals['reversed'] ); ?></p>
					</div>
					<div class="sk-admin-card">
						<strong><?php esc_html_e( 'Pedidos revisados', 'skincare' ); ?></strong>
						<p class="sk-admin-metric"><?php echo esc_html( $totals['count'] ); ?></p>
					</div>
				</div>
			</div>

			<form method="get" class="sk-admin-filter-bar">
				<input type="hidden" name="page" value="sk-rewards-control">
				<label>
					<?php esc_html_e( 'Email cliente', 'skincare' ); ?>
					<input type="email" name="customer_email" value="<?php echo esc_attr( $filters['customer_email'] ); ?>">
				</label>
				<label>
					<?php esc_html_e( 'Estado', 'skincare' ); ?>
					<select name="status">
						<option value=""><?php esc_html_e( 'Todos', 'skincare' ); ?></option>
						<?php foreach ( wc_get_order_statuses() as $status_key => $status_label ) : ?>
							<option value="<?php echo esc_attr( $status_key ); ?>" <?php selected( $filters['status'], $status_key ); ?>>
								<?php echo esc_html( $status_label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
				<label>
					<?php esc_html_e( 'Desde', 'skincare' ); ?>
					<input type="date" name="date_from" value="<?php echo esc_attr( $filters['date_from'] ); ?>">
				</label>
				<label>
					<?php esc_html_e( 'Hasta', 'skincare' ); ?>
					<input type="date" name="date_to" value="<?php echo esc_attr( $filters['date_to'] ); ?>">
				</label>
				<label>
					<?php esc_html_e( 'Ordenar por', 'skincare' ); ?>
					<select name="orderby">
						<option value="date" <?php selected( $sort['orderby'], 'date' ); ?>><?php esc_html_e( 'Fecha', 'skincare' ); ?></option>
						<option value="status" <?php selected( $sort['orderby'], 'status' ); ?>><?php esc_html_e( 'Estado', 'skincare' ); ?></option>
						<option value="total" <?php selected( $sort['orderby'], 'total' ); ?>><?php esc_html_e( 'Total', 'skincare' ); ?></option>
						<option value="points" <?php selected( $sort['orderby'], 'points' ); ?>><?php esc_html_e( 'Puntos', 'skincare' ); ?></option>
					</select>
				</label>
				<label>
					<?php esc_html_e( 'Dirección', 'skincare' ); ?>
					<select name="order">
						<option value="DESC" <?php selected( $sort['order'], 'DESC' ); ?>><?php esc_html_e( 'Desc', 'skincare' ); ?></option>
						<option value="ASC" <?php selected( $sort['order'], 'ASC' ); ?>><?php esc_html_e( 'Asc', 'skincare' ); ?></option>
					</select>
				</label>
				<button class="button button-primary"><?php esc_html_e( 'Filtrar', 'skincare' ); ?></button>
			</form>

			<table class="widefat fixed striped sk-admin-table">
				<thead>
					<tr>
						<th><?php echo wp_kses_post( self::sortable_header( __( 'Pedido', 'skincare' ), 'date' ) ); ?></th>
						<th><?php echo wp_kses_post( self::sortable_header( __( 'Fecha', 'skincare' ), 'date' ) ); ?></th>
						<th><?php esc_html_e( 'Cliente', 'skincare' ); ?></th>
						<th><?php echo wp_kses_post( self::sortable_header( __( 'Estado', 'skincare' ), 'status' ) ); ?></th>
						<th><?php echo wp_kses_post( self::sortable_header( __( 'Total', 'skincare' ), 'total' ) ); ?></th>
						<th><?php echo wp_kses_post( self::sortable_header( __( 'Puntos', 'skincare' ), 'points' ) ); ?></th>
						<th><?php esc_html_e( 'Acciones', 'skincare' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $orders ) ) : ?>
						<tr>
							<td colspan="6"><?php esc_html_e( 'No hay pedidos para mostrar.', 'skincare' ); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ( $orders as $order ) : ?>
							<?php
							$order_id = $order->get_id();
							$customer = $order->get_billing_email();
							$awarded = (int) $order->get_meta( '_sk_rewards_awarded', true );
							$reversed = (int) $order->get_meta( '_sk_rewards_reversed', true );
							$points_label = $awarded ? $awarded : '—';
							if ( $reversed ) {
								$points_label = sprintf( '%s (%s)', $points_label, esc_html__( 'revertido', 'skincare' ) );
							}
							$recalc_url = wp_nonce_url(
								add_query_arg(
									[
										'page' => 'sk-rewards-control',
										'sk_action' => 'recalculate_points',
										'order_id' => $order_id,
									],
									admin_url( 'admin.php' )
								),
								self::NONCE_ACTION
							);
							?>
							<tr>
								<td>
									<a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>">
										#<?php echo esc_html( $order->get_order_number() ); ?>
									</a>
								</td>
								<td><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></td>
								<td><?php echo esc_html( $customer ? $customer : __( 'Invitado', 'skincare' ) ); ?></td>
								<td><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
								<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
								<td><?php echo esc_html( $points_label ); ?></td>
								<td>
									<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>">
										<?php esc_html_e( 'Ver pedido', 'skincare' ); ?>
									</a>
									<a class="button button-small" href="<?php echo esc_url( $recalc_url ); ?>">
										<?php esc_html_e( 'Recalcular', 'skincare' ); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	private static function get_filters() {
		return [
			'customer_email' => isset( $_GET['customer_email'] ) ? sanitize_email( wp_unslash( $_GET['customer_email'] ) ) : '',
			'status' => isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '',
			'date_from' => isset( $_GET['date_from'] ) ? sanitize_text_field( wp_unslash( $_GET['date_from'] ) ) : '',
			'date_to' => isset( $_GET['date_to'] ) ? sanitize_text_field( wp_unslash( $_GET['date_to'] ) ) : '',
		];
	}

	private static function get_sorting() {
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'date';
		$order = isset( $_GET['order'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_GET['order'] ) ) ) : 'DESC';
		$allowed_orderby = [ 'date', 'status', 'total', 'points' ];
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}
		if ( 'ASC' !== $order && 'DESC' !== $order ) {
			$order = 'DESC';
		}

		return [
			'orderby' => $orderby,
			'order' => $order,
		];
	}

	private static function get_orders( $filters ) {
		$sorting = self::get_sorting();
		$args = [
			'limit' => 50,
			'orderby' => 'date',
			'order' => 'DESC',
		];

		if ( 'status' === $sorting['orderby'] ) {
			$args['orderby'] = 'status';
			$args['order'] = $sorting['order'];
		} elseif ( 'total' === $sorting['orderby'] ) {
			$args['orderby'] = 'total';
			$args['order'] = $sorting['order'];
		} elseif ( 'points' === $sorting['orderby'] ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_sk_rewards_awarded';
			$args['order'] = $sorting['order'];
		} else {
			$args['orderby'] = 'date';
			$args['order'] = $sorting['order'];
		}

		if ( $filters['status'] ) {
			$args['status'] = [ $filters['status'] ];
		}

		if ( $filters['customer_email'] ) {
			$user = get_user_by( 'email', $filters['customer_email'] );
			$args['customer'] = $user ? $user->ID : 0;
		}

		if ( $filters['date_from'] || $filters['date_to'] ) {
			$from = $filters['date_from'] ? $filters['date_from'] : '';
			$to = $filters['date_to'] ? $filters['date_to'] : '';
			$args['date_created'] = $from && $to ? $from . '...' . $to : ( $from ? '>' . $from : '<' . $to );
		}

		return wc_get_orders( $args );
	}

	private static function sortable_header( $label, $orderby ) {
		$sorting = self::get_sorting();
		$current_order = $sorting['order'];
		$new_order = ( $sorting['orderby'] === $orderby && 'ASC' === $current_order ) ? 'DESC' : 'ASC';
		$url = add_query_arg(
			array_merge(
				self::get_filters(),
				[
					'page' => 'sk-rewards-control',
					'orderby' => $orderby,
					'order' => $new_order,
				]
			),
			admin_url( 'admin.php' )
		);

		$indicator = '';
		if ( $sorting['orderby'] === $orderby ) {
			$indicator = 'ASC' === $current_order ? ' ▲' : ' ▼';
		}

		return sprintf(
			'<a href="%s">%s%s</a>',
			esc_url( $url ),
			esc_html( $label ),
			esc_html( $indicator )
		);
	}

	private static function summarize_points( $orders ) {
		$awarded = 0;
		$reversed = 0;
		foreach ( $orders as $order ) {
			$awarded += (int) $order->get_meta( '_sk_rewards_awarded', true );
			$reversed += (int) $order->get_meta( '_sk_rewards_reversed', true ) ? (int) $order->get_meta( '_sk_rewards_awarded', true ) : 0;
		}

		return [
			'awarded' => $awarded,
			'reversed' => $reversed,
			'count' => count( $orders ),
		];
	}

	private static function base_url() {
		return admin_url( 'admin.php?page=sk-rewards-control' );
	}
}
