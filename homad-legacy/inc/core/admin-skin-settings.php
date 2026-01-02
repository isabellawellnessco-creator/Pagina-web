<?php
/**
 * Skin Settings Page
 * Allows uploading custom assets for the active skin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Homad_Skin_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_admin_menu() {
		add_theme_page(
			'Homad Skin Settings',
			'Skin Settings',
			'manage_options',
			'homad-skin-settings',
			array( $this, 'settings_page_html' )
		);
	}

	public function register_settings() {
		register_setting( 'homad_skin_options', 'homad_skin_cursor_image' );
		register_setting( 'homad_skin_options', 'homad_skin_active_skin' );
	}

	public function settings_page_html() {
		?>
		<div class="wrap">
			<h1>Homad Skin Settings</h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'homad_skin_options' );
				do_settings_sections( 'homad_skin_options' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Active Skin</th>
						<td>
                            <select name="homad_skin_active_skin">
                                <option value="default" <?php selected( get_option('homad_skin_active_skin'), 'default' ); ?>>Default</option>
                                <option value="skincare" <?php selected( get_option('homad_skin_active_skin'), 'skincare' ); ?>>Skincare (Sukoshi)</option>
                            </select>
                            <p class="description">Select the active visual theme.</p>
                        </td>
					</tr>
					<tr valign="top">
						<th scope="row">Custom Cursor Image (URL)</th>
						<td>
                            <input type="text" name="homad_skin_cursor_image" value="<?php echo esc_attr( get_option( 'homad_skin_cursor_image' ) ); ?>" class="regular-text" />
                            <p class="description">Paste the URL of the animated GIF/PNG (e.g., Corgi).</p>
                        </td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

new Homad_Skin_Settings();
