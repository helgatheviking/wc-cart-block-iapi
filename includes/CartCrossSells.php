<?php
/**
 * Modify the Cart Cross Sells Block.
 *
 * @package Backcourt\WcCartBlock\iAPI;
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class CartCrossSells {

	/**
	 * Attach hooks for the Cart Cross Sells functionality.
	 */
	public static function attach_hooks() {
		add_filter( 'block_type_metadata', array( __CLASS__, 'modify_metadata' ) );
		add_filter( 'render_block_woocommerce/cart-cross-sells-block', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	/**
	 * Modify the block metadata to use context.
	 *
	 * @param array $metadata Metadata for registering a block type.
	 * @return array
	 */
	public static function modify_metadata( $metadata ) {
		if ( 'woocommerce/cart-cross-sells-block' === $metadata['name'] ) {
			$metadata['usesContext'] ??= array();
			$metadata['usesContext'][] = 'woocommerce/isiAPICart';
		}
		return $metadata;
	}

	/**
	 * Modify the filled cart block
	 *
	 * @param string   $content The block content.
	 * @param array    $parsed_block The block being rendered.
	 * @param WP_Block $instance The block instance.
	 * @return string
	 */
	public static function modify_block( $content, $parsed_block, $instance ) {
		$is_iapi = boolval( $instance->context['woocommerce/isiAPICart'] ?? false );

		if ( ! $is_iapi ) {
			return $content;
		}

		$p = new \WP_HTML_Tag_Processor( $content );

		if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-cart-cross-sells-block' ) ) ) {
			$p->set_attribute( 'data-wp-class--hidden', 'state.isCartEmpty' );
		}

		return $p->get_updated_html();
	}
}
