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

<div class="pbb-step pbb-step-2" data-wp-context='{"step": {"id": 2}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Choose Your Tier', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Select the party bag tier that best fits your needs', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-tier-grid">
			<template data-wp-each--tier="context.tiers">
				<div
					class="pbb-tier-card"
					data-wp-on--click="actions.selectTier"
					data-wp-class--selected="state.isTierSelected"
				>
					<div class="pbb-tier-header">
						<h3 class="pbb-tier-name" data-wp-text="context.tier.name"></h3>
						<div class="pbb-tier-price">
							$<span data-wp-text="context.tier.base_price"></span>.00
							<span class="pbb-tier-price-label"><?php esc_html_e( 'per bag', 'party-bag-builder' ); ?></span>
						</div>
					</div>

					<ul class="pbb-tier-features">
						<li><?php esc_html_e( 'All base items included', 'party-bag-builder' ); ?></li>
						<li data-wp-text="state.includedToysLabel"></li>
						<li data-wp-text="state.includedAddonsLabel" data-wp-bind--hidden="!state.freeAddonsAllowed"></li>
						<li data-wp-text="state.totalAddonsLabel"></li>
					</ul>

					<div class="pbb-tier-total">
						<div class="pbb-total-label"><?php esc_html_e( 'Your total:', 'party-bag-builder' ); ?></div>
						<div class="pbb-total-amount">
							$<span data-wp-text="state.currentTierPrice">0.00</span>
						</div>
					</div>
				</div>
			</template>
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
