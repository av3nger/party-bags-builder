/**
 * Edit component for Party Bag Builder block.
 *
 * Shows a preview message in the editor since the wizard
 * is server-rendered and only works on the frontend.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { ServerSideRender } from '@wordpress/server-side-render';

/**
 * Edit component.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Edit component.
 */
export default function Edit( props ) {
	const blockProps = useBlockProps( {
		className: 'pbb-editor-placeholder',
	} );

	return (
		<div { ...blockProps }>
			<div className="pbb-editor-message">
				<span className="dashicons dashicons-cart"></span>
				<h3>{ __( 'Party Bag Builder', 'party-bag-builder' ) }</h3>
				<p>
					{ __(
						'The wizard will be displayed on the frontend. Preview in the actual page to see the full experience.',
						'party-bag-builder'
					) }
				</p>
			</div>
			<div className="pbb-editor-preview">
				<ServerSideRender
					block="party-bag-builder/wizard"
					attributes={ props.attributes }
				/>
			</div>
		</div>
	);
}
