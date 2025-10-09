<?php
/**
 * Step 5 - Review & Summary
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-5" data-wp-context='{"step": {"id": 5}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Review Your Order', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Please review your selections before adding to cart', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-review-sections">
			<!-- Kid Count Summary -->
			<div class="pbb-review-section">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">👥</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Number of Party Bags', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="1"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value">
					<span data-wp-text="state.kidCount">0</span>
					<?php esc_html_e( ' bags', 'party-bag-builder' ); ?>
				</div>
			</div>

			<!-- Tier Summary -->
			<div class="pbb-review-section">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">⭐</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Selected Tier', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="2"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value">
					<span data-wp-text="state.tierConfig?.name"><?php esc_html_e( 'None', 'party-bag-builder' ); ?></span>
					<span class="pbb-review-meta">
						($<span data-wp-text="state.tierBasePrice">0.00</span>
						<?php esc_html_e( ' per bag', 'party-bag-builder' ); ?>)
					</span>
				</div>
			</div>

			<!-- Toys Summary -->
			<div class="pbb-review-section">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">🎁</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Selected Toys', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="3"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value">
					<span data-wp-text="state.selectedToys.length">0</span>
					<?php esc_html_e( ' toy(s) selected', 'party-bag-builder' ); ?>
				</div>
			</div>

			<!-- Addons Summary -->
			<div class="pbb-review-section">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">✨</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Add-ons', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="4"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value">
					<span data-wp-text="state.selectedAddons.length">0</span>
					<?php esc_html_e( ' addon(s) selected', 'party-bag-builder' ); ?>
					<span class="pbb-review-meta" data-wp-bind--hidden="!state.freeAddonsCount">
						(<span data-wp-text="state.freeAddonsCount">0</span>
						<?php esc_html_e( ' FREE', 'party-bag-builder' ); ?>)
					</span>
				</div>
			</div>

			<!-- Tag Style Summary -->
			<div class="pbb-review-section" data-wp-bind--hidden="!state.selectedTagStyle">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">🏷️</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Tag Style', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="4"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value">
					<span data-wp-text="state.selectedTagStyle"></span>
				</div>
			</div>

			<!-- Names Summary -->
			<div class="pbb-review-section">
				<div class="pbb-review-header">
					<span class="pbb-review-icon">📝</span>
					<h3 class="pbb-review-label"><?php esc_html_e( 'Kids Names', 'party-bag-builder' ); ?></h3>
					<button
						type="button"
						class="pbb-edit-button"
						data-wp-on--click="actions.goToStep"
						data-step="5"
					>
						<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
					</button>
				</div>
				<div class="pbb-review-value pbb-review-names">
					<span data-wp-text="state.kidNamesDisplay">
						<?php esc_html_e( 'No names provided', 'party-bag-builder' ); ?>
					</span>
				</div>
			</div>
		</div>

		<!-- Price Breakdown -->
		<div class="pbb-price-breakdown">
			<h3 class="pbb-breakdown-title"><?php esc_html_e( 'Price Breakdown', 'party-bag-builder' ); ?></h3>

			<div class="pbb-breakdown-row">
				<span class="pbb-breakdown-label">
					<?php esc_html_e( 'Base Price', 'party-bag-builder' ); ?>
					(<span data-wp-text="state.kidCount">0</span> ×
					$<span data-wp-text="state.tierBasePrice">0.00</span>)
				</span>
				<span class="pbb-breakdown-value">
					$<span data-wp-text="state.breakdownBasePrice">0.00</span>
				</span>
			</div>

			<div class="pbb-breakdown-row" data-wp-bind--hidden="state.priceBreakdown.addons === 0">
				<span class="pbb-breakdown-label">
					<?php esc_html_e( 'Paid Add-ons', 'party-bag-builder' ); ?>
				</span>
				<span class="pbb-breakdown-value">
					$<span data-wp-text="state.breakdownAddonsPrice">0.00</span>
				</span>
			</div>

			<div class="pbb-breakdown-row pbb-breakdown-total">
				<span class="pbb-breakdown-label"><?php esc_html_e( 'Total', 'party-bag-builder' ); ?></span>
				<span class="pbb-breakdown-value">
					$<span data-wp-text="state.breakdownTotalPrice">0.00</span>
				</span>
			</div>
		</div>

		<!-- Error Display -->
		<div class="pbb-validation-message pbb-error-message" data-wp-bind--hidden="state.errors.length === 0">
			<span class="pbb-error-icon">⚠️</span>
			<span data-wp-text="state.errors[0]"></span>
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
				class="pbb-button pbb-button-primary pbb-button-add-to-cart"
				data-wp-on--click="actions.addToCart"
				data-wp-bind--disabled="state.isLoading"
			>
				<span data-wp-text="state.addToCartButtonText">
					<?php esc_html_e( 'Add to Cart', 'party-bag-builder' ); ?>
				</span>
				<span class="pbb-button-icon" data-wp-bind--hidden="state.isLoading">🛒</span>
				<span class="pbb-button-spinner" data-wp-bind--hidden="!state.isLoading">⏳</span>
			</button>
		</div>
	</div>
</div>
