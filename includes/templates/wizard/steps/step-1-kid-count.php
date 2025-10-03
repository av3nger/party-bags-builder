<?php
/**
 * Step 1 - Kid Count
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-1" data-wp-context='{"stepNumber": 1}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'How many kids are attending?', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Select the number of party bags you need', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-kid-count-input">
			<label for="pbb-kid-count" class="screen-reader-text">
				<?php esc_html_e( 'Number of kids', 'party-bag-builder' ); ?>
			</label>
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
			<span class="pbb-input-label"><?php esc_html_e( 'kids', 'party-bag-builder' ); ?></span>
		</div>

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
