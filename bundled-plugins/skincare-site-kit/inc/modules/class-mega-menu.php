<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mega_Menu {

	public static function init() {
		// Add custom fields to Menu Items
		add_filter( 'wp_nav_menu_item_custom_fields', [ __CLASS__, 'add_menu_meta_fields' ], 10, 4 );
		add_action( 'wp_update_nav_menu_item', [ __CLASS__, 'save_menu_meta_fields' ], 10, 3 );

		// Frontend rendering
		add_filter( 'walker_nav_menu_start_el', [ __CLASS__, 'inject_mega_menu_content' ], 10, 4 );
		add_filter( 'nav_menu_css_class', [ __CLASS__, 'add_mega_menu_classes' ], 10, 3 );
	}

	/**
	 * Add field to select Elementor Template (Theme Part)
	 */
	public static function add_menu_meta_fields( $item_id, $item, $depth, $args ) {
		// Only top level items usually have mega menus
		if ( $depth > 0 ) return;

		$selected_block = get_post_meta( $item_id, '_sk_mega_menu_block', true );

		// Get Templates
		$templates = get_posts( [
			'post_type'      => 'sk_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		] );

		?>
		<p class="field-sk-mega-menu description description-wide">
			<label for="edit-menu-item-sk-block-<?php echo $item_id; ?>">
				<?php _e( 'Mega Menu Block (Elementor)', 'skincare' ); ?><br />
				<select id="edit-menu-item-sk-block-<?php echo $item_id; ?>" class="widefat" name="menu-item-sk-block[<?php echo $item_id; ?>]">
					<option value="">-- <?php _e( 'None', 'skincare' ); ?> --</option>
					<?php foreach ( $templates as $template ) : ?>
						<option value="<?php echo esc_attr( $template->ID ); ?>" <?php selected( $selected_block, $template->ID ); ?>>
							<?php echo esc_html( $template->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
		<?php
	}

	public static function save_menu_meta_fields( $menu_id, $menu_item_db_id, $args ) {
		if ( isset( $_POST['menu-item-sk-block'][ $menu_item_db_id ] ) ) {
			update_post_meta( $menu_item_db_id, '_sk_mega_menu_block', sanitize_text_field( $_POST['menu-item-sk-block'][ $menu_item_db_id ] ) );
		} else {
			delete_post_meta( $menu_item_db_id, '_sk_mega_menu_block' );
		}
	}

	public static function inject_mega_menu_content( $item_output, $item, $depth, $args ) {
		$block_id = get_post_meta( $item->ID, '_sk_mega_menu_block', true );

		if ( $block_id && class_exists( '\Skincare\SiteKit\Modules\Theme_Builder' ) ) {
			// We append the mega menu container to the output
			// The CSS must position this absolutely full width
			ob_start();
			?>
			<div class="sk-mega-menu-container">
				<?php \Skincare\SiteKit\Modules\Theme_Builder::render_elementor_content( $block_id ); ?>
			</div>
			<?php
			$mega_content = ob_get_clean();
			$item_output .= $mega_content;
		}

		return $item_output;
	}

	public static function add_mega_menu_classes( $classes, $item, $args ) {
		$block_id = get_post_meta( $item->ID, '_sk_mega_menu_block', true );
		if ( $block_id ) {
			$classes[] = 'sk-has-mega-menu';
		}
		return $classes;
	}
}
