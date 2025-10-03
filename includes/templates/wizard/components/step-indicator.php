<?php
/**
 * Step Indicator Component
 *
 * Displays progress through the wizard steps.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

$step_labels = array(
	1 => __( 'Kid Count', 'party-bag-builder' ),
	2 => __( 'Tier', 'party-bag-builder' ),
	3 => __( 'Toys', 'party-bag-builder' ),
	4 => __( 'Add-ons', 'party-bag-builder' ),
	5 => __( 'Names', 'party-bag-builder' ),
	6 => __( 'Review', 'party-bag-builder' ),
);
?>

<div class="pbb-step-indicator">
	<div class="pbb-step-indicator-inner">
		<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
			<div
				class="pbb-step-indicator-item"
				data-wp-context='{"stepNumber": <?php echo esc_js( $i ); ?>}'
				data-wp-class--active="state.isCurrentStep"
				data-wp-class--completed="state.isStepCompleted"
			>
				<span class="pbb-step-number"><?php echo esc_html( $i ); ?></span>
				<span class="pbb-step-label"><?php echo esc_html( $step_labels[ $i ] ); ?></span>
			</div>
		<?php endfor; ?>
	</div>
</div>
