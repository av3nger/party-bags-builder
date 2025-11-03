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
	'categories'     => array(
		'bags' => array(
			'slug'        => 'bags',
			'name'        => __( 'Party Bags', 'party-bag-builder' ),
			'description' => __( 'Party bag designs - select one bag style for your party', 'party-bag-builder' ),
		),
		'toys' => array(
			'slug'        => 'toys',
			'name'        => __( 'Toys', 'party-bag-builder' ),
			'description' => __( 'Standard selectable toys for party bags', 'party-bag-builder' ),
		),
	),

	/**
	 * Product Tags
	 *
	 * Defines the WooCommerce product tags used by the party bag builder.
	 */
	'tags'           => array(
		'toys'   => array(
			'slug'        => 'toys',
			'name'        => __( 'Party Bag - Toys', 'party-bag-builder' ),
			'description' => __( 'Standard selectable toys for party bags', 'party-bag-builder' ),
		),
		'addons' => array(
			'slug'        => 'addons',
			'name'        => __( 'Party Bag - Add-ons', 'party-bag-builder' ),
			'description' => __( 'Premium add-ons (name tags, premium toys)', 'party-bag-builder' ),
		),
	),

	/**
	 * Toy Categories
	 *
	 * Defines categories for organizing generic toys in accordion sections.
	 * Generic toys tagged with these category slugs will be grouped together.
	 * Toys without any category tags will appear in 'Miscellaneous'.
	 */
	'toy_categories' => array(
		'fidgets'  => array(
			'slug' => 'fidgets',
			'name' => __( 'Fidgets', 'party-bag-builder' ),
		),
		'puzzles'  => array(
			'slug' => 'puzzles',
			'name' => __( 'Puzzles', 'party-bag-builder' ),
		),
		'stickers' => array(
			'slug' => 'stickers',
			'name' => __( 'Stickers', 'party-bag-builder' ),
		),
	),

	/**
	 * Theme Tags
	 *
	 * Defines theme tags used for filtering bags and toys.
	 * Bags and toys should be tagged with these themes.
	 * Only bags with at least one theme tag will appear in the wizard.
	 */
	'themes'         => array(
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
	'tiers'          => array(
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
	'tag_styles'     => array(
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

	/**
	 * Print Colors
	 *
	 * Defines the available print colors for themed toys.
	 * Colors are shown as swatches in the toy selection wizard.
	 */
	'print_colors'   => array(
		array(
			'id'    => 'white',
			'name'  => __( 'White', 'party-bag-builder' ),
			'color' => 'rgb(255, 255, 255)',
		),
		array(
			'id'    => 'bone-white',
			'name'  => __( 'Bone White', 'party-bag-builder' ),
			'color' => 'rgb(203, 198, 184)',
		),
		array(
			'id'    => 'yellow',
			'name'  => __( 'Yellow', 'party-bag-builder' ),
			'color' => 'rgb(247, 217, 89)',
		),
		array(
			'id'    => 'orange',
			'name'  => __( 'Orange', 'party-bag-builder' ),
			'color' => 'rgb(249, 153, 99)',
		),
		array(
			'id'    => 'pink',
			'name'  => __( 'Pink', 'party-bag-builder' ),
			'color' => 'rgb(232, 175, 207)',
		),
		array(
			'id'    => 'purple',
			'name'  => __( 'Purple', 'party-bag-builder' ),
			'color' => 'rgb(174, 150, 212)',
		),
		array(
			'id'    => 'plum',
			'name'  => __( 'Plum', 'party-bag-builder' ),
			'color' => 'rgb(149, 0, 81)',
		),
		array(
			'id'    => 'scarlet-red',
			'name'  => __( 'Scarlet Red', 'party-bag-builder' ),
			'color' => 'rgb(222, 67, 67)',
		),
		array(
			'id'    => 'dark-red',
			'name'  => __( 'Dark Red', 'party-bag-builder' ),
			'color' => 'rgb(187, 61, 67)',
		),
		array(
			'id'    => 'apple-green',
			'name'  => __( 'Apple Green', 'party-bag-builder' ),
			'color' => 'rgb(194, 225, 137)',
		),
		array(
			'id'    => 'grass-green',
			'name'  => __( 'Grass Green', 'party-bag-builder' ),
			'color' => 'rgb(97, 198, 128)',
		),
		array(
			'id'    => 'turquoise',
			'name'  => __( 'Turquoise', 'party-bag-builder' ),
			'color' => 'rgb(0, 177, 183)',
		),
		array(
			'id'    => 'sky-blue',
			'name'  => __( 'Sky Blue', 'party-bag-builder' ),
			'color' => 'rgb(86, 183, 230)',
		),
		array(
			'id'    => 'marine-blue',
			'name'  => __( 'Marine Blue', 'party-bag-builder' ),
			'color' => 'rgb(0, 120, 191)',
		),
		array(
			'id'    => 'caramel',
			'name'  => __( 'Caramel', 'party-bag-builder' ),
			'color' => 'rgb(174, 131, 91)',
		),
		array(
			'id'    => 'terracotta',
			'name'  => __( 'Terracotta', 'party-bag-builder' ),
			'color' => 'rgb(177, 85, 51)',
		),
		array(
			'id'    => 'ash-gray',
			'name'  => __( 'Ash Gray', 'party-bag-builder' ),
			'color' => 'rgb(155, 158, 160)',
		),
		array(
			'id'    => 'black',
			'name'  => __( 'Black', 'party-bag-builder' ),
			'color' => 'rgb(0, 0, 0)',
		),
	),
);
