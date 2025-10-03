/**
 * Party Bag Builder Block
 *
 * Registers the party bag builder wizard block.
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import edit from './edit';
import block from './block.json';

import './editor.scss';
import './style.scss';

/**
 * Register the block.
 */
registerBlockType( block, {
	edit,
	save: () => null,
} );
