<?php
/**
 * Modify the Empty Cart Block.
 *
 * @package WC_Cart_Redux\Blocks
 */
namespace Backcourt\WcCartBlockRedux\EmptyCart;

defined( 'ABSPATH' ) || exit;

/**
 * Hooks
 */
add_filter( 'block_type_metadata', __NAMESPACE__ . '\modify_metadata', );
add_filter( 'render_block_woocommerce/empty-cart-block', __NAMESPACE__ . '\modify_block', 10, 3 );

/**
 * Modify the block metadata to use context.
 *
 * @param array $metadata Metadata for registering a block type.
 * @return array
 */
function modify_metadata( $metadata ) {
	if ( 'woocommerce/empty-cart-block' === $metadata['name'] ) {
		$metadata['usesContext'] ??= [];
		$metadata['usesContext'][] = 'woocommerce/isiAPICart';
	}
	return $metadata;
}


/**
 * Modify the empty cart block
 *
 * @param string $content The block content.
 * @param array  $parsed_block The block being rendered.
 * @param WP_Block $instance The block instance.
 * @return string
 */
function modify_block( $content, $parsed_block, $instance ) {

	$is_iapi = boolval( $instance->context['woocommerce/isiAPICart'] ?? false );

	if ( ! $is_iapi ) {
		return $content;
	}

	$p = new \WP_HTML_Tag_Processor( $content );

	if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-empty-cart-block' ) ) ) {
		$p->set_attribute( 'data-wp-class--hidden', '!state.isCartEmpty' ); // @todo - deprecated.
	}

	return $p->get_updated_html();

}
