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
				<?php esc_html_e( ' addons', 'party-bag-builder' ); ?>
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
				<input type="checkbox">Add personalized 3D printed name tags

				<h3 class="pbb-subsection-title"><?php esc_html_e( '3D Printed Name Tags', 'party-bag-builder' ); ?></h3>
				<p class="pbb-subsection-description">
					<?php esc_html_e( 'Personalize each bag with a custom name tag', 'party-bag-builder' ); ?>
				</p>

				<div class="pbb-tag-style-wrapper">
					<h4><?php esc_html_e( 'Choose Your Tag Style', 'party-bag-builder' ); ?></h4>
					<div class="pbb-product-grid">
						<template data-wp-each--style="context.tag_styles">
							<div
								class="pbb-product-card pbb-selectable-card"
								data-wp-on--click="actions.setTagStyle"
								data-wp-class--selected="state.isTagStyleSelected"
							>
								<div class="pbb-tag-style-color" data-wp-bind--data-color="context.style.id">
									<span><?php esc_html_e( 'Name', 'party-bag-builder' ); ?></span>
								</div>
								<p class="pbb-tag-style-name" data-wp-text="context.style.name"></p>
							</div>
						</template>
					</div>
					<p>*<?php esc_html_e( 'Note: Colors are for reference only and may vary slightly from the actual product.', 'party-bag-builder' ); ?></p>

					<div
						<template data-wp-each--name="state.kidNames">
							<div>
								<label>Bag #<span data-wp-text="context.name.i + 1"></span></label>
								<input
									type="text"
									placeholder="Enter child's name"
									data-wp-model="context.name"
								/>
								<p>0/20 characters</p>
							</div>
						</template>


						<div>
							<label>Bag #1</label>
							<input type="text" placeholder="Enter child's name" value="">
							<p>0/20 characters</p>
						</div>
						<div>
							<label>Bag #1</label>
							<input type="text" placeholder="Enter child's name" value="">
							<p>0/20 characters</p>
						</div>
					</div>
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
