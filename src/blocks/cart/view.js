/**
 * External dependencies
 */
import { store, getElement, getContext, withScope } from '@wordpress/interactivity';
const md5 = require('md5');

// These can't be imported in modules yet.
const { select, subscribe } = wp.data;

import './style.scss';

const cartStoreKey = 'wc/store/cart';

const getInputElementFromEvent = (e) => {
	const target = e.target;

	const inputElement = target.parentElement?.querySelector(
		'.wc-block-components-quantity-selector__input'
	);

	return inputElement;
};

const getInputData = (e) => {
	const inputElement = getInputElementFromEvent(e);

	if (!inputElement) {
		return;
	}

	const parsedValue = parseInt(inputElement.value, 10);
	const parsedMinValue = parseInt(inputElement.min, 10);
	const parsedMaxValue = parseInt(inputElement.max, 10);
	const parsedStep = parseInt(inputElement.step, 10);

	const currentValue = isNaN(parsedValue) ? 0 : parsedValue;
	const minValue = isNaN(parsedMinValue) ? 1 : parsedMinValue;
	const maxValue = isNaN(parsedMaxValue) ? undefined : parsedMaxValue;
	const step = isNaN(parsedStep) ? 1 : parsedStep;

	return {
		currentValue,
		minValue,
		maxValue,
		step,
		inputElement,
	};
};

const dispatchChangeEvent = (inputElement) => {
	const event = new Event('change');

	inputElement.dispatchEvent(event);
};

const { state, actions } = store( 'woocommerce/cart', {

	state: {
		isUpdating: false,
	},
	actions: {
		*refreshCart() {

			console.debug('refreshing cart' );

			// Set loading state
			state.isUpdating = true;
	
			// Dynamically import interactivity-router and navigate
			const { actions } = yield import('@wordpress/interactivity-router');
			yield actions.navigate('', { force: true, loadingAnimation: true });
	
			// Set loading state to false after navigation
			state.isUpdating = false;

		},
		addQuantity: ( event ) => {
			const inputData = getInputData( event );

			if ( ! inputData ) {
				return;
			}
			const { currentValue, maxValue, step, inputElement } = inputData;
			const newValue = currentValue + step;

			if ( maxValue === undefined || newValue <= maxValue ) {
				inputElement.value = newValue.toString();
				dispatchChangeEvent( inputElement );
			}
		},
		removeQuantity: ( event ) => {
			const inputData = getInputData( event );

			if ( ! inputData ) {
				return;
			}
			const { currentValue, minValue, step, inputElement } = inputData;
			const newValue = currentValue - step;

			if ( newValue >= minValue ) {
				inputElement.value = newValue.toString();
				dispatchChangeEvent( inputElement );
			}
		},
		*updateQuantity( e ) {

			e.preventDefault();

			const context = getContext();

			const { ['woocommerce/cartItem']: cartItem } = context;

			context.isDisabled = true;

			if (isNaN(e.currentTarget.value)) {
				return;
			}

			const quantity = parseFloat( e.currentTarget.value );

			if ( quantity > 0 ) {
				try {
					const response = yield fetch(`/wp-json/wc/store/v1/cart/update-item?key=${cartItem.key}&quantity=${quantity}`, {
						method: 'POST',
						headers: {
							'Nonce': state?.nonce || '',
						}
					});
			
					const data = yield response.json();
			
					actions.refreshCart();
	
					context.isDisabled = false;
	
				} catch (error) {
					console.debug('Error removing cart', error);
				}
			} else {
				actions.removeItem( e );
			}

		},
		*removeItem( e ) {

			e.preventDefault();

			const context = getContext();

			const { ['woocommerce/cartItem']: cartItem } = context;

			context.isDisabled = true;

			try {
				const response = yield fetch(`/wp-json/wc/store/v1/cart/remove-item?key=${cartItem.key}`, {
					method: 'POST',
					headers: {
						'Nonce': state?.nonce || '',
					}
				});
		
				const data = yield response.json();

				state.isCartEmpty = data.items_count === 0;
		
				actions.refreshCart();

				context.isDisabled = false;

			} catch (error) {
				console.debug('Error removing cart item', error);
			}

		}
	},
	callbacks: {
		init() {
			console.debug('initial cart hash', state.cartHash);
		},

	},

} );

// Subscribe to changes in the cart store.

const unsubscribe = subscribe(() => {
	const cartData = select(cartStoreKey).getCartData();
	if (!cartData || cartData.itemsCount === undefined || !cartData.items) return;

	const { itemsCount, items } = cartData;

	const clientCartItems = items.map(item => ({
		id: item.id,
		quantity: item.quantity
	}));

	const clientCartHash = md5(JSON.stringify(clientCartItems));

	// Detect actual cart updates.
	if (clientCartHash !== state.cartHash) {
		console.debug('Cart change detected, refreshing cart...');
		state.cartHash = clientCartHash;
		state.isCartEmpty = itemsCount === 0;
		actions.refreshCart();
	}

}, cartStoreKey);

