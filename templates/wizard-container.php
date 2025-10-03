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

// Prepare initial state for Interactivity API.
$initial_state = array(
	'currentStep'    => 1,
	'totalSteps'     => 6,
	'kidCount'       => 0,
	'selectedTier'   => null,
	'tierConfig'     => null,
	'commonItems'    => $context['common_items'],
	'selectedToys'   => array(),
	'selectedAddons' => array(),
	'selectedTagStyle' => null,
	'kidNames'       => array(),
	'priceBreakdown' => array(
		'base'   => 0,
		'addons' => 0,
		'total'  => 0,
	),
	'isLoading'      => false,
	'errors'         => array(),
);

// Encode context for JavaScript.
$context_json = wp_json_encode(
	array(
		'state' => $initial_state,
		'tiers' => $context['tiers'],
		'tagStyles' => $context['tag_styles'],
		'toys'  => $context['toys'],
		'addons' => $context['addons'],
		'restUrl' => $context['rest_url'],
		'nonce' => $context['nonce'],
	)
);
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
						data-wp-class--active="<?php echo esc_attr( "state.currentStep === $i" ); ?>"
						data-wp-class--completed="<?php echo esc_attr( "state.currentStep > $i" ); ?>"
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
			<!-- Step 1: Kid Count -->
			<div
				class="pbb-wizard-step"
				data-wp-bind--hidden="state.currentStep !== 1"
			>
				<h2><?php esc_html_e( 'How many kids are attending?', 'party-bag-builder' ); ?></h2>
				<p class="pbb-step-description">
					<?php esc_html_e( 'Enter the number of party bags you need (1-50)', 'party-bag-builder' ); ?>
				</p>

				<div class="pbb-kid-count-input">
					<input
						type="number"
						id="pbb-kid-count"
						min="1"
						max="50"
						step="1"
						data-wp-on--input="actions.setKidCount"
						data-wp-bind--value="state.kidCount"
					/>
					<label for="pbb-kid-count"><?php esc_html_e( 'Number of kids', 'party-bag-builder' ); ?></label>
				</div>

				<div class="pbb-step-navigation">
					<button
						type="button"
						class="pbb-button pbb-button-primary"
						data-wp-on--click="actions.nextStep"
						data-wp-bind--disabled="!state.kidCount || state.kidCount < 1"
					>
						<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
					</button>
				</div>
			</div>

			<!-- Step 2: Tier Selection -->
			<div
				class="pbb-wizard-step"
				data-wp-bind--hidden="state.currentStep !== 2"
			>
				<h2><?php esc_html_e( 'Choose your tier', 'party-bag-builder' ); ?></h2>
				<p class="pbb-step-description">
					<?php esc_html_e( 'Select the perfect tier for your party', 'party-bag-builder' ); ?>
				</p>

				<div class="pbb-tier-grid">
					<?php foreach ( $context['tiers'] as $tier ) : ?>
						<div
							class="pbb-tier-card"
							data-wp-on--click="actions.selectTier"
							data-wp-on-click-tier-id="<?php echo esc_attr( $tier['id'] ); ?>"
							data-wp-class--selected="state.selectedTier === '<?php echo esc_attr( $tier['id'] ); ?>'"
						>
							<h3 class="pbb-tier-name"><?php echo esc_html( $tier['name'] ); ?></h3>
							<div class="pbb-tier-price">
								<?php echo wp_kses_post( wc_price( $tier['base_price'] ) ); ?>
								<span class="pbb-tier-price-label"><?php esc_html_e( 'per bag', 'party-bag-builder' ); ?></span>
							</div>
							<p class="pbb-tier-description"><?php echo esc_html( $tier['description'] ); ?></p>
							<ul class="pbb-tier-features">
								<li><?php esc_html_e( 'All common items included', 'party-bag-builder' ); ?></li>
								<li>
									<?php
									/* translators: %d: number of toys */
									echo esc_html( sprintf( _n( '%d toy to choose', '%d toys to choose', $tier['includes']['toys'], 'party-bag-builder' ), $tier['includes']['toys'] ) );
									?>
								</li>
								<?php if ( $tier['includes']['free_addons'] > 0 ) : ?>
									<li>
										<?php
										/* translators: %d: number of free addons */
										echo esc_html( sprintf( _n( '%d FREE add-on', '%d FREE add-ons', $tier['includes']['free_addons'], 'party-bag-builder' ), $tier['includes']['free_addons'] ) );
										?>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="pbb-step-navigation">
					<button
						type="button"
						class="pbb-button pbb-button-secondary"
						data-wp-on--click="actions.prevStep"
					>
						<?php esc_html_e( 'Back', 'party-bag-builder' ); ?>
					</button>
					<button
						type="button"
						class="pbb-button pbb-button-primary"
						data-wp-on--click="actions.nextStep"
						data-wp-bind--disabled="!state.selectedTier"
					>
						<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
					</button>
				</div>
			</div>

			<!-- Steps 3-6: Placeholder (will be implemented in Phase 7) -->
			<div
				class="pbb-wizard-step"
				data-wp-bind--hidden="state.currentStep < 3 || state.currentStep > 6"
			>
				<h2><?php esc_html_e( 'Steps 3-6 Coming Soon', 'party-bag-builder' ); ?></h2>
				<p><?php esc_html_e( 'Additional wizard steps will be implemented in Phase 7.', 'party-bag-builder' ); ?></p>

				<div class="pbb-step-navigation">
					<button
						type="button"
						class="pbb-button pbb-button-secondary"
						data-wp-on--click="actions.prevStep"
					>
						<?php esc_html_e( 'Back', 'party-bag-builder' ); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Price Display (Sticky) -->
		<div
			class="pbb-price-display"
			data-wp-bind--hidden="!state.selectedTier"
		>
			<div class="pbb-price-display-inner">
				<span class="pbb-price-label"><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></span>
				<span class="pbb-price-amount" data-wp-text="state.priceBreakdown.total"></span>
			</div>
		</div>
	</div>
</div>