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
		<h2 class="pbb-step-title"><?php esc_html_e( 'Choose Your Party Tier', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-subheading"><?php esc_html_e( "Pick the perfect balance of fun and surprises for your party bags. Each tier includes the same base goodies - you just choose how many toys (and extras) you'd like to include.", 'party-bag-builder' ); ?></p>

		<div class="pbb-tier-grid">
			<template data-wp-each--tier="context.tiers">
				<div
					class="pbb-tier-card"
					data-wp-on--click="actions.selectTier"
					data-wp-class--selected="state.isTierSelected"
				>
					<h3 class="pbb-tier-name" data-wp-text="context.tier.name"></h3>

					<p data-wp-text="context.tier.description"></p>

					<div class="pbb-tier-price">
						$<span data-wp-text="context.tier.base_price"></span>.00
						<span class="pbb-tier-price-label"><?php esc_html_e( 'per bag', 'party-bag-builder' ); ?></span>
					</div>

					<p><?php esc_html_e( 'Each bag includes:', 'party-bag-builder' ); ?></p>

					<ul class="pbb-tier-features">
						<li><?php esc_html_e( 'Lolly, colourful ballon, sticker sheet', 'party-bag-builder' ); ?></li>
						<template data-wp-each--label="context.tier.label">
							<li data-wp-text="context.label"></li>
						</template>
						<li><?php esc_html_e( 'Optional add-ons available', 'party-bag-builder' ); ?></li>
					</ul>

					<div class="pbb-tier-total">
						<?php esc_html_e( 'Your total:', 'party-bag-builder' ); ?>
						<div class="pbb-total-amount">
							$<span data-wp-text="state.currentTierPrice">0.00</span>
						</div>
					</div>
				</div>
			</template>
		</div>

		<!-- Common Items Preview (shown when tier is selected) -->
		<div class="pbb-common-items-preview" data-wp-bind--hidden="!state.selectedTier">
			<h3><?php esc_html_e( 'Each party bag includes the following essentials for instant smiles:', 'party-bag-builder' ); ?></h3>

			<?php if ( ! empty( $context['common_items'] ) ) : ?>
				<div class="pbb-preview-grid">
					<?php foreach ( $context['common_items'] as $item ) : ?>
						<div class="pbb-product-card pbb-preview-card">
							<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
							<h4 class="pbb-product-name"><?php echo esc_html( $item['name'] ); ?></h4>
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
