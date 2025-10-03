<?php
/**
 * Product Card Component
 *
 * Reusable template for displaying product cards in the wizard.
 *
 * @package PartyBagBuilder
 *
 * @var array  $product        Product data (id, name, description, image_url, price, stock_quantity).
 * @var bool   $show_checkbox  Whether to show checkbox for selection.
 * @var bool   $show_price     Whether to show price badge.
 * @var string $price_label    Custom price label (e.g., '+$X.XX per bag').
 * @var string $checkbox_action Interactivity API action for checkbox (e.g., 'actions.toggleToy').
 * @var string $checked_condition Interactivity API condition for checked state.
 * @var string $disabled_condition Interactivity API condition for disabled state.
 */

defined( 'ABSPATH' ) || exit;

// Set defaults.
$show_checkbox      = $show_checkbox ?? false;
$show_price         = $show_price ?? false;
$price_label        = $price_label ?? '';
$checkbox_action    = $checkbox_action ?? '';
$checked_condition  = $checked_condition ?? '';
$disabled_condition = $disabled_condition ?? '';
?>

<div class="pbb-product-card pbb-selectable-card">
	<?php if ( $show_checkbox ) : ?>
		<label class="pbb-product-label">
			<input
				type="checkbox"
				class="pbb-product-checkbox"
				value="<?php echo esc_attr( $product['id'] ); ?>"
				<?php if ( $checkbox_action ) : ?>
					data-wp-on--change="<?php echo esc_attr( $checkbox_action ); ?>"
				<?php endif; ?>
				<?php if ( $checked_condition ) : ?>
					data-wp-bind--checked="<?php echo esc_attr( $checked_condition ); ?>"
				<?php endif; ?>
				<?php if ( $disabled_condition ) : ?>
					data-wp-bind--disabled="<?php echo esc_attr( $disabled_condition ); ?>"
				<?php endif; ?>
			/>

			<span class="pbb-product-content">
	<?php else : ?>
		<div class="pbb-product-content">
	<?php endif; ?>

	<?php if ( ! empty( $product['image_url'] ) ) : ?>
		<img
			src="<?php echo esc_url( $product['image_url'] ); ?>"
			alt="<?php echo esc_attr( $product['name'] ); ?>"
			class="pbb-product-image"
		/>
	<?php else : ?>
		<div class="pbb-product-image pbb-placeholder-image">
			<span class="pbb-placeholder-icon">📦</span>
		</div>
	<?php endif; ?>

	<div class="pbb-product-info">
		<h4 class="pbb-product-name"><?php echo esc_html( $product['name'] ); ?></h4>

		<?php if ( ! empty( $product['description'] ) ) : ?>
			<p class="pbb-product-description">
				<?php echo esc_html( wp_trim_words( $product['description'], 15 ) ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $show_price && isset( $product['price'] ) ) : ?>
			<div class="pbb-price-badge">
				<?php if ( $price_label ) : ?>
					<span class="pbb-price-label"><?php echo esc_html( $price_label ); ?></span>
				<?php else : ?>
					<span class="pbb-price-amount">
						<?php
						/* translators: %s: price */
						echo esc_html( sprintf( __( '$%s', 'party-bag-builder' ), number_format( $product['price'], 2 ) ) );
						?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $product['stock_quantity'] ) ) : ?>
			<?php if ( $product['stock_quantity'] < 20 && $product['stock_quantity'] > 0 ) : ?>
				<span class="pbb-stock-badge pbb-stock-low">
					<?php
					/* translators: %d: stock quantity */
					echo esc_html( sprintf( __( 'Only %d left', 'party-bag-builder' ), $product['stock_quantity'] ) );
					?>
				</span>
			<?php elseif ( $product['stock_quantity'] > 0 ) : ?>
				<span class="pbb-stock-badge pbb-stock-available">
					<?php esc_html_e( 'In Stock', 'party-bag-builder' ); ?>
				</span>
			<?php else : ?>
				<span class="pbb-stock-badge pbb-stock-out">
					<?php esc_html_e( 'Out of Stock', 'party-bag-builder' ); ?>
				</span>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<?php if ( $show_checkbox ) : ?>
		<span class="pbb-checkmark">✓</span>
	<?php endif; ?>

	<?php if ( $show_checkbox ) : ?>
			</span>
		</label>
	<?php else : ?>
		</div>
	<?php endif; ?>
</div>
