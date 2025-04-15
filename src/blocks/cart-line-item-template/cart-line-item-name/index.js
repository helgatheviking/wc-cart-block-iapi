/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Icon, heading } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from './block.json';

registerBlockType( metadata, {
	edit: ({ context: { 'woocommerce/cartItem': cartItem } }) => {

		const blockProps = useBlockProps( {
			className: 'wc-block-components-product-name',
		} );

		return (
			<div { ...blockProps }>
				{
					cartItem.permalink ? (
						<a href={ cartItem.permalink }>{ cartItem.name }</a>
					) : (
						<span>{ cartItem.name }</span>
					)
				}
			</div>
		);
	},
	icon: {
			src: (
				<Icon
					icon={ heading }
					className="wc-block-editor-components-block-icon"
				/>
			),
	}
} );