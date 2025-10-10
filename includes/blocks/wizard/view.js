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
			return step.id === 5;
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
			const { style } = getContext();
			return state.selectedTagStyle === style.id;
		},
		get isToySelected() {
			const { toy } = getContext();
			return state.selectedToys.includes( toy.id );
		},
		get isToyInputDisabled() {
			return !state.isToySelected && state.selectedToys.length >= state.maxToysAllowed;
		},
		get canGoToAddonsStep() {
			return state.selectedToys.length === state.maxToysAllowed;
		},
		get canGoToReviewStep() {
			return ! state.isNameTagAddonSelected || state.selectedTagStyle;
		},
		get isAddonSelected() {
			const { addon } = getContext();
			return state.selectedAddons.includes( addon.id );
		},
		get isAddonFree() {
			const { addon } = getContext();
			return state.priceBreakdown.freeAddons.includes( addon.id );
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
		get maxToysAllowed() {
			return state.tierConfig?.includes?.toys || 0;
		},
		get freeAddonsAllowed() {
			const { tier } = getContext();
			return (tier?.includes?.free_addons || 0) > 0;
		},
		get isNameTagAddonSelected() {
			return state.tierConfig?.includes?.free_addons > 0 || state.nameTagAddonEnabled;
		},
		get showNameTagToggle() {
			return state.tierConfig?.includes?.free_addons === 0;
		},
		get shouldShowInput() {
			const { inputIndex } = getContext();
			return inputIndex < state.kidCount;
		},
		get getKidNameByIndex() {
			const { inputIndex } = getContext();
			return state.kidNames[ inputIndex ] || '';
		},
		get kidNamesDisplay() {
			const names = state.kidNames.filter( ( n ) => n.trim() !== '' ).join( ', ' );
			return names || 'No names provided';
		},
		get addToCartButtonText() {
			return state.isLoading ? 'Adding...' : 'Add to Cart';
		},
		get selectedItemsData() {
			const { toys, addons } = getContext();

			// Get selected toys
			const selectedToys = ( toys || [] ).filter(
				( toy ) => state.selectedToys.includes( toy.id )
			);

			// Get selected addons
			const selectedAddons = ( addons || [] ).filter(
				( addon ) => state.selectedAddons.includes( addon.id )
			);

			// Combine toys and addons into a single array
			return [ ...selectedToys, ...selectedAddons ];
		},
		get selectedTagStyleName() {
			const { tag_styles } = getContext();

			if ( ! state.selectedTagStyle || ! tag_styles ) {
				return '';
			}

			const selectedStyle = tag_styles.find(
				( style ) => style.id === state.selectedTagStyle
			);

			return selectedStyle ? selectedStyle.name : '';
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
		toggleAddon: () => {
			const { addon } = getContext();

			if ( state.selectedAddons.indexOf( addon.id ) > -1 ) {
				// Remove addon
				state.selectedAddons = state.selectedAddons.filter( ( id ) => id !== addon.id );
			} else {
				// Add addon if under limit
				state.selectedAddons = [ ...state.selectedAddons, addon.id ];
			}

			state.priceBreakdown = calculatePriceBreakdown( state );
		},

		/**
		 * Toggle name tag addon (for non-premium tiers).
		 */
		toggleNameTagAddon: () => {
			state.nameTagAddonEnabled = ! state.nameTagAddonEnabled;

			// Reset tag style and names if unchecked
			if ( ! state.nameTagAddonEnabled ) {
				state.selectedTagStyle = null;
			}

			state.priceBreakdown = calculatePriceBreakdown( state );
		},

		/**
		 * Set tag style selection.
		 */
		setTagStyle: () => {
			const { style } = getContext();

			if ( style.id ) {
				state.selectedTagStyle = style.id;
			}
		},

		/**
		 * Handle kid name input using event delegation.
		 */
		handleKidNameInput: ( event ) => {
			// Check if the event target is an input with data-index
			if ( event.target.classList.contains( 'pbb-name-input' ) ) {
				const index = parseInt( event.target.dataset.index, 10 );
				const name = event.target.value;

				if ( index >= 0 && index < state.kidNames.length ) {
					// Create new array to trigger reactivity
					const updatedNames = [ ...state.kidNames ];
					updatedNames[ index ] = name;
					state.kidNames = updatedNames;
				}
			}
		},

		/**
		 * Update kid name at specific index (legacy, kept for compatibility).
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

			if ( state.currentStep === 4 && state.isNameTagAddonSelected && ! state.selectedTagStyle ) {
				state.errors = [ 'Please select a tag style.' ];
				return;
			}

			// Clear errors and advance
			state.errors = [];
			if ( state.currentStep < 5 ) {
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
			if ( step >= 1 && step <= 5 ) {
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
