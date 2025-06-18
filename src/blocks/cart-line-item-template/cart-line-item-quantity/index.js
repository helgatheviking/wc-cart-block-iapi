/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

import QuantitySelector from './quantity-selector';

/**
 * Internal dependencies
 */
import metadata from './block.json';

registerBlockType( metadata, {
	edit: ( { context: { 'woocommerce/cartItem': cartItem } } ) => {
		const blockProps = useBlockProps( {
			className: 'wc-block-cart-item__quantity',
		} );

		//console.debug('cart item test', cartItem.images[0] );

		return (
			<div { ...blockProps }>
				<QuantitySelector
					editable={ true }
					quantity={ cartItem.quantity }
					minimum={ cartItem.quantity_limits.minimum }
					maximum={ cartItem.quantity_limits.maximum }
					step={ cartItem.quantity_limits.multiple_of }
					itemName={ cartItem.name }
				/>
			</div>
		);
	},
} );
