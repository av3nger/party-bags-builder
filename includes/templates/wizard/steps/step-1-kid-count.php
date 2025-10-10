<?php
/**
 * Step 1 - Kid Count
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-1" data-wp-context='{"step": {"id": 1}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'How many kids are attending?', 'party-bag-builder' ); ?></h2>
		<p><?php esc_html_e( 'Select the number of party bags you need', 'party-bag-builder' ); ?></p>

		<div class="pbb-kid-count-input">
			<button data-wp-on--click="actions.decrementKidCount">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M5 12h14"></path>
				</svg>
			</button>
			<input
				type="number"
				id="pbb-kid-count"
				class="pbb-number-input"
				min="1"
				max="50"
				step="1"
				data-wp-on--input="actions.setKidCount"
				data-wp-bind--value="state.kidCount"
				placeholder="5"
			/>
			<button data-wp-on--click="actions.incrementKidCount">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M5 12h14"></path>
					<path d="M12 5v14"></path>
				</svg>
			</button>
		</div>

		<label for="pbb-kid-count">
			<?php esc_html_e( 'kids', 'party-bag-builder' ); ?>
		</label>

		<div class="pbb-step-navigation">
			<button
				type="button"
				class="pbb-button pbb-button-primary pbb-button-next"
				data-wp-on--click="actions.nextStep"
				data-wp-bind--disabled="!state.kidCount"
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
