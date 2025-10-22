<?php
/**
 * Order Handler class
 *
 * Handles order item meta, stock deduction/restoration, and order display.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

use WC_Order_Item_Product;

defined( 'ABSPATH' ) || exit;

/**
 * Order_Handler class.
 */
final class Order_Handler {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks(): void {
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_order_item_meta' ), 10, 3 );
		add_action( 'woocommerce_order_item_meta_end', array( $this, 'display_order_meta' ), 10, 2 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'reduce_component_stock' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'reduce_component_stock' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'restore_component_stock' ) );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'restore_component_stock' ) );
	}

	/**
	 * Save order item meta.
	 *
	 * @param WC_Order_Item_Product $item          Order item.
	 * @param string                $cart_item_key Cart item key.
	 * @param array                 $values        Cart item values.
	 */
	public function save_order_item_meta( WC_Order_Item_Product $item, string $cart_item_key, array $values ): void {
		if ( empty( $values['party_bag_data'] ) ) {
			return;
		}

		$item->add_meta_data( '_party_bag_data', $values['party_bag_data'], true );
	}

	/**
	 * Display order meta.
	 *
	 * @param int                   $item_id Order item ID.
	 * @param WC_Order_Item_Product $item    Order item.
	 */
	public function display_order_meta( int $item_id, WC_Order_Item_Product $item ): void {
		$party_bag_data = $item->get_meta( '_party_bag_data' );

		if ( empty( $party_bag_data ) ) {
			return;
		}

		// Load template.
		$template_path = PBB_PLUGIN_DIR . 'includes/templates/admin/order-meta-display.php';

		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
	}

	/**
	 * Reduce component stock for party bag items.
	 *
	 * @param int $order_id Order ID.
	 */
	private function reduce_component_stock( int $order_id ): void {
		// Check if stock already reduced.
		$already_reduced = get_post_meta( $order_id, '_pbb_stock_reduced', true );

		if ( $already_reduced ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		$stock_reduced = false;

		foreach ( $order->get_items() as $item ) {
			$party_bag_data = $item->get_meta( '_party_bag_data' );

			if ( empty( $party_bag_data ) ) {
				continue;
			}

			$kid_count = absint( $party_bag_data['kid_count'] );

			// Reduce stock for common items.
			if ( ! empty( $party_bag_data['common_items'] ) ) {
				foreach ( $party_bag_data['common_items'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'decrease' );
				}
			}

			// Reduce stock for selected toys.
			if ( ! empty( $party_bag_data['selected_toys'] ) ) {
				foreach ( $party_bag_data['selected_toys'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'decrease' );
				}
			}

			// Reduce stock for selected addons.
			if ( ! empty( $party_bag_data['selected_addons'] ) ) {
				foreach ( $party_bag_data['selected_addons'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'decrease' );
				}
			}

			$stock_reduced = true;
		}

		if ( $stock_reduced ) {
			update_post_meta( $order_id, '_pbb_stock_reduced', true );
			$order->add_order_note( __( 'Party bag component stock reduced.', 'party-bag-builder' ) );
		}
	}

	/**
	 * Restore component stock for party bag items.
	 *
	 * @param int $order_id Order ID.
	 */
	private function restore_component_stock( int $order_id ): void {
		// Check if stock was reduced.
		$stock_reduced = get_post_meta( $order_id, '_pbb_stock_reduced', true );

		if ( ! $stock_reduced ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		$stock_restored = false;

		foreach ( $order->get_items() as $item ) {
			$party_bag_data = $item->get_meta( '_party_bag_data' );

			if ( empty( $party_bag_data ) ) {
				continue;
			}

			$kid_count = absint( $party_bag_data['kid_count'] );

			// Restore stock for common items.
			if ( ! empty( $party_bag_data['common_items'] ) ) {
				foreach ( $party_bag_data['common_items'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'increase' );
				}
			}

			// Restore stock for selected toys.
			if ( ! empty( $party_bag_data['selected_toys'] ) ) {
				foreach ( $party_bag_data['selected_toys'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'increase' );
				}
			}

			// Restore stock for selected addons.
			if ( ! empty( $party_bag_data['selected_addons'] ) ) {
				foreach ( $party_bag_data['selected_addons'] as $product_data ) {
					wc_update_product_stock( absint( $product_data['id'] ), $kid_count, 'increase' );
				}
			}

			$stock_restored = true;
		}

		if ( $stock_restored ) {
			delete_post_meta( $order_id, '_pbb_stock_reduced' );
			$order->add_order_note( __( 'Party bag component stock restored.', 'party-bag-builder' ) );
		}
	}
}
