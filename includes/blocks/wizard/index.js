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
import Edit from './edit';
import block from './block.json';

import './editor.scss';
import './wizard.scss';

/**
 * Register the block.
 */
registerBlockType( block, {
	edit: Edit,
	save: () => null,
} );
