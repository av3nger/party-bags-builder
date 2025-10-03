<?php
/**
 * Tier Configuration
 *
 * Defines the pricing tiers for party bags.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

return array(
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
);
