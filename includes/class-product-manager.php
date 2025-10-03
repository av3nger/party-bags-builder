<?php
/**
 * Product manager class
 *
 * Handles product queries, formatting, and stock validation.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

defined( 'ABSPATH' ) || exit;

/**
 * Product_Manager class.
 */
final class Product_Manager {
	/**
	 * Validate stock for multiple items.
	 *
	 * @param array $items Array of items with 'id' and 'qty' keys.
	 *
	 * @return array Validation result with 'valid', 'errors', and 'items' keys.
	 */
	public function validate_stock( array $items ): array {
		$result = array(
			'valid'  => true,
			'errors' => array(),
			'items'  => array(),
		);

		foreach ( $items as $item ) {
			if ( ! isset( $item['id'], $item['qty'] ) ) {
				continue;
			}

			$product_id = absint( $item['id'] );
			$required   = absint( $item['qty'] );
			$product    = wc_get_product( $product_id );

			if ( ! $product ) {
				$result['valid']    = false;
				$result['errors'][] = sprintf(
					/* translators: %d: Product ID */
					__( 'Product ID %d not found', 'party-bag-builder' ),
					$product_id
				);
				continue;
			}

			$available  = $product->get_stock_quantity() ?? 0;
			$sufficient = $available >= $required;

			$result['items'][] = array(
				'id'         => $product_id,
				'name'       => $product->get_name(),
				'available'  => $available,
				'required'   => $required,
				'sufficient' => $sufficient,
			);

			if ( ! $sufficient ) {
				$result['valid'] = false;

				if ( 0 === $available ) {
					$result['errors'][] = sprintf(
						/* translators: %s: Product name */
						__( '%s: Out of stock', 'party-bag-builder' ),
						$product->get_name()
					);
				} else {
					$result['errors'][] = sprintf(
						/* translators: 1: Product name, 2: Available quantity, 3: Required quantity */
						__( '%1$s: Only %2$d available, need %3$d', 'party-bag-builder' ),
						$product->get_name(),
						$available,
						$required
					);
				}
			}
		}

		return $result;
	}

	/**
	 * Get builder product ID.
	 *
	 * Returns the ID of the hidden builder product used as cart container.
	 *
	 * @return int Product ID or 0 if not found.
	 */
	public function get_builder_product_id(): int {
		// Try to get from option first.
		$product_id = get_option( 'pbb_builder_product_id', 0 );

		if ( $product_id && get_post( $product_id ) ) {
			return absint( $product_id );
		}

		// Fallback: find by meta.
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'   => '_pbb_builder_product',
					'value' => 'yes',
				),
			),
		);

		$products = get_posts( $args );

		if ( ! empty( $products ) ) {
			$product_id = absint( $products[0] );
			update_option( 'pbb_builder_product_id', $product_id );
			return $product_id;
		}

		return 0;
	}
}
