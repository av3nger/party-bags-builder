<?php
/**
 * Wizard Container Template
 *
 * Main container for the party bag builder wizard using WordPress Interactivity API.
 *
 * @package PartyBagBuilder
 * @var array $context Context data passed from render_block.
 */

defined( 'ABSPATH' ) || exit;

// Prepare context for Interactivity API.
$interactive_context = array(
	// State.
	'currentStep'        => 1,
	'totalSteps'         => 6,
	'kidCount'           => 0,
	'selectedTier'       => null,
	'tierConfig'         => null,
	'selectedToys'       => array(),
	'selectedAddons'     => array(),
	'selectedTagStyle'   => null,
	'kidNames'           => array(),
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
	// Data.
	'tiers'              => $context['tiers'],
	'tag_styles'         => $context['tag_styles'],
	'common_items'       => $context['common_items'],
	'toys'               => $context['toys'],
	'addons'             => $context['addons'],
	'rest_url'           => $context['rest_url'],
	'nonce'              => $context['nonce'],
);

// Encode context for JavaScript.
$context_json = wp_json_encode( $interactive_context );
?>

<div
	class="pbb-wizard-container"
	data-wp-interactive="party-bag-builder"
	<?php echo 'data-wp-context=\'' . esc_attr( $context_json ) . '\''; ?>
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
