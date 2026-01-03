<?php
namespace Skincare\SiteKit\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_branding_settings' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
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
			'Branding & Content',
			'Branding & Content',
			'manage_options',
			'sk-branding-settings',
			[ __CLASS__, 'render_branding_page' ]
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

		add_settings_field(
			'shop_archive',
			'Shop Archive Layout',
			[ __CLASS__, 'render_select_field' ],
			'sk-theme-builder',
			'sk_locations_section',
			[ 'id' => 'shop_archive' ]
		);
	}

	public static function register_branding_settings() {
		register_setting( 'sk_branding_settings', 'sk_theme_branding_settings', [ __CLASS__, 'sanitize_branding_settings' ] );
		register_setting( 'sk_branding_settings', 'sk_press_page_settings', [ __CLASS__, 'sanitize_press_settings' ] );

		add_settings_section(
			'sk_branding_section',
			__( 'Branding', 'skincare' ),
			'__return_null',
			'sk-branding-settings'
		);

		add_settings_field(
			'logo_id',
			__( 'Logo principal', 'skincare' ),
			[ __CLASS__, 'render_media_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'logo_id', 'button_label' => __( 'Seleccionar logo', 'skincare' ) ]
		);

		add_settings_field(
			'logo_width',
			__( 'Ancho máximo del logo (px)', 'skincare' ),
			[ __CLASS__, 'render_number_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'logo_width', 'min' => 60, 'max' => 480, 'step' => 10, 'default' => 180 ]
		);

		add_settings_field(
			'accent_color',
			__( 'Color principal', 'skincare' ),
			[ __CLASS__, 'render_color_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'accent_color', 'default' => '#E5757E' ]
		);

		add_settings_field(
			'accent_hover_color',
			__( 'Color principal hover', 'skincare' ),
			[ __CLASS__, 'render_color_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'accent_hover_color', 'default' => '#D9656E' ]
		);

		add_settings_field(
			'background_color',
			__( 'Color de fondo', 'skincare' ),
			[ __CLASS__, 'render_color_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'background_color', 'default' => '#FFFFFF' ]
		);

		add_settings_field(
			'text_color',
			__( 'Color de texto principal', 'skincare' ),
			[ __CLASS__, 'render_color_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'text_color', 'default' => '#0F3062' ]
		);

		add_settings_field(
			'text_light_color',
			__( 'Color de texto secundario', 'skincare' ),
			[ __CLASS__, 'render_color_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'text_light_color', 'default' => '#8798B0' ]
		);

		add_settings_field(
			'heading_font',
			__( 'Tipografía títulos', 'skincare' ),
			[ __CLASS__, 'render_text_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'heading_font', 'placeholder' => 'Playfair Display, serif' ]
		);

		add_settings_field(
			'body_font',
			__( 'Tipografía cuerpo', 'skincare' ),
			[ __CLASS__, 'render_text_field' ],
			'sk-branding-settings',
			'sk_branding_section',
			[ 'option' => 'sk_theme_branding_settings', 'id' => 'body_font', 'placeholder' => 'Inter, sans-serif' ]
		);

		add_settings_section(
			'sk_press_page_section',
			__( 'Press page & comunidad', 'skincare' ),
			'__return_null',
			'sk-branding-settings'
		);

		add_settings_field(
			'press_title',
			__( 'Título Press', 'skincare' ),
			[ __CLASS__, 'render_text_field' ],
			'sk-branding-settings',
			'sk_press_page_section',
			[ 'option' => 'sk_press_page_settings', 'id' => 'press_title', 'placeholder' => 'Skin Cupid Press' ]
		);

		add_settings_field(
			'press_subtitle',
			__( 'Subtítulo Press', 'skincare' ),
			[ __CLASS__, 'render_textarea_field' ],
			'sk-branding-settings',
			'sk_press_page_section',
			[ 'option' => 'sk_press_page_settings', 'id' => 'press_subtitle', 'placeholder' => 'Discover the must-have products...' ]
		);

		add_settings_field(
			'community_title',
			__( 'Título Comunidad', 'skincare' ),
			[ __CLASS__, 'render_text_field' ],
			'sk-branding-settings',
			'sk_press_page_section',
			[ 'option' => 'sk_press_page_settings', 'id' => 'community_title', 'placeholder' => 'Join the Skin Cupid Community' ]
		);

		add_settings_field(
			'community_handle',
			__( 'Handle Instagram', 'skincare' ),
			[ __CLASS__, 'render_text_field' ],
			'sk-branding-settings',
			'sk_press_page_section',
			[ 'option' => 'sk_press_page_settings', 'id' => 'community_handle', 'placeholder' => '@skin.cupid.uk' ]
		);

		for ( $index = 1; $index <= 12; $index++ ) {
			add_settings_field(
				'community_image_' . $index,
				sprintf( __( 'Imagen comunidad #%d', 'skincare' ), $index ),
				[ __CLASS__, 'render_media_field' ],
				'sk-branding-settings',
				'sk_press_page_section',
				[
					'option' => 'sk_press_page_settings',
					'id' => 'community_image_' . $index,
					'button_label' => __( 'Seleccionar imagen', 'skincare' ),
				]
			);
		}

		add_settings_section(
			'sk_whatsapp_section',
			__( 'WhatsApp pedidos', 'skincare' ),
			'__return_null',
			'sk-branding-settings'
		);

		add_settings_field(
			'whatsapp_template_confirm',
			__( 'Mensaje confirmar pedido', 'skincare' ),
			[ __CLASS__, 'render_textarea_field' ],
			'sk-branding-settings',
			'sk_whatsapp_section',
			[
				'option' => 'sk_press_page_settings',
				'id' => 'whatsapp_template_confirm',
				'placeholder' => __( 'Hola, confirmamos tu pedido #{order_number}. Total: {total}.', 'skincare' ),
			]
		);

		add_settings_field(
			'whatsapp_template_delivery',
			__( 'Mensaje registrar delivery', 'skincare' ),
			[ __CLASS__, 'render_textarea_field' ],
			'sk-branding-settings',
			'sk_whatsapp_section',
			[
				'option' => 'sk_press_page_settings',
				'id' => 'whatsapp_template_delivery',
				'placeholder' => __( 'Tu pedido #{order_number} fue asignado a delivery.', 'skincare' ),
			]
		);

		add_settings_field(
			'whatsapp_template_onway',
			__( 'Mensaje pedido en camino', 'skincare' ),
			[ __CLASS__, 'render_textarea_field' ],
			'sk-branding-settings',
			'sk_whatsapp_section',
			[
				'option' => 'sk_press_page_settings',
				'id' => 'whatsapp_template_onway',
				'placeholder' => __( 'Tu pedido #{order_number} va en camino. {carrier} {tracking_url}', 'skincare' ),
			]
		);

		add_settings_field(
			'whatsapp_template_delivered',
			__( 'Mensaje pedido entregado', 'skincare' ),
			[ __CLASS__, 'render_textarea_field' ],
			'sk-branding-settings',
			'sk_whatsapp_section',
			[
				'option' => 'sk_press_page_settings',
				'id' => 'whatsapp_template_delivered',
				'placeholder' => __( 'Pedido #{order_number} entregado. ¡Gracias!', 'skincare' ),
			]
		);
	}

	public static function sanitize_branding_settings( $input ) {
		$sanitized = [];
		$sanitized['logo_id'] = isset( $input['logo_id'] ) ? absint( $input['logo_id'] ) : 0;
		$sanitized['logo_width'] = isset( $input['logo_width'] ) ? absint( $input['logo_width'] ) : 180;
		$sanitized['accent_color'] = isset( $input['accent_color'] ) ? sanitize_hex_color( $input['accent_color'] ) : '#E5757E';
		$sanitized['accent_hover_color'] = isset( $input['accent_hover_color'] ) ? sanitize_hex_color( $input['accent_hover_color'] ) : '#D9656E';
		$sanitized['background_color'] = isset( $input['background_color'] ) ? sanitize_hex_color( $input['background_color'] ) : '#FFFFFF';
		$sanitized['text_color'] = isset( $input['text_color'] ) ? sanitize_hex_color( $input['text_color'] ) : '#0F3062';
		$sanitized['text_light_color'] = isset( $input['text_light_color'] ) ? sanitize_hex_color( $input['text_light_color'] ) : '#8798B0';
		$sanitized['heading_font'] = isset( $input['heading_font'] ) ? sanitize_text_field( $input['heading_font'] ) : '';
		$sanitized['body_font'] = isset( $input['body_font'] ) ? sanitize_text_field( $input['body_font'] ) : '';
		return $sanitized;
	}

	public static function sanitize_press_settings( $input ) {
		$sanitized = [];
		$sanitized['press_title'] = isset( $input['press_title'] ) ? sanitize_text_field( $input['press_title'] ) : '';
		$sanitized['press_subtitle'] = isset( $input['press_subtitle'] ) ? sanitize_textarea_field( $input['press_subtitle'] ) : '';
		$sanitized['community_title'] = isset( $input['community_title'] ) ? sanitize_text_field( $input['community_title'] ) : '';
		$sanitized['community_handle'] = isset( $input['community_handle'] ) ? sanitize_text_field( $input['community_handle'] ) : '';
		for ( $index = 1; $index <= 12; $index++ ) {
			$key = 'community_image_' . $index;
			$sanitized[ $key ] = isset( $input[ $key ] ) ? absint( $input[ $key ] ) : 0;
		}
		$sanitized['whatsapp_template_confirm'] = isset( $input['whatsapp_template_confirm'] ) ? sanitize_textarea_field( $input['whatsapp_template_confirm'] ) : '';
		$sanitized['whatsapp_template_delivery'] = isset( $input['whatsapp_template_delivery'] ) ? sanitize_textarea_field( $input['whatsapp_template_delivery'] ) : '';
		$sanitized['whatsapp_template_onway'] = isset( $input['whatsapp_template_onway'] ) ? sanitize_textarea_field( $input['whatsapp_template_onway'] ) : '';
		$sanitized['whatsapp_template_delivered'] = isset( $input['whatsapp_template_delivered'] ) ? sanitize_textarea_field( $input['whatsapp_template_delivered'] ) : '';
		return $sanitized;
	}

	public static function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, [ 'toplevel_page_skincare-site-kit', 'skincare-site-kit_page_sk-branding-settings', 'post.php', 'post-new.php' ], true ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script(
			'sk-site-kit-admin-settings',
			SKINCARE_KIT_URL . 'assets/js/admin-settings.js',
			[ 'jquery', 'wp-color-picker' ],
			'1.0.0',
			true
		);
		wp_enqueue_style( 'wp-color-picker' );
	}

	public static function render_page() {
		echo '<div class="wrap"><h1>Skincare Site Kit</h1><p>Welcome to the main configuration panel.</p></div>';
	}

	public static function render_branding_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Branding & Content Settings', 'skincare' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'sk_branding_settings' );
				do_settings_sections( 'sk-branding-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
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

	public static function render_text_field( $args ) {
		$options = get_option( $args['option'], [] );
		$id = $args['id'];
		$value = isset( $options[ $id ] ) ? $options[ $id ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		printf(
			'<input type="text" class="regular-text" name="%1$s[%2$s]" value="%3$s" placeholder="%4$s">',
			esc_attr( $args['option'] ),
			esc_attr( $id ),
			esc_attr( $value ),
			esc_attr( $placeholder )
		);
	}

	public static function render_textarea_field( $args ) {
		$options = get_option( $args['option'], [] );
		$id = $args['id'];
		$value = isset( $options[ $id ] ) ? $options[ $id ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
		printf(
			'<textarea class="large-text" rows="3" name="%1$s[%2$s]" placeholder="%3$s">%4$s</textarea>',
			esc_attr( $args['option'] ),
			esc_attr( $id ),
			esc_attr( $placeholder ),
			esc_textarea( $value )
		);
	}

	public static function render_number_field( $args ) {
		$options = get_option( $args['option'], [] );
		$id = $args['id'];
		$value = isset( $options[ $id ] ) ? $options[ $id ] : $args['default'];
		printf(
			'<input type="number" class="small-text" name="%1$s[%2$s]" value="%3$s" min="%4$s" max="%5$s" step="%6$s">',
			esc_attr( $args['option'] ),
			esc_attr( $id ),
			esc_attr( $value ),
			esc_attr( $args['min'] ),
			esc_attr( $args['max'] ),
			esc_attr( $args['step'] )
		);
	}

	public static function render_color_field( $args ) {
		$options = get_option( $args['option'], [] );
		$id = $args['id'];
		$value = isset( $options[ $id ] ) ? $options[ $id ] : $args['default'];
		printf(
			'<input type="text" class="sk-color-field" name="%1$s[%2$s]" value="%3$s">',
			esc_attr( $args['option'] ),
			esc_attr( $id ),
			esc_attr( $value )
		);
	}

	public static function render_media_field( $args ) {
		$options = get_option( $args['option'], [] );
		$id = $args['id'];
		$attachment_id = isset( $options[ $id ] ) ? absint( $options[ $id ] ) : 0;
		$image_src = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
		$button_label = isset( $args['button_label'] ) ? $args['button_label'] : __( 'Seleccionar', 'skincare' );
		?>
		<div class="sk-media-field" data-target="<?php echo esc_attr( $args['option'] . '_' . $id ); ?>">
			<input type="hidden" name="<?php echo esc_attr( $args['option'] ); ?>[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $attachment_id ); ?>">
			<div class="sk-media-preview">
				<?php if ( $image_src ) : ?>
					<img src="<?php echo esc_url( $image_src ); ?>" alt="" style="max-width: 120px; height: auto;">
				<?php endif; ?>
			</div>
			<button type="button" class="button sk-media-upload"><?php echo esc_html( $button_label ); ?></button>
			<button type="button" class="button sk-media-remove"><?php esc_html_e( 'Quitar', 'skincare' ); ?></button>
		</div>
		<?php
	}
}
