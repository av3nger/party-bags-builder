<?php
/**
 * Step Indicator Component
 *
 * Displays progress through the wizard steps.
 *
 * @package PartyBagBuilder
 */

defined( 'ABSPATH' ) || exit;

$steps = array(
	array(
		'id'    => 1,
		'value' => __( 'Kid Count', 'party-bag-builder' ),
	),
	array(
		'id'    => 2,
		'value' => __( 'Tier', 'party-bag-builder' ),
	),
	array(
		'id'    => 3,
		'value' => __( 'Toys', 'party-bag-builder' ),
	),
	array(
		'id'    => 4,
		'value' => __( 'Add-ons', 'party-bag-builder' ),
	),
	array(
		'id'    => 5,
		'value' => __( 'Names', 'party-bag-builder' ),
	),
	array(
		'id'    => 6,
		'value' => __( 'Review', 'party-bag-builder' ),
	),
);
?>

<div class="pbb-step-indicator" data-wp-context='{"steps": <?php echo esc_js( wp_json_encode( $steps ) ); ?>}'>
	<template data-wp-each--step="context.steps">
		<div
			class="pbb-step-indicator-item"
			data-wp-class--active="state.isCurrentStep"
			data-wp-class--completed="state.isStepCompleted"
		>
			<span class="pbb-step-number">
				<span data-wp-bind--hidden="!state.isStepCompleted">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M20 6 9 17l-5-5"></path>
					</svg>
				</span>
				<span data-wp-text="context.step.id" data-wp-bind--hidden="state.isStepCompleted"></span>
			</span>
			<span class="pbb-step-label" data-wp-text="context.step.value"></span>
		</div>
		<div
			class="pbb-step-indicator-connector"
			data-wp-bind--hidden="state.isLastStep"
			data-wp-class--completed="state.isStepCompleted"
		>
			<div class="pbb-step-connector-inner"></div>
		</div>
	</template>
</div>
