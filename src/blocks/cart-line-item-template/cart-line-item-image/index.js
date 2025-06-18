/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { Icon, image } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import './style.scss';

registerBlockType( metadata, {
	edit: ( { context: { 'woocommerce/cartItem': cartItem } } ) => {
		const blockProps = useBlockProps( {
			className: 'wc-block-cart-item__image',
		} );

		return (
			<div { ...blockProps }>
				<img
					src={ cartItem.images[ 0 ].thumbnail }
					alt={ cartItem.name }
					className="preview-item-image"
				/>
			</div>
		);
	},
	icon: {
		src: (
			<Icon
				icon={ image }
				className="wc-block-editor-components-block-icon"
			/>
		),
	},
} );
