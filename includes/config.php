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
		'bags' => array(
			'slug'           => 'bags',
			'name'           => __( 'Party Bags', 'party-bag-builder' ),
			'description'    => __( 'Party bag designs - select one bag style for your party', 'party-bag-builder' ),
			'selection_type' => 'user_select',
		),
		'toys' => array(
			'slug'           => 'toys',
			'name'           => __( 'Toys', 'party-bag-builder' ),
			'description'    => __( 'Standard selectable toys for party bags', 'party-bag-builder' ),
			'selection_type' => 'user_select',
		),
	),

	/**
	 * Product Tags
	 *
	 * Defines the WooCommerce product tags used by the party bag builder.
	 */
	'tags'       => array(
		'common' => array(
			'slug'           => 'common',
			'name'           => __( 'Party Bag - Common Items', 'party-bag-builder' ),
			'description'    => __( 'Items automatically included in every party bag (lollies, balloons, stickers)', 'party-bag-builder' ),
			'selection_type' => 'auto_include',
		),
		'toys'   => array(
			'slug'           => 'toys',
			'name'           => __( 'Party Bag - Toys', 'party-bag-builder' ),
			'description'    => __( 'Standard selectable toys for party bags', 'party-bag-builder' ),
			'selection_type' => 'user_select',
		),
		'addons' => array(
			'slug'           => 'addons',
			'name'           => __( 'Party Bag - Add-ons', 'party-bag-builder' ),
			'description'    => __( 'Premium add-ons (name tags, premium toys)', 'party-bag-builder' ),
			'selection_type' => 'user_select',
			'pricing'        => 'additional',
		),
	),

	/**
	 * Theme Tags
	 *
	 * Defines theme tags used for filtering bags and toys.
	 * Bags and toys should be tagged with these themes.
	 * Only bags with at least one theme tag will appear in the wizard.
	 */
	'themes'     => array(
		'animals' => array(
			'slug' => 'animals',
			'name' => __( 'Animals', 'party-bag-builder' ),
		),
		'space'   => array(
			'slug' => 'space',
			'name' => __( 'Space', 'party-bag-builder' ),
		),
		'unicorn' => array(
			'slug' => 'unicorn',
			'name' => __( 'Unicorn', 'party-bag-builder' ),
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
			'description' => __( 'Simple, fun, and budget-friendly.', 'party-bag-builder' ),
			'label'       => array(
				esc_html__( '1 themed toy of your choice', 'party-bag-builder' ),
			),
			'includes'    => array(
				'themed'   => 1,
				'generic'  => 0,
				'name_tag' => false,
			),
		),
		'medium'  => array(
			'id'          => 'medium',
			'name'        => __( 'Medium', 'party-bag-builder' ),
			'base_price'  => 10.00,
			'description' => __( 'Most popular tier - more variety, great value.', 'party-bag-builder' ),
			'label'       => array(
				esc_html__( '1 themed toy + 1 generic toy', 'party-bag-builder' ),
			),
			'includes'    => array(
				'themed'   => 1,
				'generic'  => 1,
				'name_tag' => false,
			),
		),
		'premium' => array(
			'id'          => 'premium',
			'name'        => __( 'Premium', 'party-bag-builder' ),
			'base_price'  => 15.00,
			'description' => __( 'For parties that deserve the ultimate "wow".', 'party-bag-builder' ),
			'label'       => array(
				esc_html__( '1 themed toy + 1 generic toy', 'party-bag-builder' ),
				esc_html__( '3D-printed personalized name tag', 'party-bag-builder' ),
			),
			'includes'    => array(
				'themed'   => 1,
				'generic'  => 1,
				'name_tag' => true,
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
