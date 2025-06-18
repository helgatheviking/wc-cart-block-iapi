<?php
/**
 * Modify the Empty Cart Block.
 *
 * @package Backcourt\WcCartBlock\iAPI;
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class EmptyCart {

	public static function attach_hooks() {
		add_filter( 'block_type_metadata', array( __CLASS__, 'modify_metadata' ) );
		add_filter( 'render_block_woocommerce/empty-cart-block', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	public static function modify_metadata( $metadata ) {
		if ( 'woocommerce/empty-cart-block' === $metadata['name'] ) {
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
		if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-empty-cart-block' ) ) ) {
			$p->set_attribute( 'data-wp-class--hidden', '!state.isCartEmpty' );
		}
		return $p->get_updated_html();
	}
}
