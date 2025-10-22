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
	 * Product Manager instance
	 *
	 * @var Product_Manager
	 */
	private Product_Manager $product_manager;

	/**
	 * Constructor
	 *
	 * @param Cart_Handler    $cart_handler    Cart Handler instance.
	 * @param Product_Manager $product_manager Product Manager instance.
	 */
	public function __construct( Cart_Handler $cart_handler, Product_Manager $product_manager ) {
		$this->cart_handler    = $cart_handler;
		$this->product_manager = $product_manager;
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

		// Transform data structure for Cart Handler.
		$transformed_data = $this->transform_party_bag_data( $party_bag_data );

		// Add to cart using Cart Handler.
		$result = $this->cart_handler->add_party_bag_to_cart( $transformed_data );

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

	/**
	 * Transform party bag data from frontend format to backend format.
	 *
	 * @param array $data Raw party bag data from frontend.
	 *
	 * @return array Transformed data for Cart Handler.
	 */
	private function transform_party_bag_data( array $data ): array {
		// Get common items (automatically included in all bags).
		$common_items = $this->product_manager->get_products_by_category( 'bag-common' );

		// Transform toy IDs to product objects.
		$selected_toys = array();
		if ( ! empty( $data['toys'] ) && is_array( $data['toys'] ) ) {
			foreach ( $data['toys'] as $toy_id ) {
				$product = wc_get_product( absint( $toy_id ) );
				if ( $product ) {
					$selected_toys[] = $this->product_manager->format_product_for_api( $product );
				}
			}
		}

		// Transform addon IDs to product objects.
		$selected_addons = array();
		if ( ! empty( $data['addons'] ) && is_array( $data['addons'] ) ) {
			foreach ( $data['addons'] as $addon_id ) {
				$product = wc_get_product( absint( $addon_id ) );
				if ( $product ) {
					$selected_addons[] = $this->product_manager->format_product_for_api( $product );
				}
			}
		}

		// Build transformed data structure.
		return array(
			'kid_count'       => $data['kid_count'] ?? 1,
			'tier'            => $data['tier'] ?? '',
			'common_items'    => $common_items,
			'selected_toys'   => $selected_toys,
			'selected_addons' => $selected_addons,
			'tag_style'       => $data['tag_style'] ?? null,
			'kid_names'       => $data['kid_names'] ?? array(),
		);
	}
}
