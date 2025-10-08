/**
 * Party Bag Builder - Pricing Calculator
 *
 * @package PartyBagBuilder
 */

/**
 * WordPress packages
 */
import { getContext } from '@wordpress/interactivity';

/**
 * Calculate price breakdown based on wizard state.
 *
 * @param {Object} state - The wizard state from Interactivity API context.
 *
 * @return {Object} Price breakdown with base, addons, total, and addon categorization.
 */
export function calculatePriceBreakdown( state ) {
	const { kidCount, tierConfig, selectedAddons } = state;
	const { addons } = getContext();

	// Default breakdown if tier not selected
	if ( ! tierConfig || ! kidCount ) {
		return {
			base: 0,
			addons: 0,
			total: 0,
			freeAddons: [],
			paidAddons: [],
		};
	}

	// Base price: tier base price × number of kids
	const base = tierConfig.base_price * kidCount;

	// Get number of free addons from tier config
	const freeAddonCount = tierConfig.includes?.free_addons || 0;

	// Get full addon objects from selected addon IDs
	const selectedAddonObjects = ( selectedAddons || [] )
		.map( ( addonId ) => {
			return ( addons || [] ).find( ( addon ) => addon.id === addonId );
		} )
		.filter( ( addon ) => addon !== undefined );

	// Sort addons by price (ascending) - cheapest marked free first
	const sortedAddons = [ ...selectedAddonObjects ].sort(
		( a, b ) => parseFloat( a.price ) - parseFloat( b.price )
	);

	let addonTotal = 0;
	const freeAddonIds = [];
	const paidAddonIds = [];

	sortedAddons.forEach( ( addon, index ) => {
		if ( index < freeAddonCount ) {
			// This addon is free (within tier's free addon allowance)
			freeAddonIds.push( addon.id );
		} else {
			// This addon is paid
			const addonPrice = parseFloat( addon.price ) || 0;
			addonTotal += addonPrice * kidCount;
			paidAddonIds.push( addon.id );
		}
	} );

	return {
		base,
		addons: addonTotal,
		total: base + addonTotal,
		freeAddons: freeAddonIds,
		paidAddons: paidAddonIds,
	};
}

/**
 * Format price for display with currency symbol.
 *
 * @param {number} amount   - The amount to format.
 * @param {string} currency - Currency symbol (default: $).
 *
 * @return {string} Formatted price string.
 */
export function formatPrice( amount, currency = '$' ) {
	const numAmount = parseFloat( amount ) || 0;
	return `${ currency }${ numAmount.toFixed( 2 ) }`;
}
