/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { Icon, symbol } from '@wordpress/icons';

import './style.scss';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './edit';

const save = ( { attributes } ) => {
	const blockProps = useBlockProps.save( { style: attributes.style } );
	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
};

registerBlockType( metadata, {
	edit: Edit,
	icon: <Icon icon={ symbol } />,
	save,
} );
