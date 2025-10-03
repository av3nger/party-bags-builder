<?php
/**
 * Party Bag Builder Block - Server-side render
 *
 * @package PartyBagBuilder
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

use PBB\Plugin;

defined( 'ABSPATH' ) || exit;

// Get Product Manager instance.
$product_manager = Plugin::instance()->product_manager;

// Load configuration.
$config = require PBB_PLUGIN_DIR . 'includes/config.php';

// Fetch products for server-rendering.
$common_items = $product_manager->get_products_by_category( 'bag-common' );
$toys         = $product_manager->get_products_by_category( 'bag-toys' );
$addons       = $product_manager->get_products_by_category( 'bag-addons' );

// Prepare context data for template.
$context = array(
	'tiers'        => array_values( $config['tiers'] ),
	'tag_styles'   => array_values( $config['tag_styles'] ),
	'common_items' => $common_items,
	'toys'         => $toys,
	'addons'       => $addons,
	'rest_url'     => rest_url( 'bag-builder/v1/add-to-cart' ),
	'nonce'        => wp_create_nonce( 'wp_rest' ),
);

// Add full preview URLs for tag styles.
foreach ( $context['tag_styles'] as &$style ) {
	$style['preview_url'] = PBB_PLUGIN_URL . 'assets/images/tag-examples/' . $style['preview_image'];
	unset( $style['preview_image'] );
}

// Include the wizard container template.
require_once PBB_PLUGIN_DIR . 'includes/templates/wizard-container.php';
