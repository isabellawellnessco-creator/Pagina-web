<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Skincare_Mega_Menu {

    public function __construct() {
        // Add custom field to menu items to select an Elementor Template
        add_filter( 'wp_nav_menu_item_custom_fields', [ $this, 'add_custom_fields' ], 10, 4 );
        add_action( 'wp_update_nav_menu_item', [ $this, 'save_custom_fields' ], 10, 3 );

        // Inject template content into menu output
        add_filter( 'walker_nav_menu_start_el', [ $this, 'inject_mega_menu_content' ], 10, 4 );

        // Add CSS class to parent item if it has a mega menu
        add_filter( 'nav_menu_css_class', [ $this, 'add_mega_menu_class' ], 10, 3 );
    }

    public function add_custom_fields( $item_id, $item, $depth, $args ) {
        // Only top level items usually have mega menus
        if ( $depth > 0 ) return;

        $saved_template_id = get_post_meta( $item_id, '_skincare_mega_menu_template_id', true );

        // Get Elementor Templates
        $templates = get_posts( [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ] );

        ?>
        <div class="field-skincare-mega-menu description-wide">
            <label for="edit-menu-item-mega-menu-<?php echo $item_id; ?>">
                <?php _e( 'Mega Menu Template (Elementor)', 'skincare-site-kit' ); ?><br />
                <select id="edit-menu-item-mega-menu-<?php echo $item_id; ?>" class="widefat code edit-menu-item-mega-menu" name="menu-item-skincare-mega-menu[<?php echo $item_id; ?>]">
                    <option value=""><?php _e( '- Select Template -', 'skincare-site-kit' ); ?></option>
                    <?php foreach ( $templates as $template ) : ?>
                        <option value="<?php echo esc_attr( $template->ID ); ?>" <?php selected( $saved_template_id, $template->ID ); ?>>
                            <?php echo esc_html( $template->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <?php
    }

    public function save_custom_fields( $menu_id, $menu_item_db_id, $args ) {
        if ( isset( $_POST['menu-item-skincare-mega-menu'][$menu_item_db_id] ) ) {
            update_post_meta( $menu_item_db_id, '_skincare_mega_menu_template_id', sanitize_text_field( $_POST['menu-item-skincare-mega-menu'][$menu_item_db_id] ) );
        } else {
            delete_post_meta( $menu_item_db_id, '_skincare_mega_menu_template_id' );
        }
    }

    public function add_mega_menu_class( $classes, $item, $args ) {
        $template_id = get_post_meta( $item->ID, '_skincare_mega_menu_template_id', true );
        if ( ! empty( $template_id ) ) {
            $classes[] = 'has-mega-menu';
        }
        return $classes;
    }

    public function inject_mega_menu_content( $item_output, $item, $depth, $args ) {
        $template_id = get_post_meta( $item->ID, '_skincare_mega_menu_template_id', true );

        if ( ! empty( $template_id ) && class_exists( '\Elementor\Plugin' ) ) {
            $content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );

            if ( ! empty( $content ) ) {
                // Append the mega menu container after the link
                $item_output .= '<div class="skincare-mega-menu-container">';
                $item_output .= '<div class="skincare-mega-menu-inner">';
                $item_output .= $content;
                $item_output .= '</div>';
                $item_output .= '</div>';
            }
        }

        return $item_output;
    }
}

new Skincare_Mega_Menu();
