<?php
/**
 * REST API class
 *
 * Handles all REST API endpoints for the party bag builder.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Rest_API class.
 */
final class Rest_API {
	/**
	 * API namespace
	 */
	private const string NAMESPACE = 'bag-builder/v1';

	/**
	 * Cart Handler instance
	 *
	 * @var Cart_Handler
	 */
	private Cart_Handler $cart_handler;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->cart_handler = new Cart_Handler();
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes
	 */
	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/add-to-cart',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'add_to_cart' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'party_bag_data' => array(
						'required' => true,
						'type'     => 'object',
					),
				),
			)
		);
	}

	/**
	 * Add to cart endpoint
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function add_to_cart( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$party_bag_data = $request->get_param( 'party_bag_data' );

		// Validate required data.
		if ( empty( $party_bag_data ) || ! is_array( $party_bag_data ) ) {
			return new WP_Error(
				'invalid_data',
				__( 'Invalid party bag data', 'party-bag-builder' ),
				array( 'status' => 400 )
			);
		}

		// Add to cart using Cart Handler.
		$result = $this->cart_handler->add_party_bag_to_cart( $party_bag_data );

		if ( ! $result['success'] ) {
			return new WP_REST_Response(
				$result,
				400
			);
		}

		return new WP_REST_Response(
			$result,
			200
		);
	}
}
