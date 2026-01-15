<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Coupons_Automation {
	const OPTION = 'sk_coupon_automation_settings';
	const NONCE_ACTION = 'sk_coupon_automation_save';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_sk_coupon_automation_save', [ __CLASS__, 'handle_save' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Cupones automáticos', 'skincare' ),
			__( 'Cupones automáticos', 'skincare' ),
			'manage_woocommerce',
			'sk-coupons-automation',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$settings = get_option( self::OPTION, [] );
		$nonce = wp_create_nonce( self::NONCE_ACTION );
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Cupones automáticos', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Reglas avanzadas para generar cupones según compras, monto o primera orden.', 'skincare' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=sk_coupon_automation_save' ) ); ?>">
				<input type="hidden" name="sk_nonce" value="<?php echo esc_attr( $nonce ); ?>">

				<div class="sk-admin-panel is-active">
					<div class="sk-admin-form-grid">
						<label class="sk-admin-checkbox">
							<input type="checkbox" name="settings[enabled]" value="1" <?php checked( ! empty( $settings['enabled'] ) ); ?>>
							<?php esc_html_e( 'Activar cupones automáticos', 'skincare' ); ?>
						</label>
						<label>
							<?php esc_html_e( 'Monto mínimo de pedido', 'skincare' ); ?>
							<input type="number" step="0.01" name="settings[min_order_total]" value="<?php echo esc_attr( $settings['min_order_total'] ?? '0' ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'Aplicar solo a primera compra', 'skincare' ); ?>
							<select name="settings[first_purchase_only]">
								<option value="0" <?php selected( $settings['first_purchase_only'] ?? '0', '0' ); ?>><?php esc_html_e( 'No', 'skincare' ); ?></option>
								<option value="1" <?php selected( $settings['first_purchase_only'] ?? '0', '1' ); ?>><?php esc_html_e( 'Sí', 'skincare' ); ?></option>
							</select>
						</label>
						<label>
							<?php esc_html_e( 'Compras acumuladas para disparar', 'skincare' ); ?>
							<input type="number" name="settings[purchase_count_threshold]" value="<?php echo esc_attr( $settings['purchase_count_threshold'] ?? '0' ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'Descuento del cupón', 'skincare' ); ?>
							<input type="number" step="0.01" name="settings[coupon_amount]" value="<?php echo esc_attr( $settings['coupon_amount'] ?? '5' ); ?>">
						</label>
						<label>
							<?php esc_html_e( 'Tipo de descuento', 'skincare' ); ?>
							<select name="settings[coupon_type]">
								<option value="fixed_cart" <?php selected( $settings['coupon_type'] ?? 'fixed_cart', 'fixed_cart' ); ?>><?php esc_html_e( 'Monto fijo', 'skincare' ); ?></option>
								<option value="percent" <?php selected( $settings['coupon_type'] ?? 'fixed_cart', 'percent' ); ?>><?php esc_html_e( 'Porcentaje', 'skincare' ); ?></option>
							</select>
						</label>
						<label>
							<?php esc_html_e( 'Expira en (días)', 'skincare' ); ?>
							<input type="number" name="settings[expires_days]" value="<?php echo esc_attr( $settings['expires_days'] ?? '14' ); ?>">
						</label>
					</div>
				</div>

				<div style="margin-top: 16px;">
					<button class="button button-primary"><?php esc_html_e( 'Guardar reglas', 'skincare' ); ?></button>
				</div>
			</form>
		</div>
		<?php
	}

	public static function handle_save() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'No autorizado.', 'skincare' ) );
		}

		check_admin_referer( self::NONCE_ACTION, 'sk_nonce' );

		$settings = isset( $_POST['settings'] ) ? (array) wp_unslash( $_POST['settings'] ) : [];
		$sanitized = [
			'enabled' => ! empty( $settings['enabled'] ) ? 1 : 0,
			'min_order_total' => isset( $settings['min_order_total'] ) ? (float) $settings['min_order_total'] : 0,
			'first_purchase_only' => ! empty( $settings['first_purchase_only'] ) ? 1 : 0,
			'purchase_count_threshold' => isset( $settings['purchase_count_threshold'] ) ? (int) $settings['purchase_count_threshold'] : 0,
			'coupon_amount' => isset( $settings['coupon_amount'] ) ? (float) $settings['coupon_amount'] : 0,
			'coupon_type' => sanitize_text_field( $settings['coupon_type'] ?? 'fixed_cart' ),
			'expires_days' => isset( $settings['expires_days'] ) ? (int) $settings['expires_days'] : 0,
		];

		update_option( self::OPTION, $sanitized );

		wp_safe_redirect( add_query_arg( [ 'page' => 'sk-coupons-automation', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
		exit;
	}
}
