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
	const { kidCount, tierConfig, selectedAddons, nameTagAddonEnabled } = state;
	const { addons } = getContext();

	// Default breakdown if tier not selected
	if ( ! tierConfig || ! kidCount ) {
		return {
			base: 0,
			addons: 0,
			total: 0,
		};
	}

	// Base price: tier base price × number of kids
	const base = tierConfig.base_price * kidCount;

	// Get full addon objects from selected addon IDs
	const selectedAddonObjects = ( selectedAddons || [] )
		.map( ( addonId ) => {
			return ( addons || [] ).find( ( addon ) => addon.id === addonId );
		} )
		.filter( ( addon ) => addon !== undefined );

	let addonTotal = 0;
	selectedAddonObjects.forEach( ( addon ) => {
		const addonPrice = parseFloat( addon.price ) || 0;
		addonTotal += addonPrice * kidCount;
	} );

	// Name tags are free on premium tier, $2.50 per kid on basic/medium
	const NAME_TAG_PRICE = 2.50;
	const isPremiumTier = tierConfig.id === 'premium';
	if ( nameTagAddonEnabled && ! isPremiumTier ) {
		addonTotal += NAME_TAG_PRICE * kidCount;
	}

	return {
		base,
		addons: addonTotal,
		total: base + addonTotal,
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
