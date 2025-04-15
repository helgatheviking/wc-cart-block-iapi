/**
 * External dependencies
 */
import Dinero from 'dinero.js';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { FormattedMonetaryAmount } from '@woocommerce/blocks-components'; // ProductPrice cannot be imported yet.
import { getCurrencyFromPriceResponse } from '@woocommerce/price-format';

/**
 * Convert a Dinero object with precision to store currency minor unit.
 *
 * @param {Dinero} priceObject Price object to convert.
 * @param {Object} currency    Currency data.
 * @return {number} Amount with new minor unit precision.
 */
const getAmountFromRawPrice = ( priceObject, currency ) => {
	return priceObject.convertPrecision( currency.minorUnit ).getAmount();
};

/**
 * Internal dependencies
 */
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: ( { context: { 'woocommerce/cartItem': cartItem } } ) => {

		const blockProps = useBlockProps( {
			className: 'wc-block-cart-item__total',
		} );

		
		const priceCurrency = getCurrencyFromPriceResponse( cartItem.prices );

		const regularAmountSingle = Dinero( {
			amount: parseInt( cartItem.prices.raw_prices.regular_price, 10 ),
			precision: cartItem.prices.raw_prices.precision,
		} );
		const purchaseAmountSingle = Dinero( {
			amount: parseInt( cartItem.prices.raw_prices.price, 10 ),
			precision: cartItem.prices.raw_prices.precision,
		} );

		return (
			<div { ...blockProps }>

				<FormattedMonetaryAmount
					currency={ priceCurrency }
					className="wc-block-price-filter__amount wc-block-price-filter__amount--min wc-block-form-text-input wc-block-components-price-slider__amount wc-block-components-price-slider__amount--min"
					value={ cartItem.totals.line_subtotal }
				/>

			</div>
		);
		
	}
} );
