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
	data-wp-bind--hidden="state.isStepOne"
>
	<div class="pbb-price-display-inner">
		<div class="pbb-price-summary">
			<span class="pbb-price-label"><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></span>
			<span class="pbb-price-amount">
				$<span data-wp-text="state.breakdownTotalPrice">0.00</span>
			</span>
		</div>
	</div>
</div>
