/*
 * External dependencies
*/
import clsx from 'clsx';

/**
 * WordPress dependencies
 */
import {
	__experimentalUseBlockPreview as useBlockPreview,
	BlockContextProvider,
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './editor.scss';
import { previewCart } from '@previews/cart';

const TEMPLATE = [
	[
		'core/columns',
		{ 'verticalAlignment': 'top' },
		[
			[
				'core/column',
				{ 'verticalAlignment': 'top', width: '20%' },
				[
					[ 'woocommerce/cart-line-item-image', {} ]
				]
			],
			[
				'core/column',
				{ 'verticalAlignment': 'top', width: '60%' },
				[

							[ 'woocommerce/cart-line-item-name', {} ],
							[ 'woocommerce/cart-line-item-price', {} ],
							[ 'woocommerce/cart-line-item-meta', {} ],
							[ 'woocommerce/cart-line-item-quantity', {} ],
							[
								'core/button',
								{
									className: 'wc-block-cart-item__remove-link',
									text: __( 'Remove item', 'woocommerce' ),
									variant: 'link',
								},
							],

				]
			],
			[
				'core/column',
				{ 'verticalAlignment': 'top', width: '20%' },
				[
					[ 'woocommerce/cart-line-item-total', {} ]
				]
			]
		]
	]
];

function CartLineItemTemplateInnerBlocks() {

	const innerBlocksProps = useInnerBlocksProps( {
		className: 'wc-block-cart-items__row',
		//	template: TEMPLATE,
	});

	return ( 
		<li { ...innerBlocksProps } />
	);
}

const Edit = ( { clientId } ) => {
	const blockProps = useBlockProps( {
		className: 'wc-block-cart-line-items',
	} );

	return (
		<ul { ...blockProps }>

			{ previewCart?.items?.map( ( previewCartItem ) => (
				<BlockContextProvider key={previewCartItem.key} value={ {'woocommerce/cartItem' : previewCartItem } }>
					<CartLineItemTemplateInnerBlocks />
				</BlockContextProvider>
			) ) }
	
		</ul>

	);
}

export default Edit;
