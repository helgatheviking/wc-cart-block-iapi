<?php
/**
 * Modify the AddToCartForm Block.
 *
 * @package Backcourt\Blocks\iAPI
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class Cart {

	/**
	 * Attach hooks for the Cart functionality.
	 */
	public static function attach_hooks() {
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_editor_assets' ), 20 );
		add_filter( 'block_type_metadata', array( __CLASS__, 'modify_metadata' ) );
		add_filter( 'render_block_context', array( __CLASS__, 'provide_context' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_filter( 'render_block_woocommerce/cart', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	/**
	 * Load the block editor assets.
	 */
	public static function enqueue_editor_assets() {
		$script_asset_path = \Backcourt\WcCartBlock\iAPI\get_path() . 'build/cart/index.asset.php';

		$script_asset = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => filemtime( \Backcourt\WcCartBlock\iAPI\get_path() . 'build/cart/index.js' ),
			);

		wp_enqueue_script(
			'wc-cart-block-iapi',
			plugins_url( 'build/cart/index.js', \Backcourt\WcCartBlock\iAPI\PLUGINFILE ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
	}

	/**
	 * Modify the block metadata to include interactivity support.
	 *
	 * @param array $metadata Metadata for registering a block type.
	 * @return array
	 */
	public static function modify_metadata( $metadata ) {
		if ( 'woocommerce/cart' === $metadata['name'] ) {
			$metadata['providesContext']                         ??= array();
			$metadata['providesContext']['woocommerce/isiAPICart'] = 'isiAPICart';
			$metadata['supports']                                ??= array();
			$metadata['supports']['interactivity']                 = true;
		}
		return $metadata;
	}

	/**
	 * Modify the block context.
	 *
	 * @param array $context The block context.
	 * @param array $block The block being rendered.
	 * @return array
	 */
	public static function provide_context( $context, $block ) {
		if ( 'woocommerce/cart' === $block['blockName'] ) {
			if ( isset( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'wc-cart-iapi' ) !== false ) {
				$context['woocommerce/isiAPICart'] = true;
			}
		}
		return $context;
	}

	/**
	 * Register script assets.
	 */
	public static function register_assets() {
		$script_asset_path = \Backcourt\WcCartBlock\iAPI\get_path() . 'build/cart/view.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => filemtime( \Backcourt\WcCartBlock\iAPI\get_path() . 'build/cart/view.js' ),
			);

		wp_register_script_module(
			'wc-cart-block-iapi',
			plugins_url( 'build/cart/view.js', \Backcourt\WcCartBlock\iAPI\PLUGINFILE ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_register_style(
			'wc-cart-block-iapi',
			plugins_url( 'build/cart/style-view.css', \Backcourt\WcCartBlock\iAPI\PLUGINFILE ),
			array(),
			filemtime( \Backcourt\WcCartBlock\iAPI\get_path() . 'build/cart/style-view.css' )
		);
	}

	/**
	 * Modify the cart block
	 *
	 * @param string   $content The block content.
	 * @param array    $parsed_block The block being rendered.
	 * @param WP_Block $instance The block instance.
	 * @return string
	 */
	public static function modify_block( $content, $parsed_block, $instance ) {
		if ( ! wc()->cart instanceof \WC_Cart ) {
			return $content;
		}

		$class_names = $parsed_block['attrs']['className'] ?? '';

		if ( strpos( $class_names, 'wc-cart-iapi' ) === false ) {
			return $content;
		}

		// Generate a hash based on cart items.
		$cart_items = array_values(
			array_map(
				function ( $item ) {
					return array(
						'id'       => $item['variation_id'] ?? $item['product_id'],
						'quantity' => $item['quantity'],
					);
				},
				WC()->cart->get_cart()
			)
		);

		$cart_hash = md5( json_encode( $cart_items ) );

		wp_interactivity_state(
			'woocommerce/cart',
			array(
				'nonce'       => wp_create_nonce( 'wc_store_api' ),
				'cartHash'    => $cart_hash,
				'cartItems'   => $cart_items,
				'isCartEmpty' => empty( wc()->cart->get_cart() ),
				'isUpdating'  => false,
			)
		);

		// Unload the default cart script.
		wp_dequeue_script( 'wc-cart-block-frontend' );

		wp_enqueue_script( 'wp-data' );
		wp_enqueue_script_module( 'wc-cart-block-iapi' );
		wp_enqueue_style( 'wc-cart-block-iapi' );

		$p = new \WP_HTML_Tag_Processor( $content );

		if ( $p->next_tag( array( 'class_name' => 'wp-block-woocommerce-cart' ) ) ) {
			$p->set_attribute( 'data-wp-interactive', 'woocommerce/cart' );
			$p->set_attribute( 'data-wp-router-region', 'cart' );
			$p->set_attribute( 'data-wp-init', 'callbacks.init' );
			$p->remove_class( 'is-loading' );
			$p->set_attribute( 'data-wp-class--is-updating', 'state.isUpdating' );
		}

		return $p->get_updated_html();
	}
}
