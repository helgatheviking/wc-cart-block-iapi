/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { FormattedMonetaryAmount } from '@woocommerce/blocks-components';
import { getCurrencyFromPriceResponse } from '@woocommerce/price-format';
import Dinero from 'dinero.js';

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

import { previewCart } from '@previews/cart';

registerBlockType( metadata, {
	edit: () => {
		const blockProps = useBlockProps();

		const priceCurrency = getCurrencyFromPriceResponse(
			previewCart.totals
		);

		const totalAmount = Dinero( {
			amount: parseInt( previewCart.totals.total_price, 10 ),
			precision: previewCart.totals.currency_minor_unit,
		} );

		return (
			<div { ...blockProps }>
				<FormattedMonetaryAmount
					currency={ priceCurrency }
					className="wc-block-price-filter__amount wc-block-price-filter__amount--min wc-block-form-text-input wc-block-components-price-slider__amount wc-block-components-price-slider__amount--min"
					value={ getAmountFromRawPrice(
						totalAmount,
						priceCurrency
					) }
				/>
			</div>
		);
	},
} );
