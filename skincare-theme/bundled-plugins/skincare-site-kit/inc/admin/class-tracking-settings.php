<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tracking_Settings {
	const OPTION = 'sk_tracking_settings';
	const NONCE_ACTION = 'sk_tracking_settings_save';

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_post_sk_tracking_settings_save', [ __CLASS__, 'handle_save' ] );
	}

	public static function register_menu() {
		add_submenu_page(
			'skincare-site-kit',
			__( 'Tracking', 'skincare' ),
			__( 'Tracking', 'skincare' ),
			'manage_woocommerce',
			'sk-tracking-settings',
			[ __CLASS__, 'render_page' ]
		);
	}

	public static function render_page() {
		$settings = get_option( self::OPTION, [] );
		$steps = isset( $settings['steps'] ) && is_array( $settings['steps'] ) ? $settings['steps'] : self::default_steps();
		$nonce = wp_create_nonce( self::NONCE_ACTION );
		?>
		<div class="wrap sk-admin-dashboard">
			<h1><?php esc_html_e( 'Tracking & Estados', 'skincare' ); ?></h1>
			<p><?php esc_html_e( 'Define etiquetas y descripciones del stepper que ve el cliente.', 'skincare' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=sk_tracking_settings_save' ) ); ?>">
				<input type="hidden" name="sk_nonce" value="<?php echo esc_attr( $nonce ); ?>">

				<div class="sk-admin-panel is-active">
					<div class="sk-admin-template-grid">
						<?php foreach ( $steps as $index => $step ) : ?>
							<div>
								<h4><?php printf( esc_html__( 'Paso %d', 'skincare' ), $index + 1 ); ?></h4>
								<input type="text" name="steps[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $step['label'] ?? '' ); ?>">
								<textarea name="steps[<?php echo esc_attr( $index ); ?>][desc]" rows="3"><?php echo esc_textarea( $step['desc'] ?? '' ); ?></textarea>
								<label>
									<?php esc_html_e( 'Estados WooCommerce (separados por coma)', 'skincare' ); ?>
									<input type="text" name="steps[<?php echo esc_attr( $index ); ?>][statuses]" value="<?php echo esc_attr( $step['statuses'] ?? '' ); ?>">
								</label>
							</div>
						<?php endforeach; ?>
					</div>
					<p class="sk-admin-muted"><?php esc_html_e( 'Tip: usa wc-processing, wc-sk-on-the-way, wc-completed para mapear pasos.', 'skincare' ); ?></p>
				</div>

				<div style="margin-top: 16px;">
					<button class="button button-primary"><?php esc_html_e( 'Guardar tracking', 'skincare' ); ?></button>
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

		$steps = isset( $_POST['steps'] ) ? (array) wp_unslash( $_POST['steps'] ) : [];
		$sanitized_steps = [];
		foreach ( $steps as $step ) {
			$sanitized_steps[] = [
				'label' => sanitize_text_field( $step['label'] ?? '' ),
				'desc' => sanitize_textarea_field( $step['desc'] ?? '' ),
				'statuses' => sanitize_text_field( $step['statuses'] ?? '' ),
			];
		}

		update_option( self::OPTION, [ 'steps' => $sanitized_steps ] );
		wp_safe_redirect( add_query_arg( [ 'page' => 'sk-tracking-settings', 'updated' => 'true' ], admin_url( 'admin.php' ) ) );
		exit;
	}

	private static function default_steps() {
		return [
			[
				'label' => __( 'Pedido confirmado', 'skincare' ),
				'desc' => __( 'Estamos preparando tu pedido.', 'skincare' ),
				'statuses' => 'wc-processing,wc-on-hold,wc-pending',
			],
			[
				'label' => __( 'Empaque', 'skincare' ),
				'desc' => __( 'Empaque en progreso.', 'skincare' ),
				'statuses' => 'wc-processing',
			],
			[
				'label' => __( 'En camino', 'skincare' ),
				'desc' => __( 'Tu pedido va en camino.', 'skincare' ),
				'statuses' => 'wc-sk-on-the-way',
			],
			[
				'label' => __( 'Entregado', 'skincare' ),
				'desc' => __( 'Pedido entregado.', 'skincare' ),
				'statuses' => 'wc-completed,wc-sk-delivered',
			],
		];
	}
}
