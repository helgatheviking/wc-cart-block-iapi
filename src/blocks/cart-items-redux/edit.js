/**
 * WordPress dependencies
 */
import {
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
/*
const TEMPLATE = [
	[
		'core/columns',
		{
			verticalAlignment: 'top',
			className: 'wc-block-cart-items__header',
			metadata: { 'name': __( 'Cart items header', 'woocommerce' ) }
		},
		[
			[
				'core/column',
				{ verticalAlignment: 'top', width: '20%' },
				[
					[
						'core/heading',
						{
							level: 3,
							fontSize: 'medium',
							content: __( 'Product', 'woocommerce' )
						}
					]
				]
			],
			[ 'core/column', { verticalAlignment: 'top', width: '60%' } ],
			[
				'core/column',
				{ verticalAlignment: 'top', width: '20%' },
				[
					[
						'core/heading',
						{
							level: 3,
							fontSize: 'medium',
							content: __( 'Total', 'woocommerce' ),
						}
					]
				]
			]
		]
	],
	[ 'woocommerce/cart-line-item-template', {} ],
];
*/

const Edit = () => {
	const blockProps = useBlockProps( {
		className: 'wc-block-cart-items-redux',
	} );

	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		className: 'wc-cart-items-redux',
		//	template: TEMPLATE,
	} );

	return (
		<div { ...innerBlocksProps }>
			<InnerBlocks />
		</div>
	);
};

export default Edit;
