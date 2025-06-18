/**
 * WordPress dependencies
 */
import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './style.scss';

registerBlockVariation( 'woocommerce/cart', {
	name: 'cart-iapi',
	title: __( 'Cart with iAPI', 'woocommerce' ),
	icon: 'cart',
	attributes: {
		align: 'wide',
		className: 'wc-cart-iapi',
	},
	scope: [ 'inserter' ],
	innerBlocks: [
		[
			'woocommerce/filled-cart-block',
			{},
			[
				[
					'woocommerce/cart-items-redux',
					{},
					[
						[
							'core/columns',
							{
								verticalAlignment: 'top',
								className: 'wc-block-cart-items__header',
							},
							[
								[
									'core/column',
									{
										verticalAlignment: 'top',
										width: '20%',
									},
									[
										[
											'core/heading',
											{
												level: 2,
												content: __(
													'Product',
													'woocommerce'
												),
												className:
													'wc-block-cart-items__header-product',
											},
										],
									],
								],
								[
									'core/column',
									{ verticalAlignment: 'top', width: '60%' },
								],
								[
									'core/column',
									{
										verticalAlignment: 'top',
										width: '20%',
									},
									[
										[
											'core/heading',
											{
												level: 2,
												content: __(
													'Total',
													'woocommerce'
												),
												className:
													'wc-block-cart-items__header-total',
											},
										],
									],
								],
							],
						],
						[
							'woocommerce/cart-line-item-template',
							{},
							[
								[
									'core/columns',
									{ verticalAlignment: 'top' },
									[
										[
											'core/column',
											{
												verticalAlignment: 'top',
												width: '20%',
											},
											[
												[
													'woocommerce/cart-line-item-image',
													{},
												],
											],
										],
										[
											'core/column',
											{
												verticalAlignment: 'top',
												width: '60%',
											},
											[
												[
													'woocommerce/cart-line-item-name',
													{},
												],
												[
													'woocommerce/cart-line-item-price',
													{},
												],
												[
													'woocommerce/cart-line-item-meta',
													{},
												],
												[
													'woocommerce/cart-line-item-quantity',
													{},
												],
												[
													'core/button',
													{
														className:
															'wc-block-cart-item__remove-link',
														text: __(
															'Remove item',
															'woocommerce'
														),
														variant: 'link',
													},
												],
											],
										],
										[
											'core/column',
											{
												verticalAlignment: 'top',
												width: '20%',
											},
											[
												[
													'woocommerce/cart-line-item-total',
													{},
												],
											],
										],
									],
								],
							],
						],
					],
				],
				[
					'woocommerce/cart-totals-block',
					{},
					[
						[
							'core/group',
							{
								className:
									'wp-block-woocommerce-cart-order-summary-block',
							},
							[
								[
									'core/heading',
									{
										level: 2,
										content: __(
											'Cart Totals',
											'woocommerce'
										),
										className:
											'wp-block-woocommerce-cart-order-summary-heading-block wc-block-cart__totals-title',
									},
								],
								[
									'woocommerce/cart-order-summary-coupon-form-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-subtotal-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-fee-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-discount-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-shipping-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-taxes-block',
									{},
								],
								[
									'woocommerce/cart-order-summary-total-block',
									{},
								],
							],
						],
						[ 'woocommerce/cart-express-payment-block', {} ],
						[ 'woocommerce/proceed-to-checkout-block', {} ],
						[
							'woocommerce/cart-accepted-payment-methods-block',
							{},
						],
					],
				],
			],
		],
		[
			'woocommerce/empty-cart-block',
			{},
			[
				[
					'core/heading',
					{
						level: 3,
						content: __(
							'Your cart is currently empty!',
							'woocommerce'
						),
						textAlign: 'center',
						className:
							'with-empty-cart-icon wc-block-cart__empty-cart__title',
					},
				],
				[ 'core/separator', { className: 'is-style-dots' } ],
				[
					'woocommerce/product-new',
					{
						columns: 4,
						rows: 1,
					},
				],
			],
		],
	],
} );

/*


registerBlockVariation('woocommerce/cart', {
	name: 'cart-iapi',
	title: __('Cart with iAPI', 'woocommerce'),
	icon: 'cart',
	attributes: {
		align: 'wide',
		className: 'wc-cart-iapi',
	},
	scope: ['inserter'],
	innerBlocks: [
		[
			'woocommerce/filled-cart-block',
			{},
			[
				[
					'woocommerce/cart-items-redux',
					{},
		
					[
						[
							'woocommerce/cart-line-item-template',
							{},
							[
								[
									'core/columns',
									{ verticalAlignment: 'top' },
									[
										[
											'core/column',
											{ verticalAlignment: 'top', width: '20%' },
											[
												[ 'woocommerce/cart-line-item-image', {} ]
											]
										],
										[
											'core/column',
											{ verticalAlignment: 'top', width: '60%' },
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
											{ verticalAlignment: 'top', width: '20%' },
											[
												[ 'woocommerce/cart-line-item-total', {} ]
											]
										]
									]
								]
							]
						],

					],
		
				],
				[
					'woocommerce/cart-totals-block',
					{},
					[
						[
							'woocommerce/cart-order-summary-block',
							{},
							[
								[
									'core/heading',
									{
										level: 2,
										content: __('Cart Totals', 'woocommerce'),
										className: 'wp-block-woocommerce-cart-order-summary-heading-block wc-block-cart__totals-title',
									},
								],
								['woocommerce/cart-order-summary-coupon-form-block', {}],
								['woocommerce/cart-order-summary-subtotal-block', {}],
								['woocommerce/cart-order-summary-fee-block', {}],
								['woocommerce/cart-order-summary-discount-block', {}],
								['woocommerce/cart-order-summary-shipping-block', {}],
								['woocommerce/cart-order-summary-taxes-block', {}],
							],
						],
						['woocommerce/cart-express-payment-block', {}],
						['woocommerce/proceed-to-checkout-block', {}],
						['woocommerce/cart-accepted-payment-methods-block', {}],
					],
				],

				
			],
		],
		[
			'woocommerce/empty-cart-block',
			{},
			[
				[
					'core/heading',
					{
						level: 3,
						content: __('Your cart is currently empty!', 'woocommerce'),
						textAlign: 'center',
						className: 'with-empty-cart-icon wc-block-cart__empty-cart__title',
					},
				],
				['core/separator', { className: 'is-style-dots' }],
				[
					'woocommerce/product-new',
					{
						columns: 4,
						rows: 1,
					},
				],
			],
		],
	],
});
*/
