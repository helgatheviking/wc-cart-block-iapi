/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import metadata from './block.json';

registerBlockType( metadata, {
	edit: ( { context: { 'woocommerce/cartItem': cartItem } } ) => {
		const blockProps = useBlockProps( {
			className: 'wc-block-components-product-metadata',
		} );

		//console.debug('cart item test', cartItem.images[0] );

		const variationMeta =
			cartItem.variation && cartItem.variation.length > 0
				? cartItem.variation.map( ( attribute, index ) => (
						<div
							key={ index }
							className={ `wc-block-components-product-details__${ attribute.attribute.toLowerCase() }` }
						>
							<div className="wc-block-components-product-details__item">
								<span className="wc-block-components-product-details__name">
									{ attribute.attribute }:{ ' ' }
								</span>
								<span className="wc-block-components-product-details__value">
									{ attribute.value }
								</span>
							</div>
						</div>
				  ) )
				: null;

		return variationMeta && <div { ...blockProps }>{ variationMeta }</div>;
	},
} );
