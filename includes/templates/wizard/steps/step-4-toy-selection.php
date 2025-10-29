<?php
/**
 * Step 4 - Toy Selection, Add-ons & Name Tags
 *
 * @package PartyBagBuilder
 *
 * @var array $context Template context with toys, addons, and tag_styles.
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="pbb-step pbb-step-4" data-wp-context='{"step": {"id": 4}}' data-wp-bind--hidden="!state.isCurrentStep">
	<div class="pbb-step-content">
		<h2 class="pbb-step-title"><?php esc_html_e( 'Build Your Party Bag', 'party-bag-builder' ); ?></h2>
		<p><?php esc_html_e( 'Choose toys and add extras for your party bags', 'party-bag-builder' ); ?></p>

		<?php if ( ! empty( $context['toysThemed'] ) ) : ?>
			<!-- Themed Toys Section -->
			<div class="pbb-toy-section" data-wp-bind--hidden="!state.showThemedToys">
				<div class="pbb-section-header">
					<h3 class="pbb-subsection-title"><?php esc_html_e( 'Themed Toys', 'party-bag-builder' ); ?></h3>

					<div class="pbb-selection-counter">
						<span data-wp-text="state.selectedThemedCount">0</span>
						/
						<span data-wp-text="state.maxThemedToys">0</span>
						<?php esc_html_e( 'selected', 'party-bag-builder' ); ?>
					</div>
				</div>

				<div class="pbb-product-grid">
					<template data-wp-each--toy="context.toysThemed">
						<div
							class="pbb-product-card pbb-selectable-card"
							data-wp-class--selected="state.isToySelected"
							data-wp-class--disabled="state.isThemedToyDisabled"
							data-wp-on--click="actions.toggleToy"
						>
							<div class="pbb-product-image">
								<img data-wp-bind--src="context.toy.image_url" data-wp-bind--alt="context.toy.name" />
								<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isToySelected">
									<div class="pbb-product-image-check">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M20 6 9 17l-5-5"></path>
										</svg>
									</div>
								</div>
								<div class="pbb-product-image-disabled" data-wp-bind--hidden="!state.isThemedToyDisabled">
									<span><?php esc_html_e( 'Max selected', 'party-bag-builder' ); ?></span>
								</div>
							</div>

							<div class="pbb-product-info">
								<h4 class="pbb-product-name" data-wp-text="context.toy.name"></h4>
							</div>
						</div>
					</template>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $context['toysGeneric'] ) ) : ?>
			<!-- Generic Toys Section -->
			<div class="pbb-toy-section" data-wp-bind--hidden="!state.showGenericToys">
				<div class="pbb-section-header">
					<h3 class="pbb-subsection-title"><?php esc_html_e( 'Generic Toys', 'party-bag-builder' ); ?></h3>

					<div class="pbb-selection-counter">
						<span data-wp-text="state.selectedGenericCount">0</span>
						/
						<span data-wp-text="state.maxGenericToys">0</span>
						<?php esc_html_e( 'selected', 'party-bag-builder' ); ?>
					</div>
				</div>

				<div class="pbb-accordion">
					<template data-wp-each--category="context.toyCategories">
						<div
							class="pbb-accordion-item"
							data-wp-class--open="state.isCategoryOpen"
						>
							<div
								class="pbb-accordion-header"
								data-wp-on--click="actions.toggleCategory"
							>
								<span data-wp-text="context.category.name"></span>
								<span class="pbb-accordion-icon">▼</span>
							</div>
							<div class="pbb-accordion-content" data-wp-bind--hidden="!state.isCategoryOpen">
								<div class="pbb-product-grid">
									<template data-wp-each--toy="context.toysGeneric">
										<div
											class="pbb-product-card pbb-selectable-card"
											data-wp-class--selected="state.isToySelected"
											data-wp-class--disabled="state.isGenericToyDisabled"
											data-wp-on--click="actions.toggleToy"
											data-wp-bind--hidden="!state.isToyInCategory"
										>
											<div class="pbb-product-image">
												<img data-wp-bind--src="context.toy.image_url" data-wp-bind--alt="context.toy.name" />
												<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isToySelected">
													<div class="pbb-product-image-check">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M20 6 9 17l-5-5"></path>
														</svg>
													</div>
												</div>
												<div class="pbb-product-image-disabled" data-wp-bind--hidden="!state.isGenericToyDisabled">
													<span><?php esc_html_e( 'Max selected', 'party-bag-builder' ); ?></span>
												</div>
											</div>

											<div class="pbb-product-info">
												<h4 class="pbb-product-name" data-wp-text="context.toy.name"></h4>
											</div>
										</div>
									</template>
								</div>
							</div>
						</div>
					</template>

					<!-- Miscellaneous category for uncategorized toys -->
					<div
						class="pbb-accordion-item"
						data-wp-context='{"category": {"slug": "misc", "name": "<?php echo esc_js( __( 'Miscellaneous', 'party-bag-builder' ) ); ?>"}}'
						data-wp-class--open="state.isCategoryOpen"
					>
						<div
							class="pbb-accordion-header"
							data-wp-on--click="actions.toggleCategory"
						>
							<span><?php esc_html_e( 'Miscellaneous', 'party-bag-builder' ); ?></span>
							<span class="pbb-accordion-icon">▼</span>
						</div>
						<div class="pbb-accordion-content" data-wp-bind--hidden="!state.isCategoryOpen">
							<div class="pbb-product-grid">
								<template data-wp-each--toy="context.toysGeneric">
									<div
										class="pbb-product-card pbb-selectable-card"
										data-wp-class--selected="state.isToySelected"
										data-wp-class--disabled="state.isGenericToyDisabled"
										data-wp-on--click="actions.toggleToy"
										data-wp-bind--hidden="!state.isToyUncategorized"
									>
										<div class="pbb-product-image">
											<img data-wp-bind--src="context.toy.image_url" data-wp-bind--alt="context.toy.name" />
											<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isToySelected">
												<div class="pbb-product-image-check">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
														<path d="M20 6 9 17l-5-5"></path>
													</svg>
												</div>
											</div>
											<div class="pbb-product-image-disabled" data-wp-bind--hidden="!state.isGenericToyDisabled">
												<span><?php esc_html_e( 'Max selected', 'party-bag-builder' ); ?></span>
											</div>
										</div>

										<div class="pbb-product-info">
											<h4 class="pbb-product-name" data-wp-text="context.toy.name"></h4>
										</div>
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( empty( $context['toysThemed'] ) && empty( $context['toysGeneric'] ) ) : ?>
			<div class="pbb-error-message">
				<p><?php esc_html_e( 'No toys available at the moment. Please contact the store administrator.', 'party-bag-builder' ); ?></p>
			</div>
		<?php endif; ?>

		<!-- Add-ons Section -->
		<div class="pbb-toy-section">
			<div class="pbb-section-header">
				<h3 class="pbb-subsection-title"><?php esc_html_e( 'Add Extra Goodies (Optional)', 'party-bag-builder' ); ?></h3>

				<div class="pbb-selection-counter">
					<span data-wp-text="state.selectedAddons.length">0</span>
					<?php esc_html_e( ' selected', 'party-bag-builder' ); ?>
				</div>
			</div>

			<?php if ( ! empty( $context['addons'] ) ) : ?>
				<div class="pbb-product-grid">
					<template data-wp-each--addon="context.addons">
						<div
							class="pbb-product-card pbb-selectable-card"
							data-wp-class--selected="state.isAddonSelected"
							data-wp-on--click="actions.toggleAddon"
						>
							<div class="pbb-product-image">
								<img data-wp-bind--src="context.addon.image_url" data-wp-bind--alt="context.addon.name" />
								<div class="pbb-product-image-cover" data-wp-bind--hidden="!state.isAddonSelected">
									<div class="pbb-product-image-check">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M20 6 9 17l-5-5"></path>
										</svg>
									</div>
								</div>
							</div>

							<div class="pbb-product-info">
								<h4 class="pbb-product-name" data-wp-text="context.addon.name"></h4>
								<div class="pbb-price-badge">
									<span class="pbb-price-amount">
										+$<span data-wp-text="context.addon.price"></span> <?php esc_html_e( 'per bag', 'party-bag-builder' ); ?>
									</span>
								</div>
							</div>
						</div>
					</template>
				</div>
			<?php else : ?>
				<p class="pbb-info-message">
					<?php esc_html_e( 'No add-ons available at the moment.', 'party-bag-builder' ); ?>
				</p>
			<?php endif; ?>
		</div>

		<!-- Tag Style Selection -->
		<?php if ( ! empty( $context['tag_styles'] ) ) : ?>
			<div class="pbb-tag-style-section" data-wp-bind--hidden="!state.hasFreeNameTag">
				<h3 class="pbb-subsection-title"><?php esc_html_e( '3D Printed Name Tags', 'party-bag-builder' ); ?></h3>
				<p><?php esc_html_e( 'Personalize each bag with a custom name tag', 'party-bag-builder' ); ?></p>

				<div class="pbb-tag-style-wrapper">
					<h4 class="pbb-tag-style-header"><?php esc_html_e( 'Choose Your Tag Style', 'party-bag-builder' ); ?></h4>
					<div class="pbb-product-grid">
						<template data-wp-each--style="context.tag_styles">
							<div
								class="pbb-product-card pbb-selectable-card"
								data-wp-on--click="actions.setTagStyle"
								data-wp-class--selected="state.isTagStyleSelected"
							>
								<div class="pbb-tag-style-color" data-wp-bind--data-color="context.style.id">
									<span><?php esc_html_e( 'Name', 'party-bag-builder' ); ?></span>
								</div>
								<p class="pbb-tag-style-name" data-wp-text="context.style.name"></p>
							</div>
						</template>
					</div>
					<p>*<?php esc_html_e( 'Note: Colors are for reference only and may vary slightly from the actual product.', 'party-bag-builder' ); ?></p>

					<div class="pbb-names-section" data-wp-bind--hidden="!state.selectedTagStyle">
						<h4><?php esc_html_e( 'Enter Names for Each Bag', 'party-bag-builder' ); ?></h4>

						<div class="pbb-names-grid" data-wp-on--input="actions.handleKidNameInput">
							<?php for ( $i = 0; $i < 50; $i++ ) : ?>
								<div
									class="pbb-name-input-wrapper"
									data-wp-context='{"inputIndex": <?php echo esc_attr( $i ); ?>}'
									data-wp-bind--hidden="!state.shouldShowInput"
								>
									<label for="pbb-name-<?php echo esc_attr( $i ); ?>">
										<?php esc_html_e( 'Bag #', 'party-bag-builder' ); ?><?php echo esc_html( $i + 1 ); ?>
									</label>
									<input
										id="pbb-name-<?php echo esc_attr( $i ); ?>"
										type="text"
										class="pbb-name-input"
										maxlength="20"
										pattern="[A-Za-z0-9 ]+"
										data-index="<?php echo esc_attr( $i ); ?>"
										data-wp-bind--value="state.getKidNameByIndex"
										placeholder="<?php esc_attr_e( 'Enter name...', 'party-bag-builder' ); ?>"
									/>
								</div>
							<?php endfor; ?>
						</div>
					</div>
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
				data-wp-bind--disabled="!state.canGoToReviewStep"
			>
				<?php esc_html_e( 'Review', 'party-bag-builder' ); ?>
				<span class="pbb-button-icon">→</span>
			</button>
		</div>
	</div>
</div>
