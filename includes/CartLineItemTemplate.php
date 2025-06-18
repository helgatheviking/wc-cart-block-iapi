<?php
/**
 * Modify the Cart Line Items Template Block.
 *
 * @package Backcourt\WcCartBlock\iAPI;
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class CartLineItemTemplate {

	public static function attach_hooks() {
		add_filter( 'render_block_woocommerce/cart-line-item-template', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	public static function modify_block( $content, $parsed_block, $instance ) {
		$p = new \WP_HTML_Tag_Processor( $content );

		while ( $p->next_tag( array( 'class_name' => 'wc-block-cart-item__remove-link' ) ) ) {
			if ( $p->next_tag( array( 'tag_name' => 'a' ), array( 'scope' => 'children' ) ) ) {
				$p->set_attribute( 'data-wp-on--click', 'actions.removeItem' );
			}
		}

		return $p->get_updated_html();
	}
}
