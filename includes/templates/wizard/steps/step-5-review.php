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
		<p><?php esc_html_e( 'Please review your selections before adding to cart', 'party-bag-builder' ); ?></p>

		<div class="pbb-review-wrapper">
			<div class="pbb-review-sections">
				<!-- Kid Count Summary -->
				<div class="pbb-review-section">
					<div class="pbb-review-header">
						<div class="pbb-review-header-title">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
								<circle cx="9" cy="7" r="4"></circle>
								<path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
								<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
							</svg>
							<h3 class="pbb-review-label"><?php esc_html_e( 'Party Details', 'party-bag-builder' ); ?></h3>
						</div>

						<button
							type="button"
							class="pbb-edit-button"
							data-wp-on--click="actions.goToStep"
							data-step="1"
						>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
							</svg>
							<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
						</button>
					</div>
					<div class="pbb-review-value">
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Number of kids:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.kidCount">0</span>
						</div>
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Selected tier:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.tierConfig.name"><?php esc_html_e( 'None', 'party-bag-builder' ); ?></span>
						</div>
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Price per bag:', 'party-bag-builder' ); ?></span>
							<span>$<span data-wp-text="state.tierBasePrice">0.00</span></span>
						</div>
					</div>
				</div>

				<!-- Toys Summary -->
				<div class="pbb-review-section">
					<div class="pbb-review-header">
						<div class="pbb-review-header-title">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="8" width="18" height="4" rx="1"></rect>
								<path d="M12 8v13"></path>
								<path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path>
								<path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
							</svg>
							<h3 class="pbb-review-label"><?php esc_html_e( 'Selected Toys', 'party-bag-builder' ); ?></h3>
						</div>

						<button
							type="button"
							class="pbb-edit-button"
							data-wp-on--click="actions.goToStep"
							data-step="3"
						>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
							</svg>
							<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
						</button>
					</div>
					<div class="pbb-review-value">
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Toy(s) selected:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.selectedToys.length">0</span>
						</div>
					</div>
				</div>

				<!-- Addons Summary -->
				<div class="pbb-review-section">
					<div class="pbb-review-header">
						<div class="pbb-review-header-title">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="8" width="18" height="4" rx="1"></rect>
								<path d="M12 8v13"></path>
								<path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path>
								<path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
							</svg>
							<h3 class="pbb-review-label"><?php esc_html_e( 'Add-ons', 'party-bag-builder' ); ?></h3>
						</div>

						<button
							type="button"
							class="pbb-edit-button"
							data-wp-on--click="actions.goToStep"
							data-step="4"
						>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
							</svg>
							<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
						</button>
					</div>
					<div class="pbb-review-value">
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Addon(s) selected:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.selectedAddons.length">0</span>
						</div>
					</div>
				</div>

				<!-- Tag Style Summary -->
				<div class="pbb-review-section" data-wp-bind--hidden="!state.selectedTagStyle">
					<div class="pbb-review-header">
						<div class="pbb-review-header-title">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path>
								<circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
							</svg>
							<h3 class="pbb-review-label"><?php esc_html_e( 'Name Tags', 'party-bag-builder' ); ?></h3>
						</div>

						<button
							type="button"
							class="pbb-edit-button"
							data-wp-on--click="actions.goToStep"
							data-step="4"
						>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
							</svg>
							<?php esc_html_e( 'Edit', 'party-bag-builder' ); ?>
						</button>
					</div>
					<div class="pbb-review-value">
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Style:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.selectedTagStyle"></span>
						</div>
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Names:', 'party-bag-builder' ); ?></span>
							<span data-wp-text="state.kidNamesDisplay"></span>
						</div>
					</div>
				</div>
			</div>

			<!-- Price Breakdown -->
			<div class="pbb-price-breakdown">
				<div class="pbb-review-section">
					<div class="pbb-review-header">
						<h3 class="pbb-review-label"><?php esc_html_e( 'Price Breakdown', 'party-bag-builder' ); ?></h3>
					</div>
					<div class="pbb-review-value">
						<div class="pbb-review-value-line" data-wp-bind--hidden="state.priceBreakdown.addons === 0">
							<span>
								<?php esc_html_e( 'Base Price:', 'party-bag-builder' ); ?>
								(<span data-wp-text="state.kidCount">0</span> × $<span data-wp-text="state.tierBasePrice">0.00</span>)
							</span>
							<span>$<span data-wp-text="state.breakdownBasePrice">0.00</span></span>
						</div>
						<div class="pbb-review-value-line" data-wp-bind--hidden="state.priceBreakdown.addons === 0">
							<span><?php esc_html_e( 'Paid Add-ons:', 'party-bag-builder' ); ?></span>
							<span>$<span data-wp-text="state.breakdownAddonsPrice">0.00</span></span>
						</div>
						<div class="pbb-review-value-line">
							<span><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></span>
							<span>$<span data-wp-text="state.breakdownTotalPrice">0.00</span></span>
						</div>
					</div>
				</div>
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
