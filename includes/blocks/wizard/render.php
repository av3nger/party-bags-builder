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

// Initialize Interactivity API state (reactive).
wp_interactivity_state(
	'party-bag-builder',
	array(
		'currentStep'      => 1,
		'kidCount'         => 5,
		'selectedTier'     => 'medium',
		'tierConfig'       => $config['tiers']['medium'],
		'selectedBag'      => null,
		'selectedToys'     => array(),
		'selectedAddons'   => array(),
		'selectedToyColor' => array(),
		'selectedTagStyle' => null,
		'kidNames'         => array_fill( 0, 5, '' ),
		'priceBreakdown'   => array(
			'base'   => $config['tiers']['medium']['base_price'] * 5,
			'addons' => 0,
			'total'  => $config['tiers']['medium']['base_price'] * 5,
		),
		'isLoading'        => false,
		'errors'           => array(),
		'openCategory'     => null,
	)
);

// Prepare context data (non-reactive, instance-specific).
$all_toys = $product_manager->get_products_by_tag( 'toys' );

$context = array(
	'tiers'         => array_values( $config['tiers'] ),
	'tag_styles'    => array_values( $config['tag_styles'] ),
	'colors'        => array_values( $config['print_colors'] ),
	'toyCategories' => array_values( $config['toy_categories'] ),
	'common_items'  => $product_manager->get_products_by_tag( 'common' ),
	'bags'          => $product_manager->get_products_by_category( 'bags' ),
	'toysThemed'    => array_values( array_filter( $all_toys, fn( $toy ) => ! empty( $toy['theme'] ) ) ),
	'toysGeneric'   => array_values( array_filter( $all_toys, fn( $toy ) => empty( $toy['theme'] ) ) ),
	'addons'        => $product_manager->get_products_by_tag( 'addons' ),
	'rest_url'      => rest_url( 'bag-builder/v1/add-to-cart' ),
	'nonce'         => wp_create_nonce( 'wp_rest' ),
);
?>

<div
	<?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>
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
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-3-bag-selection.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-4-toy-selection.php';
			require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/steps/step-5-review.php';
			?>
		</div>

		<!-- Price Display (Sticky) -->
		<?php require_once PBB_PLUGIN_DIR . 'includes/templates/wizard/components/price-display.php'; ?>
	</div>
</div>
