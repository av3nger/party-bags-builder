<?php
/**
 * Step 4 - Toy Selection
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with toys.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-4" data-wp-context='{"step": {"id": 4}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Select Your Toys', 'party-bag-builder' ); ?></h2>
		<p><?php esc_html_e( 'Choose toys for your party bags', 'party-bag-builder' ); ?></p>

		<div class="pbb-selection-counter">
			<span class="pbb-counter-label"><?php esc_html_e( 'Selected:', 'party-bag-builder' ); ?></span>
			<span class="pbb-counter-value">
				<span data-wp-text="state.selectedToys.length">0</span>
				<?php esc_html_e( ' of ', 'party-bag-builder' ); ?>
				<span data-wp-text="state.maxToysAllowed">0</span>
			</span>
		</div>

		<?php if ( ! empty( $context['toys'] ) ) : ?>
			<div class="pbb-product-grid">
				<template data-wp-each--toy="context.toys">
					<div
						class="pbb-product-card pbb-selectable-card"
						data-wp-class--selected="state.isToySelected"
						data-wp-class--disabled="state.isToyInputDisabled"
						data-wp-on--click="actions.toggleToy"
					>
						<div class="pbb-product-image">
							<img data-wp-bind--src="context.toy.image_url" data-wp-bind--alt="context.toy.name" />
							<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isToySelected">
								<div class="pbb-product-image-check">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 6 9 17l-5-5"></path>
									</svg>
								</div>
							</div>
							<div class="pbb-product-image-disabled" data-wp-bind--hidden="!state.isToyInputDisabled">
								<span><?php esc_html_e( 'Max selected', 'party-bag-builder' ); ?></span>
							</div>
						</div>

						<div class="pbb-product-info">
							<h4 class="pbb-product-name" data-wp-text="context.toy.name"></h4>
						</div>
					</div>
				</template>
			</div>
		<?php else : ?>
			<div class="pbb-error-message">
				<p><?php esc_html_e( 'No toys available at the moment. Please contact the store administrator.', 'party-bag-builder' ); ?></p>
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
				data-wp-bind--disabled="!state.canGoToAddonsStep"
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
