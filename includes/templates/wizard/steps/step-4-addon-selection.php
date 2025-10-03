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

<div class="pbb-step pbb-step-4" data-wp-bind--hidden="context.currentStep !== 4">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Add Extra Goodies (Optional)', 'party-bag-builder' ); ?></h2>
		<p class="pbb-step-description">
			<?php esc_html_e( 'Enhance your party bags with premium add-ons', 'party-bag-builder' ); ?>
		</p>

		<div class="pbb-selection-counter">
			<span class="pbb-counter-label"><?php esc_html_e( 'Selected:', 'party-bag-builder' ); ?></span>
			<span class="pbb-counter-value">
				<span data-wp-text="context.selectedAddons.length">0</span>
				<?php esc_html_e( ' of ', 'party-bag-builder' ); ?>
				<span data-wp-text="context.tierConfig?.includes?.max_addons || 0">0</span>
				<?php esc_html_e( ' max', 'party-bag-builder' ); ?>
			</span>
			<span class="pbb-free-addons" data-wp-bind--hidden="!(context.tierConfig?.includes?.free_addons > 0)">
				(<span data-wp-text="context.tierConfig?.includes?.free_addons || 0">0</span>
				<?php esc_html_e( ' FREE', 'party-bag-builder' ); ?>)
			</span>
		</div>

		<?php if ( ! empty( $context['addons'] ) ) : ?>
			<div class="pbb-product-grid">
				<?php foreach ( $context['addons'] as $addon ) : ?>
					<div class="pbb-product-card pbb-selectable-card">
						<label class="pbb-product-label">
							<input
								type="checkbox"
								class="pbb-product-checkbox"
								value="<?php echo esc_attr( $addon['id'] ); ?>"
								data-wp-on--change="actions.toggleAddon"
								data-wp-bind--checked="context.selectedAddons.includes(<?php echo esc_attr( $addon['id'] ); ?>)"
								data-wp-bind--disabled="!context.selectedAddons.includes(<?php echo esc_attr( $addon['id'] ); ?>) && context.selectedAddons.length >= (context.tierConfig?.includes?.max_addons || 0)"
							/>

							<span class="pbb-product-content">
								<?php if ( ! empty( $addon['image_url'] ) ) : ?>
									<img src="<?php echo esc_url( $addon['image_url'] ); ?>" alt="<?php echo esc_attr( $addon['name'] ); ?>" class="pbb-product-image">
								<?php else : ?>
									<div class="pbb-product-image pbb-placeholder-image">
										<span class="pbb-placeholder-icon">⭐</span>
									</div>
								<?php endif; ?>

								<div class="pbb-product-info">
									<h4 class="pbb-product-name"><?php echo esc_html( $addon['name'] ); ?></h4>

									<?php if ( ! empty( $addon['description'] ) ) : ?>
										<p class="pbb-product-description"><?php echo esc_html( wp_trim_words( $addon['description'], 15 ) ); ?></p>
									<?php endif; ?>

									<div class="pbb-price-badge">
										<span class="pbb-price-amount">
											<?php
											/* translators: %s: price */
											echo esc_html( sprintf( __( '+$%s per bag', 'party-bag-builder' ), number_format( $addon['price'], 2 ) ) );
											?>
										</span>
										<!-- This will be shown dynamically if addon is marked free -->
										<span class="pbb-free-indicator" data-wp-bind--hidden="!context.priceBreakdown.freeAddons.includes(<?php echo esc_attr( $addon['id'] ); ?>)">
											<?php esc_html_e( 'FREE (included)', 'party-bag-builder' ); ?>
										</span>
									</div>

									<?php if ( $addon['stock_quantity'] < 20 && $addon['stock_quantity'] > 0 ) : ?>
										<span class="pbb-stock-badge pbb-stock-low">
											<?php
											/* translators: %d: stock quantity */
											echo esc_html( sprintf( __( 'Only %d left', 'party-bag-builder' ), $addon['stock_quantity'] ) );
											?>
										</span>
									<?php endif; ?>
								</div>

								<span class="pbb-checkmark">✓</span>
							</span>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="pbb-info-message">
				<?php esc_html_e( 'No add-ons available at the moment. You can continue to the next step.', 'party-bag-builder' ); ?>
			</p>
		<?php endif; ?>

		<!-- Tag Style Selection (shown when applicable) -->
		<?php if ( ! empty( $context['tag_styles'] ) ) : ?>
			<div class="pbb-tag-style-section" data-wp-bind--hidden="context.selectedAddons.length === 0">
				<h3 class="pbb-subsection-title"><?php esc_html_e( 'Choose Your Tag Style', 'party-bag-builder' ); ?></h3>
				<p class="pbb-subsection-description">
					<?php esc_html_e( 'Select a color style for personalized name tags', 'party-bag-builder' ); ?>
				</p>

				<div class="pbb-tag-styles-grid">
					<?php foreach ( $context['tag_styles'] as $style ) : ?>
						<div
							class="pbb-tag-style-card"
							data-wp-on--click="actions.setTagStyle"
							data-wp-class--selected="context.selectedTagStyle === '<?php echo esc_attr( $style['id'] ); ?>'"
							data-tag-style-id="<?php echo esc_attr( $style['id'] ); ?>"
						>
							<?php if ( ! empty( $style['preview_url'] ) ) : ?>
								<img src="<?php echo esc_url( $style['preview_url'] ); ?>" alt="<?php echo esc_attr( $style['id'] ); ?>" class="pbb-tag-preview-image">
							<?php else : ?>
								<div class="pbb-tag-color-swatches">
									<span class="pbb-color-swatch pbb-tag-color" style="background-color: <?php echo esc_attr( $style['tag_color'] ); ?>"></span>
									<span class="pbb-color-swatch pbb-base-color" style="background-color: <?php echo esc_attr( $style['base_color'] ); ?>"></span>
								</div>
							<?php endif; ?>
							<div class="pbb-tag-style-name">
								<?php
								// Convert ID to readable name (e.g., 'red-white' -> 'Red & White').
								echo esc_html( ucwords( str_replace( '-', ' & ', $style['id'] ) ) );
								?>
							</div>
							<span class="pbb-checkmark">✓</span>
						</div>
					<?php endforeach; ?>
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
