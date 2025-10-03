<?php
/**
 * Cart Handler class
 *
 * Handles adding party bags to cart, price calculation, and cart display.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

use Exception;
use WC_Cart;
use function WC;

defined( 'ABSPATH' ) || exit;

/**
 * Cart_Handler class.
 */
final class Cart_Handler {
	/**
	 * Product Manager instance.
	 *
	 * @var Product_Manager
	 */
	private Product_Manager $product_manager;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->product_manager = new Product_Manager();
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks(): void {
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_cart_item_data' ), 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'override_cart_price' ) );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'display_cart_item_price' ), 10, 2 );
	}

	/**
	 * Add party bag to cart.
	 *
	 * @param array $party_bag_data Party bag configuration data.
	 *
	 * @return array Success/error response with cart details.
	 */
	public function add_party_bag_to_cart( array $party_bag_data ): array {
		// Validate stock one final time.
		$stock_validation = $this->validate_final_stock( $party_bag_data );

		if ( ! $stock_validation['valid'] ) {
			return array(
				'success' => false,
				'message' => __( 'Insufficient stock', 'party-bag-builder' ),
				'errors'  => $stock_validation['errors'],
			);
		}

		// Calculate total price.
		$price_breakdown = $this->calculate_total_price( $party_bag_data );

		// Add price breakdown to party bag data.
		$party_bag_data['price_breakdown'] = $price_breakdown;

		// Get builder product ID.
		$builder_product_id = $this->product_manager->get_builder_product_id();

		if ( ! $builder_product_id ) {
			return array(
				'success' => false,
				'message' => __( 'Builder product not found', 'party-bag-builder' ),
				'errors'  => array(),
			);
		}

		// Generate unique cart key.
		$cart_item_data = array(
			'party_bag_data' => $party_bag_data,
			'unique_key'     => md5( wp_json_encode( $party_bag_data ) ),
		);

		// Add to cart.
		try {
			$cart_item_key = WC()->cart->add_to_cart(
				$builder_product_id,
				1,
				0,
				array(),
				$cart_item_data
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => __( 'Failed to add to cart', 'party-bag-builder' ),
				'errors'  => array(
					$e->getMessage(),
				),
			);
		}

		if ( ! $cart_item_key ) {
			return array(
				'success' => false,
				'message' => __( 'Failed to add to cart', 'party-bag-builder' ),
				'errors'  => array(),
			);
		}

		return array(
			'success'       => true,
			'message'       => __( 'Party bag added to cart successfully!', 'party-bag-builder' ),
			'cart_url'      => wc_get_cart_url(),
			'cart_item_key' => $cart_item_key,
		);
	}

	/**
	 * Validate stock for all items.
	 *
	 * @param array $party_bag_data Party bag configuration data.
	 *
	 * @return array Validation result.
	 */
	private function validate_final_stock( array $party_bag_data ): array {
		$items     = array();
		$kid_count = absint( $party_bag_data['kid_count'] );

		// Flatten common items.
		if ( ! empty( $party_bag_data['common_items'] ) ) {
			foreach ( $party_bag_data['common_items'] as $item ) {
				$items[] = array(
					'id'  => absint( $item['id'] ),
					'qty' => $kid_count,
				);
			}
		}

		// Flatten selected toys.
		if ( ! empty( $party_bag_data['selected_toys'] ) ) {
			foreach ( $party_bag_data['selected_toys'] as $item ) {
				$items[] = array(
					'id'  => absint( $item['id'] ),
					'qty' => $kid_count,
				);
			}
		}

		// Flatten selected addons.
		if ( ! empty( $party_bag_data['selected_addons'] ) ) {
			foreach ( $party_bag_data['selected_addons'] as $item ) {
				$items[] = array(
					'id'  => absint( $item['id'] ),
					'qty' => $kid_count,
				);
			}
		}

		return $this->product_manager->validate_stock( $items );
	}

	/**
	 * Calculate total price for party bag.
	 *
	 * @param array $party_bag_data Party bag configuration data.
	 *
	 * @return array Price breakdown with base, addons, and total.
	 */
	public function calculate_total_price( array $party_bag_data ): array {
		$kid_count       = absint( $party_bag_data['kid_count'] );
		$tier_base_price = floatval( $party_bag_data['tier_base_price'] );

		// Calculate base price.
		$base = $tier_base_price * $kid_count;

		// Get tier configuration for free addon count.
		$config = require PBB_PLUGIN_DIR . 'includes/config.php';
		$tiers  = $config['tiers'];
		$tier   = $party_bag_data['tier'] ?? '';

		$free_addon_count = 0;
		if ( isset( $tiers[ $tier ]['includes']['free_addons'] ) ) {
			$free_addon_count = absint( $tiers[ $tier ]['includes']['free_addons'] );
		}

		// Calculate addon pricing.
		$addon_total     = 0;
		$free_addons     = array();
		$paid_addons     = array();
		$selected_addons = $party_bag_data['selected_addons'] ?? array();

		if ( ! empty( $selected_addons ) ) {
			// Sort addons by price (ascending) - cheapest marked free first.
			usort(
				$selected_addons,
				function ( $a, $b ) {
					return floatval( $a['price'] ) <=> floatval( $b['price'] );
				}
			);

			foreach ( $selected_addons as $index => $addon ) {
				$addon_id    = absint( $addon['id'] );
				$addon_price = floatval( $addon['price'] );

				if ( $index < $free_addon_count ) {
					// This addon is free.
					$free_addons[] = $addon_id;
				} else {
					// This addon is paid.
					$addon_total  += $addon_price * $kid_count;
					$paid_addons[] = $addon_id;
				}
			}
		}

		return array(
			'base'        => $base,
			'addons'      => $addon_total,
			'total'       => $base + $addon_total,
			'free_addons' => $free_addons,
			'paid_addons' => $paid_addons,
		);
	}

	/**
	 * Display cart item data.
	 *
	 * @param array $item_data      Item data to display.
	 * @param array $cart_item_data Cart item data.
	 *
	 * @return array Modified item data.
	 */
	public function display_cart_item_data( array $item_data, array $cart_item_data ): array {
		if ( empty( $cart_item_data['party_bag_data'] ) ) {
			return $item_data;
		}

		$party_bag_data = $cart_item_data['party_bag_data'];

		// Add tier info.
		$item_data[] = array(
			'key'   => __( 'Tier', 'party-bag-builder' ),
			'value' => ucfirst( $party_bag_data['tier'] ?? '' ),
		);

		// Add kid count.
		$item_data[] = array(
			'key'   => __( 'Number of Kids', 'party-bag-builder' ),
			'value' => absint( $party_bag_data['kid_count'] ),
		);

		// Add selected toys.
		if ( ! empty( $party_bag_data['selected_toys'] ) ) {
			$toy_names = array_map(
				fn( $toy ) => esc_html( $toy['name'] ),
				$party_bag_data['selected_toys']
			);

			$item_data[] = array(
				'key'   => __( 'Toys', 'party-bag-builder' ),
				'value' => implode( ', ', $toy_names ),
			);
		}

		// Add selected addons.
		if ( ! empty( $party_bag_data['selected_addons'] ) ) {
			$addon_items = array();
			$free_addons = $party_bag_data['price_breakdown']['free_addons'] ?? array();

			foreach ( $party_bag_data['selected_addons'] as $addon ) {
				$addon_id   = absint( $addon['id'] );
				$addon_name = esc_html( $addon['name'] );

				if ( in_array( $addon_id, $free_addons, true ) ) {
					$addon_items[] = $addon_name . ' ' . __( '(FREE)', 'party-bag-builder' );
				} else {
					$addon_items[] = $addon_name;
				}
			}

			$item_data[] = array(
				'key'   => __( 'Add-ons', 'party-bag-builder' ),
				'value' => implode( ', ', $addon_items ),
			);
		}

		// Add names preview (first 3).
		if ( ! empty( $party_bag_data['kid_names'] ) ) {
			$names         = array_map( 'esc_html', $party_bag_data['kid_names'] );
			$names_count   = count( $names );
			$preview_names = array_slice( $names, 0, 3 );

			$names_display = implode( ', ', $preview_names );
			if ( $names_count > 3 ) {
				/* translators: %d: number of additional names */
				$names_display .= sprintf( __( ' +%d more', 'party-bag-builder' ), $names_count - 3 );
			}

			$item_data[] = array(
				'key'   => __( 'Names', 'party-bag-builder' ),
				'value' => $names_display,
			);
		}

		return $item_data;
	}

	/**
	 * Override cart price.
	 *
	 * @param WC_Cart $cart Cart object.
	 */
	public function override_cart_price( WC_Cart $cart ): void {
		foreach ( $cart->get_cart() as $cart_item ) {
			if ( empty( $cart_item['party_bag_data'] ) ) {
				continue;
			}

			$party_bag_data = $cart_item['party_bag_data'];

			// Get total price from price breakdown.
			if ( isset( $party_bag_data['price_breakdown']['total'] ) ) {
				$total = floatval( $party_bag_data['price_breakdown']['total'] );
				$cart_item['data']->set_price( $total );
			}
		}
	}

	/**
	 * Display cart item price.
	 *
	 * @param string $price     Price HTML.
	 * @param array  $cart_item Cart item data.
	 *
	 * @return string Modified price HTML.
	 */
	public function display_cart_item_price( string $price, array $cart_item ): string {
		if ( empty( $cart_item['party_bag_data'] ) ) {
			return $price;
		}

		$party_bag_data = $cart_item['party_bag_data'];

		if ( ! isset( $party_bag_data['price_breakdown'] ) ) {
			return $price;
		}

		$breakdown = $party_bag_data['price_breakdown'];

		// Build price breakdown tooltip.
		$tooltip  = '<div class="pbb-price-breakdown">';
		$tooltip .= '<strong>' . esc_html__( 'Price Breakdown:', 'party-bag-builder' ) . '</strong><br>';

		$tooltip .= sprintf(
			/* translators: 1: tier name, 2: kid count, 3: base price */
			esc_html__( '%1$s tier × %2$d kids: %3$s', 'party-bag-builder' ),
			esc_html( ucfirst( $party_bag_data['tier'] ?? '' ) ),
			absint( $party_bag_data['kid_count'] ),
			wc_price( $breakdown['base'] )
		) . '<br>';

		if ( $breakdown['addons'] > 0 ) {
			$tooltip .= sprintf(
				/* translators: %s: addon price */
				esc_html__( 'Add-ons: %s', 'party-bag-builder' ),
				wc_price( $breakdown['addons'] )
			) . '<br>';
		}

		$tooltip .= '</div>';

		return $price . ' <span class="pbb-price-info" title="' . esc_attr( wp_strip_all_tags( $tooltip ) ) . '">ℹ️</span>';
	}
}
