/**
 * Party Bag Builder - Interactivity API Store
 *
 * @package PartyBagBuilder
 */

import { store, getContext } from '@wordpress/interactivity';
import { calculatePriceBreakdown } from './pricing';
import { addToCart } from './api';

store( 'party-bag-builder', {
	state: {
		currentStep: 1,
		kidCount: 0,
		selectedTier: null,
		tierConfig: null,
		commonItems: [],
		selectedToys: [],
		selectedAddons: [],
		selectedTagStyle: null,
		kidNames: [],
		priceBreakdown: { base: 0, addons: 0, total: 0, freeAddons: [], paidAddons: [] },
		isLoading: false,
		errors: [],
	},

	actions: {
		/**
		 * Set the number of kids for party bags.
		 */
		setKidCount: ( event ) => {
			const context = getContext();
			const count = parseInt( event.target.value, 10 );

			if ( count >= 1 && count <= 50 ) {
				context.kidCount = count;
				context.kidNames = Array( count ).fill( '' );
				context.actions.updatePriceBreakdown();
			}
		},

		/**
		 * Select a tier and load its configuration.
		 */
		selectTier: ( event ) => {
			const context = getContext();
			const tierId = event.target.dataset.tierId;
			const tiers = context.tiers || [];

			const tier = tiers.find( ( t ) => t.id === tierId );
			if ( tier ) {
				context.selectedTier = tierId;
				context.tierConfig = tier;
				context.selectedToys = [];
				context.selectedAddons = [];
				context.actions.updatePriceBreakdown();
			}
		},

		/**
		 * Toggle toy selection.
		 */
		toggleToy: ( event ) => {
			const context = getContext();
			const toyId = parseInt( event.target.value, 10 );
			const maxToys = context.tierConfig?.includes?.toys || 0;

			const index = context.selectedToys.indexOf( toyId );

			if ( index > -1 ) {
				// Remove toy
				context.selectedToys = context.selectedToys.filter( ( id ) => id !== toyId );
			} else if ( context.selectedToys.length < maxToys ) {
				// Add toy if under limit
				context.selectedToys = [ ...context.selectedToys, toyId ];
			}
		},

		/**
		 * Toggle addon selection.
		 */
		toggleAddon: ( event ) => {
			const context = getContext();
			const addonId = parseInt( event.target.value, 10 );
			const maxAddons = context.tierConfig?.includes?.max_addons || 0;

			const index = context.selectedAddons.indexOf( addonId );

			if ( index > -1 ) {
				// Remove addon
				context.selectedAddons = context.selectedAddons.filter( ( id ) => id !== addonId );
			} else if ( context.selectedAddons.length < maxAddons ) {
				// Add addon if under limit
				context.selectedAddons = [ ...context.selectedAddons, addonId ];
			}

			context.actions.updatePriceBreakdown();
		},

		/**
		 * Set tag style selection.
		 */
		setTagStyle: ( event ) => {
			const context = getContext();
			context.selectedTagStyle = event.target.value;
		},

		/**
		 * Update kid name at specific index.
		 */
		updateKidName: ( event ) => {
			const context = getContext();
			const index = parseInt( event.target.dataset.index, 10 );
			const name = event.target.value;

			if ( index >= 0 && index < context.kidNames.length ) {
				context.kidNames[ index ] = name;
			}
		},

		/**
		 * Navigate to next step.
		 */
		nextStep: () => {
			const context = getContext();

			// Validation
			if ( context.currentStep === 1 && context.kidCount < 1 ) {
				context.errors = [ 'Please select the number of kids.' ];
				return;
			}

			if ( context.currentStep === 2 && ! context.selectedTier ) {
				context.errors = [ 'Please select a tier.' ];
				return;
			}

			if ( context.currentStep === 3 ) {
				const maxToys = context.tierConfig?.includes?.toys || 0;
				if ( context.selectedToys.length !== maxToys ) {
					context.errors = [ `Please select exactly ${ maxToys } toy(s).` ];
					return;
				}
			}

			if ( context.currentStep === 4 && ! context.selectedTagStyle ) {
				context.errors = [ 'Please select a tag style.' ];
				return;
			}

			// Clear errors and advance
			context.errors = [];
			if ( context.currentStep < 6 ) {
				context.currentStep += 1;
				context.callbacks.onStepChange();
			}
		},

		/**
		 * Navigate to previous step.
		 */
		prevStep: () => {
			const context = getContext();
			context.errors = [];

			if ( context.currentStep > 1 ) {
				context.currentStep -= 1;
				context.callbacks.onStepChange();
			}
		},

		/**
		 * Navigate to specific step.
		 */
		goToStep: ( step ) => {
			const context = getContext();

			if ( step >= 1 && step <= 6 ) {
				context.currentStep = step;
				context.errors = [];
				context.callbacks.onStepChange();
			}
		},

		/**
		 * Add to cart - async generator function.
		 */
		addToCart: function* () {
			const context = getContext();

			// Final validation
			if ( ! context.selectedTier || context.selectedToys.length === 0 || ! context.selectedTagStyle ) {
				context.errors = [ 'Please complete all required steps.' ];
				return;
			}

			context.isLoading = true;
			context.errors = [];

			try {
				const partyBagData = {
					kid_count: context.kidCount,
					tier: context.selectedTier,
					toys: context.selectedToys,
					addons: context.selectedAddons,
					tag_style: context.selectedTagStyle,
					kid_names: context.kidNames.filter( ( name ) => name.trim() !== '' ),
				};

				const result = yield addToCart( partyBagData, context.nonce );

				if ( result.success ) {
					// Redirect to cart or show success message
					window.location.href = result.data.cart_url || '/cart';
				} else {
					context.errors = result.errors || [ result.message || 'Failed to add to cart.' ];
				}
			} catch ( error ) {
				context.errors = [ error.message || 'An unexpected error occurred.' ];
			} finally {
				context.isLoading = false;
			}
		},

		/**
		 * Update price breakdown based on current selections.
		 */
		updatePriceBreakdown: () => {
			const context = getContext();
			context.priceBreakdown = calculatePriceBreakdown( context );
			context.callbacks.onPriceUpdate();
		},

		/**
		 * Toggle price breakdown visibility.
		 */
		togglePriceBreakdown: () => {
			const context = getContext();
			context.showPriceBreakdown = ! context.showPriceBreakdown;
		},
	},

	callbacks: {
		/**
		 * Called when step changes - scroll to top.
		 */
		onStepChange: () => {
			const wizardElement = document.querySelector( '[data-wp-interactive="party-bag-builder"]' );
			if ( wizardElement ) {
				wizardElement.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			}
		},

		/**
		 * Called when price updates - can trigger animations or logging.
		 */
		onPriceUpdate: () => {
			// Hook for future enhancements (e.g., price animation)
		},
	},
} );