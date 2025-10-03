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
	 * Product Manager instance
	 *
	 * @var Product_Manager
	 */
	private Product_Manager $product_manager;

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
		$this->product_manager = new Product_Manager();
		$this->cart_handler    = new Cart_Handler();
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes
	 */
	public function register_routes(): void {
		// GET /tiers.
		register_rest_route(
			self::NAMESPACE,
			'/tiers',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_tiers' ),
				'permission_callback' => '__return_true',
			)
		);

		// GET /categories.
		register_rest_route(
			self::NAMESPACE,
			'/categories',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_categories' ),
				'permission_callback' => '__return_true',
			)
		);

		// GET /products.
		register_rest_route(
			self::NAMESPACE,
			'/products',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_products' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'category'      => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => function ( $param ) {
							return in_array( $param, array( 'bag-common', 'bag-toys', 'bag-addons' ), true );
						},
					),
					'in_stock_only' => array(
						'required'          => false,
						'type'              => 'boolean',
						'default'           => true,
						'sanitize_callback' => 'rest_sanitize_boolean',
					),
				),
			)
		);

		// GET /tag-styles.
		register_rest_route(
			self::NAMESPACE,
			'/tag-styles',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_tag_styles' ),
				'permission_callback' => '__return_true',
			)
		);

		// POST /validate-stock.
		register_rest_route(
			self::NAMESPACE,
			'/validate-stock',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'validate_stock' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'kid_count'    => array(
						'required'          => true,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => function ( $param ) {
							return $param >= 1 && $param <= 50;
						},
					),
					'common_items' => array(
						'required'          => false,
						'type'              => 'array',
						'default'           => array(),
						'sanitize_callback' => function ( $param ) {
							return array_map( 'absint', (array) $param );
						},
					),
					'toys'         => array(
						'required'          => false,
						'type'              => 'array',
						'default'           => array(),
						'sanitize_callback' => function ( $param ) {
							return array_map( 'absint', (array) $param );
						},
					),
					'addons'       => array(
						'required'          => false,
						'type'              => 'array',
						'default'           => array(),
						'sanitize_callback' => function ( $param ) {
							return array_map( 'absint', (array) $param );
						},
					),
				),
			)
		);

		// POST /add-to-cart.
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
	 * Get tiers endpoint
	 *
	 * @return WP_REST_Response
	 */
	public function get_tiers(): WP_REST_Response {
		$tiers = require PBB_PLUGIN_DIR . 'includes/config-tiers.php';

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => array_values( $tiers ),
			),
			200
		);
	}

	/**
	 * Get categories endpoint
	 *
	 * @return WP_REST_Response
	 */
	public function get_categories(): WP_REST_Response {
		$categories = require PBB_PLUGIN_DIR . 'includes/config-categories.php';
		$result     = array();

		foreach ( $categories as $category ) {
			$category['product_count'] = $this->product_manager->count_products_in_category( $category['slug'] );
			$result[]                  = $category;
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $result,
			),
			200
		);
	}

	/**
	 * Get products endpoint
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function get_products( WP_REST_Request $request ): WP_REST_Response {
		$category      = $request->get_param( 'category' );
		$in_stock_only = $request->get_param( 'in_stock_only' );

		$products = $this->product_manager->get_products_by_category( $category, $in_stock_only );

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $products,
			),
			200
		);
	}

	/**
	 * Get tag styles endpoint
	 *
	 * @return WP_REST_Response
	 */
	public function get_tag_styles(): WP_REST_Response {
		$tag_styles = require PBB_PLUGIN_DIR . 'includes/config-tag-styles.php';
		$result     = array();

		foreach ( $tag_styles as $style ) {
			$style['preview_url'] = PBB_PLUGIN_URL . 'assets/images/tag-examples/' . $style['preview_image'];
			unset( $style['preview_image'] );
			$result[] = $style;
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => array_values( $result ),
			),
			200
		);
	}

	/**
	 * Validate stock endpoint
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function validate_stock( WP_REST_Request $request ): WP_REST_Response {
		$kid_count    = $request->get_param( 'kid_count' );
		$common_items = $request->get_param( 'common_items' );
		$toys         = $request->get_param( 'toys' );
		$addons       = $request->get_param( 'addons' );

		// Flatten all items into single array with quantities.
		$items = array();

		foreach ( $common_items as $product_id ) {
			$items[] = array(
				'id'  => $product_id,
				'qty' => $kid_count,
			);
		}

		foreach ( $toys as $product_id ) {
			$items[] = array(
				'id'  => $product_id,
				'qty' => $kid_count,
			);
		}

		foreach ( $addons as $product_id ) {
			$items[] = array(
				'id'  => $product_id,
				'qty' => $kid_count,
			);
		}

		$validation = $this->product_manager->validate_stock( $items );

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $validation,
			),
			200
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
