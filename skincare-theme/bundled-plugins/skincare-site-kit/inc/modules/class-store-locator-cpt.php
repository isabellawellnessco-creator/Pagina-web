<?php
namespace Skincare\SiteKit\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Store_Locator_CPT {

	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ __CLASS__, 'add_meta_boxes' ] );
		add_action( 'save_post', [ __CLASS__, 'save_meta_boxes' ] );
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
	}

	public static function register_post_type() {
		$labels = [
			'name'               => __( 'Tiendas', 'skincare' ),
			'singular_name'      => __( 'Tienda', 'skincare' ),
			'menu_name'          => __( 'Tiendas', 'skincare' ),
			'add_new'            => __( 'Añadir nueva', 'skincare' ),
			'add_new_item'       => __( 'Añadir nueva tienda', 'skincare' ),
			'edit_item'          => __( 'Editar tienda', 'skincare' ),
			'new_item'           => __( 'Nueva tienda', 'skincare' ),
			'view_item'          => __( 'Ver tienda', 'skincare' ),
			'search_items'       => __( 'Buscar tiendas', 'skincare' ),
			'not_found'          => __( 'No encontrado', 'skincare' ),
			'not_found_in_trash' => __( 'No encontrado en papelera', 'skincare' ),
		];

		$args = [
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'thumbnail' ], // 'editor' for description/content
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-location',
			'show_in_nav_menus'   => false,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => false, // Don't need single page frontend for now, or true if needed
			'rewrite'             => false,
			'show_in_rest'        => true,
		];

		register_post_type( 'sk_store', $args );
	}

	public static function add_meta_boxes() {
		add_meta_box(
			'sk_store_details',
			__( 'Detalles de la Tienda', 'skincare' ),
			[ __CLASS__, 'render_meta_box' ],
			'sk_store',
			'normal',
			'high'
		);
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( 'sk_store_meta_box', 'sk_store_meta_box_nonce' );

		$address = get_post_meta( $post->ID, '_sk_store_address', true );
		$city = get_post_meta( $post->ID, '_sk_store_city', true );
		$lat = get_post_meta( $post->ID, '_sk_store_lat', true );
		$lng = get_post_meta( $post->ID, '_sk_store_lng', true );
		$phone = get_post_meta( $post->ID, '_sk_store_phone', true );
		$hours = get_post_meta( $post->ID, '_sk_store_hours', true ); // Stored as text or JSON
		$email = get_post_meta( $post->ID, '_sk_store_email', true );

		?>
		<p>
			<label for="sk_store_address"><strong><?php esc_html_e( 'Dirección', 'skincare' ); ?></strong></label><br>
			<input type="text" id="sk_store_address" name="sk_store_address" value="<?php echo esc_attr( $address ); ?>" class="widefat">
		</p>
		<p>
			<label for="sk_store_city"><strong><?php esc_html_e( 'Ciudad', 'skincare' ); ?></strong></label><br>
			<input type="text" id="sk_store_city" name="sk_store_city" value="<?php echo esc_attr( $city ); ?>" class="widefat">
		</p>
		<div style="display: flex; gap: 10px;">
			<p style="flex: 1;">
				<label for="sk_store_lat"><strong><?php esc_html_e( 'Latitud', 'skincare' ); ?></strong></label><br>
				<input type="text" id="sk_store_lat" name="sk_store_lat" value="<?php echo esc_attr( $lat ); ?>" class="widefat">
			</p>
			<p style="flex: 1;">
				<label for="sk_store_lng"><strong><?php esc_html_e( 'Longitud', 'skincare' ); ?></strong></label><br>
				<input type="text" id="sk_store_lng" name="sk_store_lng" value="<?php echo esc_attr( $lng ); ?>" class="widefat">
			</p>
		</div>
		<p>
			<label for="sk_store_phone"><strong><?php esc_html_e( 'Teléfono', 'skincare' ); ?></strong></label><br>
			<input type="text" id="sk_store_phone" name="sk_store_phone" value="<?php echo esc_attr( $phone ); ?>" class="widefat">
		</p>
		<p>
			<label for="sk_store_email"><strong><?php esc_html_e( 'Email', 'skincare' ); ?></strong></label><br>
			<input type="text" id="sk_store_email" name="sk_store_email" value="<?php echo esc_attr( $email ); ?>" class="widefat">
		</p>
		<p>
			<label for="sk_store_hours"><strong><?php esc_html_e( 'Horario (Texto libre)', 'skincare' ); ?></strong></label><br>
			<textarea id="sk_store_hours" name="sk_store_hours" class="widefat" rows="4"><?php echo esc_textarea( $hours ); ?></textarea>
		</p>
		<?php
	}

	public static function save_meta_boxes( $post_id ) {
		if ( ! isset( $_POST['sk_store_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['sk_store_meta_box_nonce'], 'sk_store_meta_box' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = [
			'sk_store_address',
			'sk_store_city',
			'sk_store_lat',
			'sk_store_lng',
			'sk_store_phone',
			'sk_store_email',
			'sk_store_hours'
		];

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) ); // Or sanitize_textarea_field for hours
			}
		}
		// Special handling for hours if it needs html or line breaks
		if ( isset( $_POST['sk_store_hours'] ) ) {
			update_post_meta( $post_id, '_sk_store_hours', sanitize_textarea_field( $_POST['sk_store_hours'] ) );
		}
	}

	public static function register_api_endpoints() {
		register_rest_route( 'skincare/v1', '/stores', [
			'methods' => 'GET',
			'callback' => [ __CLASS__, 'get_stores' ],
			'permission_callback' => '__return_true',
		] );
	}

	public static function get_stores( $request ) {
		$args = [
			'post_type' => 'sk_store',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		];

		// Simple search by query
		$search = $request->get_param( 'search' );
		if ( $search ) {
			$args['s'] = $search;
		}

		$query = new \WP_Query( $args );
		$stores = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();
				$stores[] = [
					'id' => $id,
					'title' => get_the_title(),
					'content' => get_the_content(), // Description
					'address' => get_post_meta( $id, '_sk_store_address', true ),
					'city' => get_post_meta( $id, '_sk_store_city', true ),
					'lat' => get_post_meta( $id, '_sk_store_lat', true ),
					'lng' => get_post_meta( $id, '_sk_store_lng', true ),
					'phone' => get_post_meta( $id, '_sk_store_phone', true ),
					'email' => get_post_meta( $id, '_sk_store_email', true ),
					'hours' => get_post_meta( $id, '_sk_store_hours', true ),
					'image' => get_the_post_thumbnail_url( $id, 'medium' ) ?: '',
				];
			}
			wp_reset_postdata();
		}

		return rest_ensure_response( $stores );
	}
}
