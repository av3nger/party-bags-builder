<?php
/**
 * Tag Styles Configuration
 *
 * Defines the name tag color combinations available for selection.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

return array(
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
);
