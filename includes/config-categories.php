<?php
/**
 * Category Configuration
 *
 * Defines the product categories used by the party bag builder.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

return array(
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
);
