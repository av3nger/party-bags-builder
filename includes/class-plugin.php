<?php
/**
 * Main plugin class
 *
 * @package PartyBagBuilder
 */

namespace PBB;

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin class.
 */
final class Plugin {
	/**
	 * The single instance of the class.
	 *
	 * @var null|Plugin $instance
	 */
	private static ?Plugin $instance = null;

	/**
	 * Product Manager instance.
	 *
	 * @var null|Product_Manager $product_manager
	 */
	public ?Product_Manager $product_manager = null;

	/**
	 * REST API instance.
	 *
	 * @var null|Rest_API $rest_api
	 */
	public ?Rest_API $rest_api = null;

	/**
	 * Cart Handler instance.
	 *
	 * @var null|Cart_Handler $cart_handler
	 */
	public ?Cart_Handler $cart_handler = null;

	/**
	 * Order Handler instance.
	 *
	 * @var null|Order_Handler $order_handler
	 */
	public ?Order_Handler $order_handler = null;

	/**
	 * Blocks instance.
	 *
	 * @var null|Blocks $blocks
	 */
	public ?Blocks $blocks = null;

	/**
	 * Main Plugin instance.
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor
	 */
	private function __construct() {
		$this->init_hooks();
		$this->init_classes();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks(): void {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'before_woocommerce_init', array( $this, 'declare_wc_compatibility' ) );
	}

	/**
	 * Initialize plugin classes.
	 */
	private function init_classes(): void {
		$this->product_manager = new Product_Manager();
		$this->cart_handler    = new Cart_Handler();
		$this->rest_api        = new Rest_API( $this->cart_handler );
		$this->order_handler   = new Order_Handler();
		$this->blocks          = new Blocks();
	}

	/**
	 * Load plugin text domain.
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain(
			'party-bag-builder',
			false,
			dirname( PBB_PLUGIN_BASENAME ) . '/languages'
		);
	}

	/**
	 * Declare WooCommerce compatibility.
	 *
	 * Declares support for WooCommerce HPOS and Cart/Checkout Blocks.
	 */
	public function declare_wc_compatibility(): void {
		if ( ! class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			return;
		}

		// Declare HPOS compatibility.
		FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			PBB_PLUGIN_FILE
		);

		// Declare Cart and Checkout Blocks compatibility.
		FeaturesUtil::declare_compatibility(
			'cart_checkout_blocks',
			PBB_PLUGIN_FILE
		);
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 *
	 * @throws Exception When attempting to unserialize singleton.
	 */
	public function __wakeup(): void {
		throw new Exception( 'Cannot unserialize singleton' );
	}
}
