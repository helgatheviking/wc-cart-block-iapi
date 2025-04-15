<?php
/**
 * Server rendering for the Cart Line Item Price block.
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
?>
<div class="wc-block-cart-item__prices">
	<?php echo WC()->cart->get_product_price( $_product ); ?>
</div>

