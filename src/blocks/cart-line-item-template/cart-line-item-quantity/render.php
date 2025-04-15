<?php
/**
 * Server rendering for the Cart Line Item Quantity block.
 *
 * @package WooCommerce/blocks
 * 
 * @param array $attributes - The block attributes.
 * @param string $content - The block default content.
 * @param WP_Block $block - The block instance.
 */
$cart_item = $block->context['woocommerce/cartItem'] ?? [];

if ( empty( $cart_item ) ) {
	return '';
}

$_product = $cart_item['data'];

if ( ! $_product ) {
	return '';
}

$min  = 1;
$step = 1;
$max  = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();

if ( $max = -1 ) {
	$max = 9999; // @todo - handle this
}

// @todo - add support for filtering min/max/step quantity.
?>

<div class="wc-block-cart-item__quantity">
	<div class="wc-block-components-quantity-selector">
		<input data-wp-on--change="actions.updateQuantity" class="wc-block-components-quantity-selector__input" type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" aria-label="<?php echo sprintf( esc_attr( 'Quantity of %s in your cart', 'woocommerce' ), $_product->get_title() ); ?>" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>">
		<button data-wp-on--click="actions.removeQuantity" aria-label="<?php echo sprintf( esc_attr( 'Reduce quantity of %s', 'woocommerce' ), $_product->get_title() ); ?>" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--minus">－</button>
		<button data-wp-on--click="actions.addQuantity" aria-label="<?php echo sprintf( esc_attr( 'Increase quantity of %s', 'woocommerce' ), $_product->get_title() ); ?>I" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--plus">＋</button>
	</div>
</div>
