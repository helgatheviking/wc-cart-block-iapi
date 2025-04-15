<?php
/**
 * Server rendering for the Cart Line Item Name block.
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

if ( $_product->is_visible() ) {
    printf( '<a href="%s" tabindex="-1">' . $_product->get_name() . '</a>', $_product->get_permalink( $cart_item ) );
} else {
    echo $_product->get_name();
}

