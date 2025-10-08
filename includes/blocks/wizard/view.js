/**
 * Party Bag Builder - Interactivity API Store
 *
 * @package PartyBagBuilder
 */

/**
 * WordPress packages
 */
import { store, getContext } from '@wordpress/interactivity';

/**
 * Internal packages
 */
import { calculatePriceBreakdown } from './pricing';
import { addToCart } from './api';

/**
 * Helper function to scroll wizard to top on step change.
 */
function scrollWizardToTop() {
	const wizardElement = document.querySelector( '[data-wp-interactive="party-bag-builder"]' );
	if ( wizardElement ) {
		wizardElement.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	}
}

const { state, actions } = store( 'party-bag-builder', {
	state: {
		get isStepOne() {
			return state.currentStep === 1;
		},
		get isLastStep() {
			const { step } = getContext();
			return step.id === 6;
		},
		get includedToysLabel() {
			const { tier } = getContext();
			const toys = tier?.includes?.toys || 0;

			return wp.i18n.sprintf(
					wp.i18n._n(
							'%d toy of your choice',
							'%d toys of your choice',
							toys,
							'party-bag-builder'
					),
					toys
			);
		},
		get includedAddonsLabel() {
			const { tier } = getContext();
			const addons = tier?.includes?.free_addons || 0;

			return wp.i18n.sprintf(
					wp.i18n._n(
							'%d FREE addon',
							'%d FREE addons',
							addons,
							'party-bag-builder'
					),
					addons
			);
		},
		get totalAddonsLabel() {
			const { tier } = getContext();
			const addons = tier?.includes?.max_addons || 0;

			return wp.i18n.sprintf(
					wp.i18n.__(
							'Up to %d total addons',
							'party-bag-builder'
					),
					addons
			);
		},
		get isCurrentStep() {
			const { step } = getContext();
			return step.id === state.currentStep;
		},
		get isStepCompleted() {
			const { step } = getContext();
			return state.currentStep > step.id;
		},
		get currentTierPrice() {
			const { tier } = getContext();
			return ( ( tier.base_price || 0 ) * state.kidCount ).toFixed( 2 );
		},
		get isTierSelected() {
			const { tier } = getContext();
			return state.selectedTier === tier.id;
		},
		get isTagStyleSelected() {
			const context = getContext();
			return state.selectedTagStyle === context.tagStyleId;
		},
		get isToySelected() {
			const { toy } = getContext();
			return state.selectedToys.includes( toy.id );
		},
		get isToyInputDisabled() {
			return !state.isToySelected && state.selectedToys.length >= state.maxToysAllowed;
		},
		get isAddonSelected() {
			const context = getContext();
			return state.selectedAddons.includes( context.addonId );
		},
		get isAddonFree() {
			const context = getContext();
			return state.priceBreakdown.freeAddons.includes( context.addonId );
		},
		// Formatted prices (reused across templates)
		get tierBasePrice() {
			return ( state.tierConfig?.base_price || 0 ).toFixed( 2 );
		},
		get breakdownBasePrice() {
			return state.priceBreakdown.base.toFixed( 2 );
		},
		get breakdownAddonsPrice() {
			return state.priceBreakdown.addons.toFixed( 2 );
		},
		get breakdownTotalPrice() {
			return state.priceBreakdown.total.toFixed( 2 );
		},
		get freeAddonsCount() {
			return state.priceBreakdown.freeAddons.length;
		},
		get maxAddonsAllowed() {
			return state.tierConfig?.includes?.max_addons || 0;
		},
		get maxToysAllowed() {
			return state.tierConfig?.includes?.toys || 0;
		},
		get freeAddonsAllowed() {
			const { tier } = getContext();
			return (tier?.includes?.free_addons || 0) > 0;
		},
		get kidNamesDisplay() {
			const names = state.kidNames.filter( ( n ) => n.trim() !== '' ).join( ', ' );
			return names || 'No names provided';
		},
		get addToCartButtonText() {
			return state.isLoading ? 'Adding...' : 'Add to Cart';
		},
	},
	actions: {
		/**
		 * Set the number of kids for party bags.
		 */
		setKidCount: ( event ) => {
			const count = parseInt( event.target.value, 10 );

			if ( count >= 1 && count <= 50 ) {
				state.kidCount = count;
				state.kidNames = Array( count ).fill( '' );
				state.priceBreakdown = calculatePriceBreakdown( state );
			}
		},

		/**
		 * Increment kids count by 1.
		 */
		incrementKidCount: () => {
			if ( state.kidCount < 50 ) {
				state.kidCount += 1;
				state.kidNames = Array( state.kidCount ).fill( '' );
				state.priceBreakdown = calculatePriceBreakdown( state );
			}
		},

		/**
		 * Decrement kids count by 1.
		 */
		decrementKidCount: () => {
			if ( state.kidCount > 1 ) {
				state.kidCount -= 1;
				state.kidNames = Array( state.kidCount ).fill( '' );
				state.priceBreakdown = calculatePriceBreakdown( state );
			}
		},

		/**
		 * Select a tier and load its configuration.
		 */
		selectTier: () => {
			const { tier } = getContext();

			if ( tier ) {
				state.selectedTier = tier.id;
				state.tierConfig = tier;
				state.selectedToys = [];
				state.selectedAddons = [];
				state.priceBreakdown = calculatePriceBreakdown( state );
			}
		},

		/**
		 * Toggle toy selection.
		 */
		toggleToy: () => {
			const { toy } = getContext();

			const maxToys = state.tierConfig?.includes?.toys || 0;

			if ( state.selectedToys.indexOf( toy.id ) > -1 ) {
				// Remove toy
				state.selectedToys = state.selectedToys.filter( ( id ) => id !== toy.id );
			} else if ( state.selectedToys.length < maxToys ) {
				// Add toy if under limit
				state.selectedToys = [ ...state.selectedToys, toy.id ];
			}
		},

		/**
		 * Toggle addon selection.
		 */
		toggleAddon: ( event ) => {
			const addonId = parseInt( event.target.value, 10 );
			const maxAddons = state.tierConfig?.includes?.max_addons || 0;

			const index = state.selectedAddons.indexOf( addonId );

			if ( index > -1 ) {
				// Remove addon
				state.selectedAddons = state.selectedAddons.filter( ( id ) => id !== addonId );
			} else if ( state.selectedAddons.length < maxAddons ) {
				// Add addon if under limit
				state.selectedAddons = [ ...state.selectedAddons, addonId ];
			}

			state.priceBreakdown = calculatePriceBreakdown( state );
		},

		/**
		 * Set tag style selection.
		 */
		setTagStyle: ( event ) => {
			const tagStyleId = event.target.dataset.tagStyleId;
			if ( tagStyleId ) {
				state.selectedTagStyle = tagStyleId;
			}
		},

		/**
		 * Update kid name at specific index.
		 */
		updateKidName: ( event ) => {
			const index = parseInt( event.target.dataset.index, 10 );
			const name = event.target.value;

			if ( index >= 0 && index < state.kidNames.length ) {
				// Create new array to trigger reactivity
				const updatedNames = [ ...state.kidNames ];
				updatedNames[ index ] = name;
				state.kidNames = updatedNames;
			}
		},

		/**
		 * Navigate to next step.
		 */
		nextStep: () => {
			// Validation
			if ( state.currentStep === 1 && state.kidCount < 1 ) {
				state.errors = [ 'Please select the number of kids.' ];
				return;
			}

			if ( state.currentStep === 1 ) {
				actions.selectTier();
			}

			if ( state.currentStep === 2 && ! state.selectedTier ) {
				state.errors = [ 'Please select a tier.' ];
				return;
			}

			if ( state.currentStep === 3 ) {
				const maxToys = state.tierConfig?.includes?.toys || 0;
				if ( state.selectedToys.length !== maxToys ) {
					state.errors = [ `Please select exactly ${ maxToys } toy(s).` ];
					return;
				}
			}

			if ( state.currentStep === 4 && ! state.selectedTagStyle ) {
				state.errors = [ 'Please select a tag style.' ];
				return;
			}

			// Clear errors and advance
			state.errors = [];
			if ( state.currentStep < 6 ) {
				state.currentStep += 1;
				scrollWizardToTop();
			}
		},

		/**
		 * Navigate to previous step.
		 */
		prevStep: () => {
			state.errors = [];

			if ( state.currentStep > 1 ) {
				state.currentStep -= 1;
				scrollWizardToTop();
			}
		},

		/**
		 * Navigate to specific step.
		 */
		goToStep: ( step ) => {
			if ( step >= 1 && step <= 6 ) {
				state.currentStep = step;
				state.errors = [];
				scrollWizardToTop();
			}
		},

		/**
		 * Add to cart - async generator function.
		 */
		addToCart: function* () {
			const context = getContext();

			// Final validation
			if ( ! state.selectedTier || state.selectedToys.length === 0 || ! state.selectedTagStyle ) {
				state.errors = [ 'Please complete all required steps.' ];
				return;
			}

			state.isLoading = true;
			state.errors = [];

			try {
				const partyBagData = {
					kid_count: state.kidCount,
					tier: state.selectedTier,
					toys: state.selectedToys,
					addons: state.selectedAddons,
					tag_style: state.selectedTagStyle,
					kid_names: state.kidNames.filter( ( name ) => name.trim() !== '' ),
				};

				const result = yield addToCart( partyBagData, context.nonce );

				if ( result.success ) {
					// Redirect to cart or show success message
					window.location.href = result.data.cart_url || '/cart';
				} else {
					state.errors = result.errors || [ result.message || 'Failed to add to cart.' ];
				}
			} catch ( error ) {
				state.errors = [ error.message || 'An unexpected error occurred.' ];
			} finally {
				state.isLoading = false;
			}
		},

		/**
		 * Toggle price breakdown visibility.
		 */
		togglePriceBreakdown: () => {
			state.showPriceBreakdown = ! state.showPriceBreakdown;
		},
	},
} );
