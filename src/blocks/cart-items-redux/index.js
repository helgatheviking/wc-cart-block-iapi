/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { Icon, postList } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './edit';

import './editor.scss';

const save = () => {
	const blockProps = useBlockProps.save();

        return (
            <div { ...blockProps }>
                <InnerBlocks.Content />
            </div>
        );
};

registerBlockType( metadata, {
	/*edit: () => {
		const blockProps = useBlockProps();

        return (
            <div { ...blockProps }>
                <InnerBlocks />
            </div>
        );
	},
    */
    edit: Edit,
	icon: <Icon icon={ postList } />,
	save,
} );
