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
		get isBagSelected() {
			const { bag } = getContext();
			return state.selectedBag === bag.id;
		},
		get isToySelected() {
			const { toy } = getContext();
			return state.selectedToys.includes( toy.id );
		},
		get canGoToToysStep() {
			return null !== state.selectedBag;
		},
		get canGoToReviewStep() {
			// Check if all required toys are selected
			const themedValid = state.maxThemedToys === 0 || state.selectedThemedCount === state.maxThemedToys;
			const genericValid = state.maxGenericToys === 0 || state.selectedGenericCount === state.maxGenericToys;
			const toysValid = themedValid && genericValid;

			// Check if tag style is selected (for premium tier only)
			const tagStyleValid = ! state.hasFreeNameTag || state.selectedTagStyle;

			return toysValid && tagStyleValid;
		},
		get isAddonSelected() {
			const { addon } = getContext();
			return state.selectedAddons.includes( addon.id );
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
		get maxToysAllowed() {
			const themed = state.tierConfig?.includes?.themed || 0;
			const generic = state.tierConfig?.includes?.generic || 0;
			return themed + generic;
		},
		get maxThemedToys() {
			return state.tierConfig?.includes?.themed || 0;
		},
		get maxGenericToys() {
			return state.tierConfig?.includes?.generic || 0;
		},
		get hasFreeNameTag() {
			return state.selectedTier === 'premium';
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
			const { toysThemed, toysGeneric, addons } = getContext();

			// Combine all toys
			const allToys = [ ...( toysThemed || [] ), ...( toysGeneric || [] ) ];

			// Get selected toys
			const selectedToys = allToys.filter(
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
		get selectedBagData() {
			const { bags } = getContext();

			if ( ! state.selectedBag || ! bags ) {
				return null;
			}

			const selectedBag = bags.find(
				( bag ) => bag.id === state.selectedBag
			);

			return selectedBag || null;
		},
		get filteredToys() {
			const { toysThemed } = getContext();
			const theme = state.selectedBagData?.theme || null;

			if ( ! theme || ! toysThemed ) {
				return toysThemed || [];
			}

			return toysThemed.filter( ( toy ) => toy.theme === theme );
		},
		get selectedThemedCount() {
			const themedToyIds = state.filteredToys.map( ( toy ) => toy.id );
			return state.selectedToys.filter( ( toyId ) => themedToyIds.includes( toyId ) ).length;
		},
		get selectedGenericCount() {
			const { toysGeneric } = getContext();
			if ( ! toysGeneric ) return 0;

			const genericToyIds = toysGeneric.map( ( toy ) => toy.id );
			return state.selectedToys.filter( ( toyId ) => genericToyIds.includes( toyId ) ).length;
		},
		get isThemedToyDisabled() {
			return !state.isToySelected && state.selectedThemedCount >= state.maxThemedToys;
		},
		get isGenericToyDisabled() {
			return !state.isToySelected && state.selectedGenericCount >= state.maxGenericToys;
		},
		get showThemedToys() {
			return state.maxThemedToys > 0;
		},
		get showGenericToys() {
			return state.maxGenericToys > 0;
		},
		get isCategoryOpen() {
			const { category } = getContext();
			return state.openCategory === category?.slug;
		},
		get isToyInCategory() {
			const { toy, category } = getContext();
			if ( ! toy || ! category ) return false;
			return toy.categories && toy.categories.includes( category.slug );
		},
		get isToyUncategorized() {
			const { toy } = getContext();
			if ( ! toy ) return false;
			return ! toy.categories || toy.categories.length === 0;
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

			if ( state.selectedToys.indexOf( toy.id ) > -1 ) {
				// Remove toy
				state.selectedToys = state.selectedToys.filter( ( id ) => id !== toy.id );
			} else {
				// Check if this is a themed or generic toy and enforce the correct limit
				const theme = state.selectedBagData?.theme || null;
				const isThemed = toy.theme === theme;
				const canAdd = isThemed
					? state.selectedThemedCount < state.maxThemedToys
					: state.selectedGenericCount < state.maxGenericToys;

				if ( canAdd ) {
					state.selectedToys = [ ...state.selectedToys, toy.id ];
				}
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
		 * Set tag style selection.
		 */
		setTagStyle: () => {
			const { style } = getContext();

			if ( style.id ) {
				state.selectedTagStyle = style.id;
			}
		},

		/**
		 * Select a bag (single selection).
		 */
		selectBag: () => {
			const { bag } = getContext();

			if ( bag && bag.id ) {
				state.selectedBag = bag.id;
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

			if ( state.currentStep === 3 && ! state.selectedBag ) {
				state.errors = [ 'Please select a bag.' ];
				return;
			}

			if ( state.currentStep === 4 ) {
				// Validate themed toys
				if ( state.maxThemedToys > 0 && state.selectedThemedCount !== state.maxThemedToys ) {
					state.errors = [ `Please select exactly ${ state.maxThemedToys } themed toy(s).` ];
					return;
				}

				// Validate generic toys
				if ( state.maxGenericToys > 0 && state.selectedGenericCount !== state.maxGenericToys ) {
					state.errors = [ `Please select exactly ${ state.maxGenericToys } generic toy(s).` ];
					return;
				}

				// Validate tag style for premium tier
				if ( state.hasFreeNameTag && ! state.selectedTagStyle ) {
					state.errors = [ 'Please select a tag style.' ];
					return;
				}
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
		goToStep: () => {
			const { step } = getContext();

			// Only allow navigation to completed steps (backward navigation)
			if ( step.id >= 1 && step.id <= 5 && step.id < state.currentStep ) {
				state.currentStep = step.id;
				state.errors = [];
				scrollWizardToTop();
			}
		},

		/**
		 * Toggle accordion category.
		 */
		toggleCategory: () => {
			const { category } = getContext();

			if ( category && category.slug ) {
				// Toggle: if already open, close it; otherwise open the clicked one
				state.openCategory = state.openCategory === category.slug ? null : category.slug;
			}
		},

		/**
		 * Add to cart - async generator function.
		 */
		addToCart: function* () {
			const context = getContext();

			// Final validation
			if ( ! state.selectedTier || state.selectedToys.length === 0 ) {
				state.errors = [ 'Please complete all required steps.' ];
				return;
			}

			state.isLoading = true;
			state.errors = [];

			try {
				const partyBagData = {
					kid_count: state.kidCount,
					tier: state.selectedTier,
					bag: state.selectedBag,
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
	},
} );
