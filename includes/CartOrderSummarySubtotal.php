<?php
/**
 * Modify the Cart Order Summary Subtotal Block.
 *
 * @package Backcourt\WcCartBlock\iAPI;
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class CartOrderSummarySubtotal {

	public static function attach_hooks() {
		add_filter( 'block_type_metadata', array( __CLASS__, 'modify_metadata' ) );
		add_filter( 'render_block_woocommerce/cart-order-summary-subtotal-block', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	public static function modify_metadata( $metadata ) {
		if ( 'woocommerce/cart-order-summary-subtotal-block' === $metadata['name'] ) {
			$metadata['usesContext'] ??= array();
			$metadata['usesContext'][] = 'woocommerce/isiAPICart';
		}
		return $metadata;
	}

	public static function modify_block( $content, $parsed_block, $instance ) {
		$is_iapi = boolval( $instance->context['woocommerce/isiAPICart'] ?? false );
		if ( ! $is_iapi ) {
			return $content;
		}
		$p = new \WP_HTML_Tag_Processor( $content );
		if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-cart-order-summary-subtotal-block' ) ) ) {
			$p->set_attribute( 'data-wp-class--hidden', 'state.isCartEmpty' );
		}
		return $p->get_updated_html();
	}
}
