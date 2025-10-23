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
			if ( ! WC()->cart ) {
				wc_load_cart();
			}

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
	 * @return array Price breakdown with base, addons, name tags, and total.
	 */
	public function calculate_total_price( array $party_bag_data ): array {
		// Get tier configuration for free addon count.
		$config = require PBB_PLUGIN_DIR . 'includes/config.php';
		$tiers  = $config['tiers'];
		$tier   = $party_bag_data['tier'] ?? '';

		$kid_count       = absint( $party_bag_data['kid_count'] );
		$tier_base_price = floatval( $config['tiers'][ $tier ]['base_price'] );

		// Calculate base price.
		$base = $tier_base_price * $kid_count;

		// Check if name tags are selected.
		$tag_style     = $party_bag_data['tag_style'] ?? null;
		$has_name_tags = ! empty( $tag_style );

		// Calculate addon pricing.
		$addon_total    = 0;
		$name_tag_total = 0;

		// Name tags are free on premium tier, $2.50 per kid on basic/medium.
		if ( $has_name_tags && 'premium' !== $tier ) {
			$name_tag_total = 2.50 * $kid_count;
		}

		// All addons are paid - no free addon slots.
		$selected_addons = $party_bag_data['selected_addons'] ?? array();
		if ( ! empty( $selected_addons ) ) {
			foreach ( $selected_addons as $addon ) {
				$product = wc_get_product( absint( $addon['id'] ) );
				if ( $product ) {
					$addon_price  = floatval( $product->get_price() );
					$addon_total += $addon_price * $kid_count;
				}
			}
		}

		return array(
			'base'      => $base,
			'addons'    => $addon_total,
			'name_tags' => $name_tag_total,
			'total'     => $base + $addon_total + $name_tag_total,
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
			$addon_names = array_map(
				fn( $addon ) => esc_html( $addon['name'] ),
				$party_bag_data['selected_addons']
			);

			$item_data[] = array(
				'key'   => __( 'Add-ons', 'party-bag-builder' ),
				'value' => implode( ', ', $addon_names ),
			);
		}

		// Add name tags if selected.
		if ( ! empty( $party_bag_data['tag_style'] ) ) {
			$config         = require PBB_PLUGIN_DIR . 'includes/config.php';
			$tag_styles     = $config['tag_styles'] ?? array();
			$tag_style      = $party_bag_data['tag_style'];
			$tag_style_name = '';

			// Find the tag style name.
			foreach ( $tag_styles as $style ) {
				if ( $style['id'] === $tag_style ) {
					$tag_style_name = $style['name'];
					break;
				}
			}

			// Name tags are free on premium tier (when name_tag_total is 0).
			$name_tag_cost = $party_bag_data['price_breakdown']['name_tags'] ?? 0;
			$is_free       = 0.0 === floatval( $name_tag_cost );
			$tag_display   = $tag_style_name;

			if ( $is_free ) {
				$tag_display .= ' ' . __( '(FREE)', 'party-bag-builder' );
			}

			$item_data[] = array(
				'key'   => __( 'Name Tags', 'party-bag-builder' ),
				'value' => $tag_display,
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

		if ( isset( $breakdown['name_tags'] ) && $breakdown['name_tags'] > 0 ) {
			$tooltip .= sprintf(
				/* translators: %s: name tag price */
				esc_html__( 'Name Tags: %s', 'party-bag-builder' ),
				wc_price( $breakdown['name_tags'] )
			) . '<br>';
		}

		$tooltip .= '</div>';

		return $price . ' <span class="pbb-price-info" title="' . esc_attr( wp_strip_all_tags( $tooltip ) ) . '">ℹ️</span>';
	}
}
