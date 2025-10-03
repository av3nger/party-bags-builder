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
				'max_addons'  => 2,
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
				'max_addons'  => 3,
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
				'max_addons'  => 3,
			),
		),
	),

	/**
	 * Name Tag Styles
	 *
	 * Defines the name tag color combinations available for selection.
	 */
	'tag_styles' => array(
		'red-white'     => array(
			'id'              => 'red-white',
			'name'            => __( 'Red & White', 'party-bag-builder' ),
			'tag_color'       => '#FF0000',
			'tag_color_name'  => __( 'Red', 'party-bag-builder' ),
			'base_color'      => '#FFFFFF',
			'base_color_name' => __( 'White', 'party-bag-builder' ),
			'preview_image'   => 'red-white-tag.jpg',
		),
		'blue-yellow'   => array(
			'id'              => 'blue-yellow',
			'name'            => __( 'Blue & Yellow', 'party-bag-builder' ),
			'tag_color'       => '#0000FF',
			'tag_color_name'  => __( 'Blue', 'party-bag-builder' ),
			'base_color'      => '#FFFF00',
			'base_color_name' => __( 'Yellow', 'party-bag-builder' ),
			'preview_image'   => 'blue-yellow-tag.jpg',
		),
		'pink-white'    => array(
			'id'              => 'pink-white',
			'name'            => __( 'Pink & White', 'party-bag-builder' ),
			'tag_color'       => '#FFC0CB',
			'tag_color_name'  => __( 'Pink', 'party-bag-builder' ),
			'base_color'      => '#FFFFFF',
			'base_color_name' => __( 'White', 'party-bag-builder' ),
			'preview_image'   => 'pink-white-tag.jpg',
		),
		'green-black'   => array(
			'id'              => 'green-black',
			'name'            => __( 'Green & Black', 'party-bag-builder' ),
			'tag_color'       => '#00FF00',
			'tag_color_name'  => __( 'Green', 'party-bag-builder' ),
			'base_color'      => '#000000',
			'base_color_name' => __( 'Black', 'party-bag-builder' ),
			'preview_image'   => 'green-black-tag.jpg',
		),
		'purple-silver' => array(
			'id'              => 'purple-silver',
			'name'            => __( 'Purple & Silver', 'party-bag-builder' ),
			'tag_color'       => '#800080',
			'tag_color_name'  => __( 'Purple', 'party-bag-builder' ),
			'base_color'      => '#C0C0C0',
			'base_color_name' => __( 'Silver', 'party-bag-builder' ),
			'preview_image'   => 'purple-silver-tag.jpg',
		),
		'orange-blue'   => array(
			'id'              => 'orange-blue',
			'name'            => __( 'Orange & Blue', 'party-bag-builder' ),
			'tag_color'       => '#FFA500',
			'tag_color_name'  => __( 'Orange', 'party-bag-builder' ),
			'base_color'      => '#0000FF',
			'base_color_name' => __( 'Blue', 'party-bag-builder' ),
			'preview_image'   => 'orange-blue-tag.jpg',
		),
	),
);
