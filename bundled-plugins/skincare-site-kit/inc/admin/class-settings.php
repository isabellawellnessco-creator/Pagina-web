<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_menu() {
		add_menu_page(
			'Skincare Settings',
			'Skincare Kit',
			'manage_options',
			'skincare-site-kit',
			[ __CLASS__, 'render_page' ],
			'dashicons-heart',
			50
		);

		add_submenu_page(
			'skincare-site-kit',
			'Theme Builder',
			'Theme Builder',
			'manage_options',
			'sk-theme-builder',
			[ __CLASS__, 'render_theme_builder_page' ]
		);
	}

	public static function register_settings() {
		register_setting( 'sk_theme_builder', 'sk_theme_builder_settings' );

		add_settings_section(
			'sk_locations_section',
			'Theme Locations',
			null,
			'sk-theme-builder'
		);

		add_settings_field(
			'global_header',
			'Global Header',
			[ __CLASS__, 'render_select_field' ],
			'sk-theme-builder',
			'sk_locations_section',
			[ 'id' => 'global_header' ]
		);

		add_settings_field(
			'global_footer',
			'Global Footer',
			[ __CLASS__, 'render_select_field' ],
			'sk-theme-builder',
			'sk_locations_section',
			[ 'id' => 'global_footer' ]
		);

		add_settings_field(
			'single_product',
			'Single Product Layout',
			[ __CLASS__, 'render_select_field' ],
			'sk-theme-builder',
			'sk_locations_section',
			[ 'id' => 'single_product' ]
		);
	}

	public static function render_page() {
		echo '<div class="wrap"><h1>Skincare Site Kit</h1><p>Welcome to the main configuration panel.</p></div>';
	}

	public static function render_theme_builder_page() {
		?>
		<div class="wrap">
			<h1>Theme Builder Settings</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'sk_theme_builder' );
				do_settings_sections( 'sk-theme-builder' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public static function render_select_field( $args ) {
		$options = get_option( 'sk_theme_builder_settings' );
		$id = $args['id'];
		$selected = isset( $options[ $id ] ) ? $options[ $id ] : '';

		// Get sk_template posts
		$templates = get_posts( [
			'post_type'      => 'sk_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		] );

		echo '<select name="sk_theme_builder_settings[' . esc_attr( $id ) . ']">';
		echo '<option value="">-- Select Template --</option>';
		foreach ( $templates as $template ) {
			echo '<option value="' . esc_attr( $template->ID ) . '" ' . selected( $selected, $template->ID, false ) . '>' . esc_html( $template->post_title ) . '</option>';
		}
		echo '</select>';
	}
}
