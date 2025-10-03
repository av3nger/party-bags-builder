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

// Initialize Interactivity API state (reactive).
wp_interactivity_state(
	'party-bag-builder',
	array(
		'currentStep'        => 1,
		'kidCount'           => 5,
		'selectedTier'       => 'medium',
		'tierConfig'         => $config['tiers']['medium'],
		'selectedToys'       => array(),
		'selectedAddons'     => array(),
		'selectedTagStyle'   => null,
		'kidNames'           => array_fill( 0, 5, '' ),
		'priceBreakdown'     => array(
			'base'       => 0,
			'addons'     => 0,
			'total'      => 0,
			'freeAddons' => array(),
			'paidAddons' => array(),
		),
		'showPriceBreakdown' => false,
		'isLoading'          => false,
		'errors'             => array(),
	)
);

// Prepare context data (non-reactive, instance-specific).
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
?>

<div
	<?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>
	class="pbb-wizard-container"
	data-wp-interactive="party-bag-builder"
	<?php echo wp_kses_data( wp_interactivity_data_wp_context( $context ) ); ?>
>
	<div class="pbb-wizard-wrapper">
		<!-- Step Indicator -->
		<?php require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/components/step-indicator.php'; ?>

		<!-- Wizard Content -->
		<div class="pbb-wizard-content">
			<?php
			// Include all step templates.
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-1-kid-count.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-2-tier-selection.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-3-toy-selection.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-4-addon-selection.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-5-kid-names.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-6-review.php';
			?>
		</div>

		<!-- Price Display (Sticky) -->
		<?php require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/components/price-display.php'; ?>
	</div>
</div>
