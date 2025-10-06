<?php
/**
 * Price Display Component
 *
 * Sticky price summary that shows the running total.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;
?>

<div
	class="pbb-price-display"
	data-wp-bind--hidden="state.isStep1"
>
	<div class="pbb-price-display-inner">
		<div class="pbb-price-summary">
			<span class="pbb-price-label"><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></span>
			<span class="pbb-price-amount">
				$<span data-wp-text="state.breakdownTotalPrice">0.00</span>
			</span>
		</div>

		<!-- Expandable Breakdown (Optional) -->
		<div class="pbb-price-breakdown-toggle" data-wp-on--click="actions.togglePriceBreakdown">
			<span class="pbb-breakdown-icon">ℹ️</span>
			<span class="pbb-breakdown-text"><?php esc_html_e( 'View Breakdown', 'party-bag-builder' ); ?></span>
		</div>

		<div class="pbb-price-breakdown-detail" data-wp-bind--hidden="!state.showPriceBreakdown">
			<div class="pbb-breakdown-row">
				<span class="pbb-breakdown-label">
					<?php esc_html_e( 'Base Price', 'party-bag-builder' ); ?>
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

			<div class="pbb-breakdown-row pbb-breakdown-subtotal" data-wp-bind--hidden="!state.freeAddonsCount">
				<span class="pbb-breakdown-label">
					<?php esc_html_e( 'Free Add-ons', 'party-bag-builder' ); ?>
					(<span data-wp-text="state.freeAddonsCount">0</span>)
				</span>
				<span class="pbb-breakdown-value pbb-free-value">
					<?php esc_html_e( 'FREE', 'party-bag-builder' ); ?>
				</span>
			</div>
		</div>
	</div>
</div>
