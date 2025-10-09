<?php
/**
 * Party Bag Builder Configuration
 *
 * Consolidated configuration file for categories, tiers, and tag styles.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

return array(
	/**
	 * Product Categories
	 *
	 * Defines the WooCommerce product categories used by the party bag builder.
	 */
	'categories' => array(
		'bag-common' => array(
			'slug'           => 'bag-common',
			'name'           => __( 'Party Bag - Common Items', 'party-bag-builder' ),
			'description'    => __( 'Items automatically included in every party bag (lollies, balloons, stickers)', 'party-bag-builder' ),
			'selection_type' => 'auto_include',
		),
		'bag-toys'   => array(
			'slug'           => 'bag-toys',
			'name'           => __( 'Party Bag - Toys', 'party-bag-builder' ),
			'description'    => __( 'Standard selectable toys for party bags', 'party-bag-builder' ),
			'selection_type' => 'user_select',
		),
		'bag-addons' => array(
			'slug'           => 'bag-addons',
			'name'           => __( 'Party Bag - Add-ons', 'party-bag-builder' ),
			'description'    => __( 'Premium add-ons (name tags, premium toys)', 'party-bag-builder' ),
			'selection_type' => 'user_select',
			'pricing'        => 'additional',
		),
	),

	/**
	 * Pricing Tiers
	 *
	 * Defines the pricing tiers for party bags.
	 */
	'tiers'      => array(
		'basic'   => array(
			'id'          => 'basic',
			'name'        => __( 'Basic', 'party-bag-builder' ),
			'base_price'  => 6.00,
			'description' => __( 'Perfect for small celebrations', 'party-bag-builder' ),
			'includes'    => array(
				'common'      => 'all',
				'toys'        => 1,
				'free_addons' => 0,
			),
		),
		'medium'  => array(
			'id'          => 'medium',
			'name'        => __( 'Medium', 'party-bag-builder' ),
			'base_price'  => 10.00,
			'description' => __( 'Great value bundle', 'party-bag-builder' ),
			'includes'    => array(
				'common'      => 'all',
				'toys'        => 2,
				'free_addons' => 0,
			),
		),
		'premium' => array(
			'id'          => 'premium',
			'name'        => __( 'Premium', 'party-bag-builder' ),
			'base_price'  => 15.00,
			'description' => __( 'Ultimate party experience', 'party-bag-builder' ),
			'includes'    => array(
				'common'      => 'all',
				'toys'        => 3,
				'free_addons' => 1,
			),
		),
	),

	/**
	 * Name Tag Styles
	 *
	 * Defines the name tag color combinations available for selection.
	 */
	'tag_styles' => array(
		'pink-white'   => array(
			'id'   => 'pink-white',
			'name' => __( 'Pink & White', 'party-bag-builder' ),
		),
		'green-white'  => array(
			'id'   => 'green-white',
			'name' => __( 'Green & White', 'party-bag-builder' ),
		),
		'blue-white'   => array(
			'id'   => 'blue-white',
			'name' => __( 'Blue & White', 'party-bag-builder' ),
		),
		'purple-white' => array(
			'id'   => 'purple-white',
			'name' => __( 'Purple & White', 'party-bag-builder' ),
		),
		'yellow-white' => array(
			'id'   => 'yellow-white',
			'name' => __( 'Yellow & White', 'party-bag-builder' ),
		),
		'red-white'    => array(
			'id'   => 'red-white',
			'name' => __( 'Red & White', 'party-bag-builder' ),
		),
	),
);
