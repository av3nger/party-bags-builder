<?php
/**
 * Step 3 - Toy Selection
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with toys.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-3" data-wp-context='{"step": {"id": 3}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Select Your Toys', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Choose toys for your party bags', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-selection-counter">
			<span class="pbb-counter-label"><?php esc_html_e( 'Selected:', 'party-bag-builder' ); ?></span>
			<span class="pbb-counter-value">
				<span data-wp-text="state.selectedToys.length">0</span>
				<?php esc_html_e( ' of ', 'party-bag-builder' ); ?>
				<span data-wp-text="state.maxToysAllowed">0</span>
			</span>
		</div>

		<?php if ( ! empty( $context['toys'] ) ) : ?>
			<div class="pbb-product-grid">
				<?php foreach ( $context['toys'] as $toy ) : ?>
					<div class="pbb-product-card pbb-selectable-card" data-wp-context='{"toyId": <?php echo esc_js( $toy['id'] ); ?>}'>
						<label class="pbb-product-label">
							<input
								type="checkbox"
								class="pbb-product-checkbox"
								value="<?php echo esc_attr( $toy['id'] ); ?>"
								data-wp-on--change="actions.toggleToy"
								data-wp-bind--checked="state.isToySelected"
								data-wp-bind--disabled="state.isToyInputDisabled"
							/>

							<span class="pbb-product-content">
								<?php if ( ! empty( $toy['image_url'] ) ) : ?>
									<img src="<?php echo esc_url( $toy['image_url'] ); ?>" alt="<?php echo esc_attr( $toy['name'] ); ?>" class="pbb-product-image">
								<?php else : ?>
									<div class="pbb-product-image pbb-placeholder-image">
										<span class="pbb-placeholder-icon">🎁</span>
									</div>
								<?php endif; ?>

								<div class="pbb-product-info">
									<h4 class="pbb-product-name"><?php echo esc_html( $toy['name'] ); ?></h4>

									<?php if ( ! empty( $toy['description'] ) ) : ?>
										<p class="pbb-product-description"><?php echo esc_html( wp_trim_words( $toy['description'], 15 ) ); ?></p>
									<?php endif; ?>
								</div>
							</span>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="pbb-error-message">
				<p><?php esc_html_e( 'No toys available at the moment. Please contact the store administrator.', 'party-bag-builder' ); ?></p>
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
				data-wp-bind--disabled="state.selectedToys.length !== state.maxToysAllowed"
			>
				<?php esc_html_e( 'Next Step', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
