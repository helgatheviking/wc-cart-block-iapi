<?php
/**
 * Server rendering for the Cart Line Item Template block.
 *
 * @package WooCommerce/blocks
 * 
 * @param array $attributes - The block attributes.
 * @param string $content - The block default content.
 * @param WP_Block $block - The block instance.
 */

if ( ! wc()->cart instanceof \WC_Cart ) {
	return '';
}

$classnames = 'wc-block-cart-items';

?>

<ul class="wc-block-cart-line-items" tabindex="-1">
	<caption class="screen-reader-text"><h2 class="screen-reader-text"><?php esc_html_e( 'Products in cart', 'woocommerce' ); ?></h2></caption>

	<?php
	foreach( wc()->cart->get_cart() as $cart_item_key => $cart_item ) {

		// Get the parsed block of the current Template block.
		$parsed_block = $block->parsed_block;

		// Set the block name to one that does not correspond to an existing registered block.
		// This ensures that for the inner instances of the Post Template block, we do not render any block supports.
		$parsed_block['blockName'] = 'core/null';

		// Relay the block context to the inner blocks.
		$available_context = array_merge(
			(array) $block->context,
			array(
				'productId' => $cart_item['product_id'],
				'woocommerce/cartItem'   => $cart_item,
				'isDisabled' => false,
			)
		);

		// Render the inner blocks of the Post Template block with `dynamic` set to `false` to prevent calling
		// `render_callback` and ensure that no wrapper markup is included.
		$block_content = (
			new \WP_Block(
				$parsed_block,
				$available_context
			)
		)->render( array( 'dynamic' => false ) );

		$item_directives = '
			data-wp-interactive="woocommerce/cart"
			data-wp-key="cart-item-' . $cart_item_key . '"
			data-wp-class--is-disabled="context.isDisabled"
		';

		// Wrap the render inner blocks in a `li` element with the appropriate post classes.
		$cart_item_classes = implode( ' ', array( 'wc-block-cart-items__row' ) ); // @todo - needs a filter.
		echo strtr(
			'<li class="{classes}"
				{item_directives}
				{item_context}
			>
				{content}
			</li>',
			array(
				'{classes}'         => esc_attr( $cart_item_classes ),
				'{item_directives}' => $item_directives,
				'{item_context}'    => wp_interactivity_data_wp_context( $available_context ),
				'{content}'         => $block_content,
			)
		);
	}

	?>

</ul>
