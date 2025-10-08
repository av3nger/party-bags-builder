<?php
/**
 * Step 4 - Addon Selection with Tag Style
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with addons and tag_styles.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-4" data-wp-context='{"step": {"id": 4}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Add Extra Goodies (Optional)', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Enhance your party bags with premium add-ons', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-selection-counter">
			<span class="pbb-counter-label"><?php esc_html_e( 'Selected:', 'party-bag-builder' ); ?></span>
			<span class="pbb-counter-value">
				<span data-wp-text="state.selectedAddons.length">0</span>
				<?php esc_html_e( ' of ', 'party-bag-builder' ); ?>
				<span data-wp-text="state.maxAddonsAllowed">0</span>
				<?php esc_html_e( ' max', 'party-bag-builder' ); ?>
			</span>
			<span class="pbb-free-addons" data-wp-bind--hidden="!state.freeAddonsAllowed">
				(<span data-wp-text="state.freeAddonsAllowed">0</span>
				<?php esc_html_e( ' FREE', 'party-bag-builder' ); ?>)
			</span>
		</div>

		<?php if ( ! empty( $context['addons'] ) ) : ?>
			<div class="pbb-product-grid">
				<template data-wp-each--addon="context.addons">
					<div
						class="pbb-product-card pbb-selectable-card"
						data-wp-class--selected="state.isAddonSelected"
						data-wp-class--disabled="state.isAddonInputDisabled"
						data-wp-on--click="actions.toggleAddon"
					>
						<div class="pbb-product-image">
							<img data-wp-bind--src="context.addon.image_url" data-wp-bind--alt="context.addon.name" />
							<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isAddonSelected">
								<div class="pbb-product-image-check">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 6 9 17l-5-5"></path>
									</svg>
								</div>
							</div>
							<div class="pbb-product-image-disabled" data-wp-bind--hidden="!state.isAddonInputDisabled">
								<span><?php esc_html_e( 'Max selected', 'party-bag-builder' ); ?></span>
							</div>
						</div>

						<div class="pbb-product-info">
							<h4 class="pbb-product-name" data-wp-text="context.addon.name"></h4>
							<p class="pbb-product-description" data-wp-text="context.addon.description"></p>
							<div class="pbb-price-badge">
								<span class="pbb-price-amount">
									+$<span data-wp-text="context.addon.price"></span> <?php esc_html_e( 'per bag', 'party-bag-builder' ); ?>
								</span>
								<span class="pbb-free-indicator" data-wp-bind--hidden="!state.isAddonFree">
									<?php esc_html_e( 'FREE (included)', 'party-bag-builder' ); ?>
								</span>
							</div>
						</div>
					</div>
				</template>
			</div>
		<?php else : ?>
			<p class="pbb-info-message">
				<?php esc_html_e( 'No add-ons available at the moment. You can continue to the next step.', 'party-bag-builder' ); ?>
			</p>
		<?php endif; ?>

		<!-- Tag Style Selection (shown when applicable) -->
		<?php if ( ! empty( $context['tag_styles'] ) ) : ?>
			<div class="pbb-tag-style-section" data-wp-bind--hidden="state.selectedAddons.length === 0">
				<h3 class="pbb-subsection-title"><?php esc_html_e( 'Choose Your Tag Style', 'party-bag-builder' ); ?></h3>
				<p class="pbb-subsection-description">
					<?php esc_html_e( 'Select a color style for personalized name tags', 'party-bag-builder' ); ?>
				</p>

				<div class="pbb-tag-styles-grid">
					<?php foreach ( $context['tag_styles'] as $style ) : ?>
						<div
							class="pbb-tag-style-card"
							data-wp-context='{"tagStyleId": "<?php echo esc_js( $style['id'] ); ?>"}'
							data-wp-on--click="actions.setTagStyle"
							data-wp-class--selected="state.isTagStyleSelected"
							data-tag-style-id="<?php echo esc_attr( $style['id'] ); ?>"
						>
							<?php if ( ! empty( $style['preview_url'] ) ) : ?>
								<img src="<?php echo esc_url( $style['preview_url'] ); ?>" alt="<?php echo esc_attr( $style['id'] ); ?>" class="pbb-tag-preview-image">
							<?php else : ?>
								<div class="pbb-tag-color-swatches">
									<span class="pbb-color-swatch pbb-tag-color" style="background-color: <?php echo esc_attr( $style['tag_color'] ); ?>"></span>
									<span class="pbb-color-swatch pbb-base-color" style="background-color: <?php echo esc_attr( $style['base_color'] ); ?>"></span>
								</div>
							<?php endif; ?>
							<div class="pbb-tag-style-name">
								<?php
								// Convert ID to readable name (e.g., 'red-white' -> 'Red & White').
								echo esc_html( ucwords( str_replace( '-', ' & ', $style['id'] ) ) );
								?>
							</div>
							<span class="pbb-checkmark">✓</span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

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
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
