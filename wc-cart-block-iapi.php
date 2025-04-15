<?php
/**
 * Plugin Name:       WC Cart Block iAPI
 * Description:       WC Cart block with interactivity API
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            helgatheviking
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-cart-block-redux
 * Domain Path:       wc-cart-block-redux
 * Update URI:        http://github.com
 *
 * @package WcCartBlockRedux
 */

namespace Backcourt\WcCartBlockRedux;

defined( 'ABSPATH' ) || exit;

// Store the main plugin file.
const PLUGINFILE = __FILE__;

// Store the plugin version.
const VERSION = '1.0.0-alpha.1';

/**
 * Plugin path.
 *
 * @return string
 */
function get_path() {
	return trailingslashit( plugin_dir_path( PLUGINFILE ) );
}

/**
 * Require all includes.
 */
foreach ( glob( get_path() . 'includes/*.php', GLOB_BRACE ) as $file ) {
	require_once $file;
}

/**
 * Declare WooCommerce Features compatibility.
 */
add_action( 'before_woocommerce_init', function() {
	if ( ! class_exists( 'Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		return;
	}

	// HPOS (Custom Order tables) compatibility.
	\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', plugin_basename( __FILE__ ), true );

	// Cart and Checkout Blocks.
	\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', plugin_basename( __FILE__ ), true );

} );


/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function block_init() {
	register_block_type( get_path() . 'build/cart-order-summary-total' );
	register_block_type( get_path() . 'build/cart-items-redux' );
	register_block_type( get_path() . 'build/cart-line-item-template' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-image' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-name' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-price' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-quantity' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-meta' );
	register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-total' );
	
	//register_block_type( get_path() . 'build/cart-line-item-template/cart-line-item-remove-link' );
}
add_action( 'init', __NAMESPACE__ . '\block_init', 5 );


/**
 * Register our custom block type variations. (DO NOT NEED BOTH SCRIPT AND PHP);
 *
 * @param array    $variations Array of block type variations.
 * @param WP_Block $block_type  The block type.
 * @return array
 */
function block_type_variations( $variations, $block_type ) {
	if ( $block_type->name !== 'woocommerce/cart' ) {
		return $variations;
	}

	$variations[] = array(
		'name'        => 'cart-iapi',
		'title'       => __( 'Cart with iAPI', 'text-domain' ),
		'icon'        => 'cart',
		'attributes'  => array(
			'align'     => 'wide',
			'className' => 'wc-cart-iapi',
		),
		'scope'       => array( 'inserter' ),
		'innerBlocks' => array(
			array( 'woocommerce/filled-cart-block', array(), array(
				array( 'woocommerce/cart-items-block', array( 'placeholder' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent et eros eu felis.', 'text-domain' ) ), array(
					array( 'woocommerce/cart-line-items-block', array(), array(
						array( 'woocommerce/cart-line-item-template', array(), array(
							array( 'woocommerce/cart-line-item-image', array() ),
							array( 'woocommerce/cart-line-item-product', array(), array(
								array( 'woocommerce/cart-line-item-name', array() ),
								array( 'woocommerce/cart-line-item-price', array() ),
								array( 'woocommerce/cart-line-item-quantity', array() ),
								array( 'woocommerce/cart-line-item-meta', array() ),
								array( 'woocommerce/cart-line-item-remove-link', array() ),
							) ),
							array( 'woocommerce/cart-line-item-total', array() ),
							
						) )
					) )
				) ),
				array( 'woocommerce/cart-totals-block', array(), array(
					array( 'woocommerce/cart-order-summary-block', array(), array(
						array( 'core/heading', array(
							'level'       => 2,
							'placeholder' => __( 'Cart Totals', 'text-domain' ),
							'className'   => 'wp-block-woocommerce-cart-order-summary-heading-block wc-block-cart__totals-title',
						) ),
						array( 'woocommerce/cart-order-summary-coupon-form-block', array() ),
						array( 'woocommerce/cart-order-summary-subtotal-block', array() ),
						array( 'woocommerce/cart-order-summary-fee-block', array() ),
						array( 'woocommerce/cart-order-summary-discount-block', array() ),
						array( 'woocommerce/cart-order-summary-shipping-block', array() ),
						array( 'woocommerce/cart-order-summary-taxes-block', array() ),
					) ),
					array( 'woocommerce/cart-express-payment-block', array() ),
					array( 'woocommerce/proceed-to-checkout-block', array() ),
					array( 'woocommerce/cart-accepted-payment-methods-block', array() ),
				) )
			) ),
			array( 'woocommerce/empty-cart-block', array(), array(
				array( 'core/heading', array(
					'level'       => 3,
					'placeholder' => __( 'Your cart is currently empty!', 'text-domain' ),
					'textAlign'   => 'center',
					'className'   => 'with-empty-cart-icon wc-block-cart__empty-cart__title',
				) ),
				array( 'core/separator', array( 'className' => 'is-style-dots' ) ),
				array( 'woocommerce/product-new', array(
					'columns' => 4,
					'rows'    => 1,
				) ),
			) )
		)
	);

	return $variations;
}
//add_filter( 'get_block_type_variations', __NAMESPACE__ . '\block_type_variations', 10, 2 );


// Cache-bust all styles.
function add_timestamp( $src, $handle ) {

	if ( strpos( $handle, 'woocommerce-cart-items-redux' ) === false && strpos( $handle, 'woocommerce-cart-line-item' ) === false ) {
		return $src;
	}

	if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$src = add_query_arg( 'time', time(), $src );
	}
	return $src;
}
//add_filter( 'style_loader_src', __NAMESPACE__ . '\add_timestamp', 9999, 2 );