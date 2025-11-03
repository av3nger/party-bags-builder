<?php
/**
 * Step 3 - Bag Selection
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with bags.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-3" data-wp-context='{"step": {"id": 3}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Choose Your Theme', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-subheading"><?php esc_html_e( 'Pick one design to match your party theme. Each bag is included in the price - no extra cost for choosing your favourite look.', 'party-bag-builder' ); ?></p>

		<?php if ( ! empty( $context['bags'] ) ) : ?>
			<div class="pbb-product-grid">
				<template data-wp-each--bag="context.bags">
					<div
						class="pbb-product-card pbb-selectable-card"
						data-wp-class--selected="state.isBagSelected"
						data-wp-on--click="actions.selectBag"
					>
						<div class="pbb-product-image">
							<img data-wp-bind--src="context.bag.image_url" data-wp-bind--alt="context.bag.name" />
							<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isBagSelected">
								<div class="pbb-product-image-check">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 6 9 17l-5-5"></path>
									</svg>
								</div>
							</div>
						</div>

						<div class="pbb-product-info">
							<h4 class="pbb-product-name" data-wp-text="context.bag.name"></h4>
							<div class="pbb-price-badge pbb-price-badge-included">
								<span class="pbb-price-amount">
									<?php esc_html_e( 'Included', 'party-bag-builder' ); ?>
								</span>
							</div>
						</div>
					</div>
				</template>

				<!-- Custom tile for contact form -->
				<a href="/contact-us" class="pbb-product-card pbb-selectable-card pbb-custom-tile">
					<div class="pbb-product-image pbb-custom-image">
						<div class="pbb-custom-content">
							<span class="pbb-custom-text"><?php esc_html_e( 'Custom', 'party-bag-builder' ); ?></span>
						</div>
					</div>

					<div class="pbb-product-info">
						<h4 class="pbb-product-name"><?php esc_html_e( 'Need Something Unique?', 'party-bag-builder' ); ?></h4>
						<div class="pbb-price-badge">
							<span class="pbb-price-amount">
								<?php esc_html_e( 'Contact Us', 'party-bag-builder' ); ?>
							</span>
						</div>
					</div>
				</a>
			</div>
		<?php else : ?>
			<p class="pbb-info-message">
				<?php esc_html_e( 'No bags available at the moment.', 'party-bag-builder' ); ?>
			</p>
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
				data-wp-bind--disabled="!state.canGoToToysStep"
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
