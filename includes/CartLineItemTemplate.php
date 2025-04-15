<?php
/**
 * Modify the Cart Line Items Template  Block.
 *
 * @package WC_Cart_Redux\Blocks
 */
namespace Backcourt\WcCartBlockRedux\CartLineItemTemplate;

defined( 'ABSPATH' ) || exit;

/**
 * Hooks
 */
add_filter( 'render_block_woocommerce/cart-line-item-template', __NAMESPACE__ . '\modify_block', 10, 3 );


/**
 * Modify the filled cart block
 *
 * @param string $content The block content.
 * @param array  $parsed_block The block being rendered.
 * @param WP_Block $instance The block instance.
 * @return string
 */
function modify_block( $content, $parsed_block, $instance ) {

	$p = new \WP_HTML_Tag_Processor( $content );

	while ( $p->next_tag( array( 'class_name' => 'wc-block-cart-item__remove-link' ) ) ) {
		// Now we're inside the right parent <div> — search its <a> child(ren)
		if ( $p->next_tag( [ 'tag_name' => 'a' ], [ 'scope' => 'children' ] ) ) {
			$p->set_attribute( 'data-wp-on--click', 'actions.removeItem' );
		}
	}

	return $p->get_updated_html();

}


	
