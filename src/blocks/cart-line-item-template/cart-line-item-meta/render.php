<?php
/**
 * Server rendering for the Cart Line Item Meta block.
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

echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
