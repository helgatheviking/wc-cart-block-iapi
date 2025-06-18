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
 * Text Domain:       wc-cart-block-iapi
 * Update URI:        http://github.com
 *
 * @package WcCartBlock
 */

namespace Backcourt\WcCartBlock\iAPI;

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
 * Check if the autoloader exists.
 * If it does, require it.
 * If it does not, show an admin notice and deactivate the plugin.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {

	// If the autoloader exists, require it.
	// This is used for Composer dependencies.
	include_once __DIR__ . '/vendor/autoload.php';
} else {
	// If the autoloader does not exist, deactivate the plugin and show an admin notice.
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'WC Mix and Match - Interactivity API error: Required dependencies are not installed. Please run <code>composer install</code> in the plugin directory.', 'wc-cart-block-iapi' ) . '</p></div>';
		}
	);
	// Deactivate the plugin to prevent fatal errors.
	add_action(
		'admin_init',
		function () {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

/**
 * Require all includes.
 */
Cart::attach_hooks();
CartCrossSells::attach_hooks();
CartLineItemTemplate::attach_hooks();
CartOrderSummary::attach_hooks();
CartOrderSummarySubtotal::attach_hooks();
CartOrderSummaryTaxes::attach_hooks();
EmptyCart::attach_hooks();
FilledCart::attach_hooks();
ProceedToCheckout::attach_hooks();

/**
 * Declare WooCommerce Features compatibility.
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( ! class_exists( 'Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			return;
		}

		// HPOS (Custom Order tables) compatibility.
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', plugin_basename( __FILE__ ), true );

		// Cart and Checkout Blocks.
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', plugin_basename( __FILE__ ), true );
	}
);

/**
 * Load the plugin textdomain for translations.
 */
add_action(
	'init',
	function () {
		load_plugin_textdomain( 'wc-cart-block-iapi', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
);

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
}
add_action( 'init', __NAMESPACE__ . '\block_init', 5 );

