<?php
namespace Skincare\SiteKit\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rest_Controller {
	public static function init() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
	}

	public static function register_routes() {
		register_rest_route(
			'skincare/v1',
			'/rewards',
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'get_rewards' ],
				'permission_callback' => [ __CLASS__, 'require_logged_in_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/rewards/redeem',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'redeem_rewards' ],
				'permission_callback' => [ __CLASS__, 'require_logged_in_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/forms/contact',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'submit_contact' ],
				'permission_callback' => [ __CLASS__, 'require_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/quote',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'submit_quote' ],
				'permission_callback' => [ __CLASS__, 'require_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/cart/coupon',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'apply_coupon' ],
				'permission_callback' => [ __CLASS__, 'require_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/wishlist/add',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'wishlist_add' ],
				'permission_callback' => [ __CLASS__, 'require_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/stock-notify',
			[
				'methods' => \WP_REST_Server::CREATABLE,
				'callback' => [ __CLASS__, 'stock_notify' ],
				'permission_callback' => [ __CLASS__, 'require_nonce' ],
			]
		);

		register_rest_route(
			'skincare/v1',
			'/search',
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [ __CLASS__, 'search_products' ],
				'permission_callback' => '__return_true', // Public search
			]
		);
	}

	public static function require_nonce( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce ) {
			$nonce = $request->get_param( 'nonce' );
		}
		return (bool) $nonce && wp_verify_nonce( $nonce, 'wp_rest' );
	}

	public static function require_logged_in_nonce( $request ) {
		return is_user_logged_in() && self::require_nonce( $request );
	}

	public static function get_rewards( $request ) {
		if ( ! class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			return new \WP_Error( 'sk_rewards_missing', __( 'Rewards system unavailable.', 'skincare' ), [ 'status' => 500 ] );
		}

		$user_id = get_current_user_id();
		$rules = \Skincare\SiteKit\Admin\Rewards_Master::get_rules();

		return rest_ensure_response(
			[
				'points' => \Skincare\SiteKit\Admin\Rewards_Master::get_user_balance( $user_id ),
				'history' => \Skincare\SiteKit\Admin\Rewards_Master::get_user_history( $user_id, 20 ),
				'redeem_points' => $rules['redeem_points'],
				'redeem_amount' => $rules['redeem_amount'],
			]
		);
	}

	public static function redeem_rewards( $request ) {
		if ( ! class_exists( '\Skincare\SiteKit\Admin\Rewards_Master' ) ) {
			return new \WP_Error( 'sk_rewards_missing', __( 'Rewards system unavailable.', 'skincare' ), [ 'status' => 500 ] );
		}

		$user_id = get_current_user_id();
		$result = \Skincare\SiteKit\Admin\Rewards_Master::redeem_points_for_user( $user_id );
		if ( is_wp_error( $result ) ) {
			return new \WP_Error( $result->get_error_code(), $result->get_error_message(), [ 'status' => 400 ] );
		}

		return rest_ensure_response( $result );
	}

	public static function apply_coupon( $request ) {
		$params = self::get_request_params( $request );
		$code = isset( $params['coupon_code'] ) ? sanitize_text_field( $params['coupon_code'] ) : '';

		if ( ! $code ) {
			return new \WP_Error( 'sk_invalid_coupon', __( 'Código de cupón vacío.', 'skincare' ), [ 'status' => 400 ] );
		}

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return new \WP_Error( 'sk_wc_missing', __( 'WooCommerce cart not available.', 'skincare' ), [ 'status' => 500 ] );
		}

		if ( WC()->cart->apply_coupon( $code ) ) {
			return rest_ensure_response( [ 'success' => true, 'message' => __( 'Cupón aplicado.', 'skincare' ) ] );
		} else {
			return new \WP_Error( 'sk_invalid_coupon', __( 'Cupón inválido.', 'skincare' ), [ 'status' => 400 ] );
		}
	}

	public static function submit_contact( $request ) {
		$params = self::get_request_params( $request );
		$name = sanitize_text_field( $params['name'] ?? '' );
		$email = sanitize_email( $params['email'] ?? '' );
		$contact = sanitize_text_field( $params['contact'] ?? ( $params['phone'] ?? '' ) );
		$message = sanitize_textarea_field( $params['message'] ?? '' );

		if ( ( ! $email && ! $contact ) || ! $message ) {
			return new \WP_Error( 'sk_contact_invalid', __( 'Por favor completa los campos requeridos.', 'skincare' ), [ 'status' => 400 ] );
		}

		$to = get_option( 'admin_email' );
		$subject = 'Nuevo Mensaje de Contacto - ' . get_bloginfo( 'name' );
		$body = "Nombre: $name\nEmail: $email\nContacto: $contact\n\nMensaje:\n$message";
		$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
		if ( $email ) {
			$headers[] = 'Reply-To: ' . $email;
		}

		if ( wp_mail( $to, $subject, $body, $headers ) ) {
			return rest_ensure_response(
				[
					'success' => true,
					'message' => __( '¡Mensaje enviado correctamente! Te responderemos pronto.', 'skincare' ),
				]
			);
		}

		return new \WP_Error( 'sk_contact_failed', __( 'Hubo un error al enviar el mensaje.', 'skincare' ), [ 'status' => 500 ] );
	}

	public static function submit_quote( $request ) {
		$params = self::get_request_params( $request );
		$sanitized = [];
		foreach ( $params as $key => $value ) {
			$sanitized[ sanitize_key( $key ) ] = is_scalar( $value ) ? sanitize_text_field( $value ) : wp_json_encode( $value );
		}

		$email = isset( $sanitized['email'] ) ? sanitize_email( $sanitized['email'] ) : '';
		$name = $sanitized['name'] ?? '';
		$quote_type = $sanitized['quote_type'] ?? ( $sanitized['role'] ?? __( 'Quote', 'skincare' ) );

		if ( ! $email && empty( $sanitized['phone'] ) ) {
			return new \WP_Error( 'sk_quote_invalid', __( 'Por favor completa los campos requeridos.', 'skincare' ), [ 'status' => 400 ] );
		}

		$lines = [
			'Tipo: ' . $quote_type,
			'Nombre: ' . $name,
			'Email: ' . $email,
			'Teléfono: ' . ( $sanitized['phone'] ?? '' ),
			'Detalle:',
		];
		foreach ( $sanitized as $key => $value ) {
			if ( in_array( $key, [ 'name', 'email', 'phone', 'quote_type', 'nonce' ], true ) ) {
				continue;
			}
			$lines[] = sprintf( '%s: %s', ucfirst( str_replace( '_', ' ', $key ) ), $value );
		}

		$body = implode( "\n", $lines );
		$subject = sprintf( 'Nueva solicitud de cotización (%s) - %s', $quote_type, get_bloginfo( 'name' ) );
		$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];
		if ( $email ) {
			$headers[] = 'Reply-To: ' . $email;
		}

		if ( wp_mail( get_option( 'admin_email' ), $subject, $body, $headers ) ) {
			return rest_ensure_response(
				[
					'success' => true,
					'message' => __( 'Solicitud enviada correctamente.', 'skincare' ),
				]
			);
		}

		return new \WP_Error( 'sk_quote_failed', __( 'No se pudo enviar la solicitud.', 'skincare' ), [ 'status' => 500 ] );
	}

	public static function wishlist_add( $request ) {
		// Mock implementation - in production this would link to a real Wishlist module
		// Just returning success to satisfy the frontend requirement for now
		// unless \Skincare\SiteKit\Modules\Wishlist exists and has logic we can call.
		$params = self::get_request_params( $request );
		$product_id = isset( $params['product_id'] ) ? absint( $params['product_id'] ) : 0;
		if ( ! $product_id ) {
			return new \WP_Error( 'sk_invalid_product', __( 'Producto inválido.', 'skincare' ), [ 'status' => 400 ] );
		}

		// Simulate success
		return rest_ensure_response( [ 'success' => true, 'message' => __( 'Añadido a favoritos.', 'skincare' ) ] );
	}

	public static function stock_notify( $request ) {
		$params = self::get_request_params( $request );
		$email = isset( $params['email'] ) ? sanitize_email( $params['email'] ) : '';
		$product_id = isset( $params['product_id'] ) ? absint( $params['product_id'] ) : 0;

		if ( ! $email || ! $product_id ) {
			return new \WP_Error( 'sk_invalid_data', __( 'Datos incompletos.', 'skincare' ), [ 'status' => 400 ] );
		}

		// In a real implementation, save to DB. For now, simulate success.
		// Sending an email to admin as a fallback notification
		wp_mail(
			get_option( 'admin_email' ),
			'Stock Notification Request',
			"User $email wants to know when product #$product_id is back in stock."
		);

		return rest_ensure_response( [ 'success' => true, 'message' => __( 'Te avisaremos cuando esté disponible.', 'skincare' ) ] );
	}

	public static function search_products( $request ) {
		$term = $request->get_param( 'term' );
		if ( empty( $term ) ) {
			return rest_ensure_response( [] );
		}

		$args = [
			'post_type' => 'product',
			'post_status' => 'publish',
			's' => sanitize_text_field( $term ),
			'posts_per_page' => 5,
		];

		$query = new \WP_Query( $args );
		$results = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				global $product;
				$results[] = [
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'url' => get_permalink(),
					'price' => $product ? $product->get_price_html() : '',
					'image' => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '',
				];
			}
			wp_reset_postdata();
		}

		return rest_ensure_response( $results );
	}

	private static function get_request_params( $request ) {
		$params = $request->get_json_params();
		if ( empty( $params ) ) {
			$params = $request->get_body_params();
		}
		return is_array( $params ) ? $params : [];
	}
}
