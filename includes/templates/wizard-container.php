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
	'currentStep'      => 1,
	'totalSteps'       => 6,
	'kidCount'         => 0,
	'selectedTier'     => null,
	'tierConfig'       => null,
	'selectedToys'     => array(),
	'selectedAddons'   => array(),
	'selectedTagStyle' => null,
	'kidNames'         => array(),
	'priceBreakdown'   => array(
		'base'       => 0,
		'addons'     => 0,
		'total'      => 0,
		'freeAddons' => array(),
		'paidAddons' => array(),
	),
	'isLoading'        => false,
	'errors'           => array(),
	// Data.
	'tiers'            => $context['tiers'],
	'tag_styles'       => $context['tag_styles'],
	'common_items'     => $context['common_items'],
	'toys'             => $context['toys'],
	'addons'           => $context['addons'],
	'rest_url'         => $context['rest_url'],
	'nonce'            => $context['nonce'],
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
		<div class="pbb-step-indicator">
			<div class="pbb-step-indicator-inner">
				<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
					<div
						class="pbb-step-indicator-item"
						data-wp-class--active="<?php echo esc_attr( "context.currentStep === $i" ); ?>"
						data-wp-class--completed="<?php echo esc_attr( "context.currentStep > $i" ); ?>"
					>
						<span class="pbb-step-number"><?php echo esc_html( $i ); ?></span>
						<span class="pbb-step-label">
							<?php
							$labels = array(
								1 => __( 'Kid Count', 'party-bag-builder' ),
								2 => __( 'Tier', 'party-bag-builder' ),
								3 => __( 'Toys', 'party-bag-builder' ),
								4 => __( 'Add-ons', 'party-bag-builder' ),
								5 => __( 'Names', 'party-bag-builder' ),
								6 => __( 'Review', 'party-bag-builder' ),
							);
							echo esc_html( $labels[ $i ] );
							?>
						</span>
					</div>
				<?php endfor; ?>
			</div>
		</div>

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
		<div
			class="pbb-price-display"
			data-wp-bind--hidden="!context.selectedTier"
		>
			<div class="pbb-price-display-inner">
				<span class="pbb-price-label"><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></span>
				<span class="pbb-price-amount">
					$<span data-wp-text="context.priceBreakdown.total.toFixed(2)">0.00</span>
				</span>
			</div>
		</div>
	</div>
</div>
