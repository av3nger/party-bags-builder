/**
 * Party Bag Builder - API Helper
 *
 * @package PartyBagBuilder
 */

/**
 * Add party bag to cart via REST API.
 *
 * @param {Object} partyBagData - The party bag configuration data.
 * @param {string} nonce        - WordPress REST API nonce.
 *
 * @return {Promise<Object>} Response object with success, data, message, and errors.
 */
export async function addToCart( partyBagData, nonce ) {
	try {
		const response = await fetch( '/wp-json/bag-builder/v1/add-to-cart', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': nonce,
			},
			body: JSON.stringify( {
				party_bag_data: partyBagData,
			} ),
		} );

		const data = await response.json();

		// Check if the response is OK (status 200-299)
		if ( ! response.ok ) {
			return {
				success: false,
				data: null,
				message: data.message || 'Failed to add to cart.',
				errors: data.errors || [ data.message || 'Unknown error occurred.' ],
			};
		}

		return {
			success: true,
			data: data.data || data,
			message: data.message || 'Added to cart successfully!',
			errors: [],
		};
	} catch ( error ) {
		return {
			success: false,
			data: null,
			message: 'Network error occurred.',
			errors: [ error.message || 'Failed to connect to server.' ],
		};
	}
}