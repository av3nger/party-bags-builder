<?php
/**
 * Step 2 - Tier Selection with Included Items
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with tiers and common_items.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-2" data-wp-context='{"stepNumber": 2}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Choose Your Tier', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Select the party bag tier that best fits your needs', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-tier-grid">
			<?php foreach ( $context['tiers'] as $tier ) : ?>
				<div
					class="pbb-tier-card"
					data-tier-id="<?php echo esc_attr( $tier['id'] ); ?>"
					data-wp-context='<?php echo esc_attr( wp_json_encode( array( 'tierId' => $tier['id'], 'tierBasePrice' => $tier['base_price'], 'tier' => $tier ) ) ); ?>'
					data-wp-on--click="actions.selectTier"
					data-wp-class--selected="state.isTierSelected"
				>
					<div class="pbb-tier-header">
						<h3 class="pbb-tier-name"><?php echo esc_html( $tier['name'] ); ?></h3>
						<div class="pbb-tier-price">
							<?php
							/* translators: %s: price */
							echo esc_html( sprintf( __( '$%s per bag', 'party-bag-builder' ), number_format( $tier['base_price'], 2 ) ) );
							?>
						</div>
					</div>

					<div class="pbb-tier-features">
						<ul>
							<li><?php esc_html_e( 'All base items included', 'party-bag-builder' ); ?></li>
							<li>
								<?php
								/* translators: %d: number of toys */
								echo esc_html( sprintf( _n( '%d toy of your choice', '%d toys of your choice', $tier['includes']['toys'], 'party-bag-builder' ), $tier['includes']['toys'] ) );
								?>
							</li>
							<?php if ( $tier['includes']['free_addons'] > 0 ) : ?>
								<li>
									<?php
									/* translators: %d: number of free addons */
									echo esc_html( sprintf( _n( '%d FREE addon', '%d FREE addons', $tier['includes']['free_addons'], 'party-bag-builder' ), $tier['includes']['free_addons'] ) );
									?>
								</li>
							<?php endif; ?>
							<li>
								<?php
								/* translators: %d: maximum number of addons */
								echo esc_html( sprintf( __( 'Up to %d total addons', 'party-bag-builder' ), $tier['includes']['max_addons'] ) );
								?>
							</li>
						</ul>
					</div>

					<div class="pbb-tier-total">
						<div class="pbb-total-label"><?php esc_html_e( 'Your total:', 'party-bag-builder' ); ?></div>
						<div class="pbb-total-amount">
							<?php
							/* translators: %s will be replaced with calculated price */
							echo esc_html__( '$', 'party-bag-builder' );
							?>
							<span data-wp-text="state.currentTierPrice">0.00</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Common Items Preview (shown when tier is selected) -->
		<div class="pbb-common-items-preview" data-wp-bind--hidden="!state.selectedTier">
			<h3 class="pbb-preview-title"><?php esc_html_e( 'Each party bag includes the following base items:', 'party-bag-builder' ); ?></h3>

			<?php if ( ! empty( $context['common_items'] ) ) : ?>
				<div class="pbb-product-grid pbb-preview-grid">
					<?php foreach ( $context['common_items'] as $item ) : ?>
						<div class="pbb-product-card pbb-preview-card">
							<?php if ( ! empty( $item['image_url'] ) ) : ?>
								<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" class="pbb-product-image">
							<?php else : ?>
								<div class="pbb-product-image pbb-placeholder-image">
									<span class="pbb-placeholder-icon">📦</span>
								</div>
							<?php endif; ?>
							<div class="pbb-product-info">
								<h4 class="pbb-product-name"><?php echo esc_html( $item['name'] ); ?></h4>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="pbb-empty-message">
					<?php esc_html_e( 'No common items available at the moment.', 'party-bag-builder' ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="pbb-step-navigation">
			<button
				type="button"
				class="pbb-button pbb-button-secondary pbb-button-prev"
				data-wp-on--click="actions.prevStep"
			>
				<span class="pbb-button-icon">←</span>
				<?php esc_html_e( 'Previous', 'party-bag-builder' ); ?>
			</button>
			<button
				type="button"
				class="pbb-button pbb-button-primary pbb-button-next"
				data-wp-on--click="actions.nextStep"
				data-wp-bind--disabled="!state.selectedTier"
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
