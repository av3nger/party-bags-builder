<?php
/**
 * Step 5 - Kid Names
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-5" data-wp-bind--hidden="context.currentStep !== 5">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Enter Kids Names', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Personalize each party bag with the child\'s name (optional but recommended for name tags)', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-help-text">
			<span class="pbb-help-icon">ℹ️</span>
			<?php esc_html_e( '15 characters max, letters and numbers only', 'party-bag-builder' ); ?>
		</div>

		<div class="pbb-names-grid">
			<?php
			// We'll render a template for name inputs that will be repeated via Interactivity API
			// For now, render a reasonable number server-side (e.g., 10) and let JS handle more.
			for ( $i = 0; $i < 10; $i++ ) :
				?>
				<div class="pbb-name-input-wrapper" data-wp-bind--hidden="<?php echo esc_attr( $i ); ?> >= context.kidCount">
					<label for="pbb-kid-name-<?php echo esc_attr( $i ); ?>" class="pbb-name-label">
						<?php
						/* translators: %d: kid number */
						echo esc_html( sprintf( __( 'Kid %d', 'party-bag-builder' ), $i + 1 ) );
						?>
					</label>
					<input
						type="text"
						id="pbb-kid-name-<?php echo esc_attr( $i ); ?>"
						class="pbb-name-input"
						maxlength="15"
						pattern="[A-Za-z0-9 ]+"
						data-index="<?php echo esc_attr( $i ); ?>"
						data-wp-on--input="actions.updateKidName"
						data-wp-bind--value="context.kidNames[<?php echo esc_attr( $i ); ?>] || ''"
						placeholder="<?php esc_attr_e( 'Enter name...', 'party-bag-builder' ); ?>"
					/>
				</div>
			<?php endfor; ?>

			<!-- Additional inputs for 11-50 (rendered conditionally) -->
			<?php for ( $i = 10; $i < 50; $i++ ) : ?>
				<div class="pbb-name-input-wrapper" data-wp-bind--hidden="<?php echo esc_attr( $i ); ?> >= context.kidCount">
					<label for="pbb-kid-name-<?php echo esc_attr( $i ); ?>" class="pbb-name-label">
						<?php
						/* translators: %d: kid number */
						echo esc_html( sprintf( __( 'Kid %d', 'party-bag-builder' ), $i + 1 ) );
						?>
					</label>
					<input
						type="text"
						id="pbb-kid-name-<?php echo esc_attr( $i ); ?>"
						class="pbb-name-input"
						maxlength="15"
						pattern="[A-Za-z0-9 ]+"
						data-index="<?php echo esc_attr( $i ); ?>"
						data-wp-on--input="actions.updateKidName"
						data-wp-bind--value="context.kidNames[<?php echo esc_attr( $i ); ?>] || ''"
						placeholder="<?php esc_attr_e( 'Enter name...', 'party-bag-builder' ); ?>"
					/>
				</div>
			<?php endfor; ?>
		</div>

		<div class="pbb-validation-message" data-wp-bind--hidden="context.errors.length === 0">
			<span class="pbb-error-icon">⚠️</span>
			<span data-wp-text="context.errors[0]"></span>
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
			>
				<?php esc_html_e( 'Review Order', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
