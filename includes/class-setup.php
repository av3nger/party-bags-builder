<?php
/**
 * Setup class
 *
 * Handles plugin activation and deactivation.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

use WC_Data_Exception;
use WC_Product_Simple;

defined( 'ABSPATH' ) || exit;

/**
 * Setup class.
 */
final class Setup {
	/**
	 * Plugin activation.
	 *
	 * Creates categories, builder product, and optionally sample products.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	public static function activate(): void {
		self::create_categories();
		self::create_tags();
		self::create_builder_product();
		self::maybe_create_sample_products();

		flush_rewrite_rules();

		// Set activation flag.
		set_transient( 'pbb_activation_redirect', true, 30 );
	}

	/**
	 * Plugin deactivation.
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}

	/**
	 * Create product categories.
	 */
	private static function create_categories(): void {
		$config     = require PBB_PLUGIN_DIR . 'includes/config.php';
		$categories = $config['categories'];

		foreach ( $categories as $category ) {
			$existing_term = term_exists( $category['slug'], 'product_cat' );

			if ( ! $existing_term ) {
				wp_insert_term(
					$category['name'],
					'product_cat',
					array(
						'slug'        => $category['slug'],
						'description' => $category['description'],
					)
				);
			}
		}
	}

	/**
	 * Create product tags.
	 */
	private static function create_tags(): void {
		$config = require PBB_PLUGIN_DIR . 'includes/config.php';
		$tags   = $config['tags'];

		foreach ( $tags as $tag ) {
			$existing_term = term_exists( $tag['slug'], 'product_tag' );

			if ( ! $existing_term ) {
				wp_insert_term(
					$tag['name'],
					'product_tag',
					array(
						'slug'        => $tag['slug'],
						'description' => $tag['description'],
					)
				);
			}
		}

		// Also create theme tags.
		$themes = $config['themes'];

		foreach ( $themes as $theme ) {
			$existing_term = term_exists( $theme['slug'], 'product_tag' );

			if ( ! $existing_term ) {
				wp_insert_term(
					$theme['name'],
					'product_tag',
					array(
						'slug' => $theme['slug'],
					)
				);
			}
		}
	}

	/**
	 * Create hidden builder product.
	 *
	 * Creates a single hidden product that serves as the cart container.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_builder_product(): void {
		// Check if builder product already exists.
		$existing_product_id = get_option( 'pbb_builder_product_id', 0 );

		if ( $existing_product_id && get_post( $existing_product_id ) ) {
			return;
		}

		// Create product.
		$product = new WC_Product_Simple();
		$product->set_name( __( 'Custom Party Bag', 'party-bag-builder' ) );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'hidden' );
		$product->set_price( 0 );
		$product->set_regular_price( 0 );
		$product->set_virtual( true );
		$product->set_sold_individually( false );
		$product->set_manage_stock( false );

		// Save product.
		$product_id = $product->save();

		if ( $product_id ) {
			// Add meta to identify builder product.
			update_post_meta( $product_id, '_pbb_builder_product', 'yes' );

			// Exclude from search.
			update_post_meta( $product_id, '_visibility', 'hidden' );

			// Store product ID.
			update_option( 'pbb_builder_product_id', $product_id );
		}
	}

	/**
	 * Maybe create sample products.
	 *
	 * Creates sample products in each category if WP_DEBUG is enabled.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function maybe_create_sample_products(): void {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		// Check if sample products already exist.
		if ( get_option( 'pbb_sample_products_created', false ) ) {
			return;
		}

		self::create_sample_common_items();
		self::create_sample_bags();
		self::create_sample_toys();
		self::create_sample_addons();

		update_option( 'pbb_sample_products_created', true );
	}

	/**
	 * Create sample common items.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_sample_common_items(): void {
		$common_items = array(
			array(
				'name'  => 'Rainbow Lollipop',
				'price' => 0.50,
				'stock' => 100,
			),
			array(
				'name'  => 'Colorful Balloon',
				'price' => 0.75,
				'stock' => 150,
			),
			array(
				'name'  => 'Sticker Sheet',
				'price' => 0.25,
				'stock' => 200,
			),
		);

		$tag_id = self::get_tag_id( 'common' );

		foreach ( $common_items as $item ) {
			self::create_sample_product( $item['name'], $item['price'], 0, $item['stock'], array( $tag_id ) );
		}
	}

	/**
	 * Create sample bags.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_sample_bags(): void {
		$bags = array(
			array(
				'name'  => 'Animals Party Bag',
				'price' => 0,
				'stock' => 100,
				'theme' => 'animals',
			),
			array(
				'name'  => 'Space Party Bag',
				'price' => 0,
				'stock' => 100,
				'theme' => 'space',
			),
			array(
				'name'  => 'Unicorn Party Bag',
				'price' => 0,
				'stock' => 100,
				'theme' => 'unicorn',
			),
		);

		$category_id = self::get_category_id( 'bags' );

		foreach ( $bags as $bag ) {
			$theme_tag_id = self::get_tag_id( $bag['theme'] );
			self::create_sample_product( $bag['name'], $bag['price'], $category_id, $bag['stock'], array( $theme_tag_id ) );
		}
	}

	/**
	 * Create sample toys.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_sample_toys(): void {
		$toys = array(
			array(
				'name'  => 'Mini Toy Car - Animals',
				'price' => 1.50,
				'stock' => 50,
				'theme' => 'animals',
			),
			array(
				'name'  => 'Puzzle Cube - Space',
				'price' => 2.00,
				'stock' => 40,
				'theme' => 'space',
			),
			array(
				'name'  => 'Bouncy Ball - Unicorn',
				'price' => 1.00,
				'stock' => 75,
				'theme' => 'unicorn',
			),
			array(
				'name'  => 'Plastic Whistle - Animals',
				'price' => 0.75,
				'stock' => 60,
				'theme' => 'animals',
			),
			array(
				'name'  => 'Mini Yo-Yo - Space',
				'price' => 1.25,
				'stock' => 45,
				'theme' => 'space',
			),
		);

		$category_id = self::get_category_id( 'toys' );
		$toys_tag_id = self::get_tag_id( 'toys' );

		foreach ( $toys as $toy ) {
			$theme_tag_id = self::get_tag_id( $toy['theme'] );
			self::create_sample_product( $toy['name'], $toy['price'], $category_id, $toy['stock'], array( $toys_tag_id, $theme_tag_id ) );
		}
	}

	/**
	 * Create sample addons.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_sample_addons(): void {
		$addons = array(
			array(
				'name'  => '3D Printed Name Tag',
				'price' => 2.00,
			),
			array(
				'name'  => 'Premium Toy - Deluxe Car',
				'price' => 3.00,
				'stock' => 30,
			),
			array(
				'name'  => 'Premium Toy - Action Figure',
				'price' => 4.00,
				'stock' => 25,
			),
		);

		$tag_id = self::get_tag_id( 'addons' );

		foreach ( $addons as $addon ) {
			self::create_sample_product( $addon['name'], $addon['price'], 0, $addon['stock'] ?? 0, array( $tag_id ) );
		}
	}

	/**
	 * Create a sample product.
	 *
	 * @param string $name        Product name.
	 * @param float  $price       Product price.
	 * @param int    $category_id Category ID (0 for no category).
	 * @param int    $stock       Optional. Stock quantity.
	 * @param array  $tag_ids     Optional. Tag IDs to assign.
	 *
	 * @throws WC_Data_Exception Throws exception when invalid data is found.
	 */
	private static function create_sample_product( string $name, float $price, int $category_id = 0, int $stock = 0, array $tag_ids = array() ): void {
		$product = new WC_Product_Simple();
		$product->set_name( $name );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_price( $price );
		$product->set_regular_price( $price );
		$product->set_manage_stock( true );
		$product->set_sold_individually( true );
		if ( $stock > 0 ) {
			$product->set_stock_quantity( $stock );
		}
		$product->set_stock_status( 'instock' );

		// Set category if provided.
		if ( $category_id > 0 ) {
			$product->set_category_ids( array( $category_id ) );
		}

		// Set tags if provided.
		if ( ! empty( $tag_ids ) ) {
			$product->set_tag_ids( $tag_ids );
		}

		$product->save();
	}

	/**
	 * Get category ID by slug.
	 *
	 * @param string $slug Category slug.
	 *
	 * @return int Category ID or 0 if not found.
	 */
	private static function get_category_id( string $slug ): int {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		return $term ? $term->term_id : 0;
	}

	/**
	 * Get tag ID by slug.
	 *
	 * @param string $slug Tag slug.
	 *
	 * @return int Tag ID or 0 if not found.
	 */
	private static function get_tag_id( string $slug ): int {
		$term = get_term_by( 'slug', $slug, 'product_tag' );
		return $term ? $term->term_id : 0;
	}
}
