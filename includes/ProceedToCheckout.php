<?php
/**
 * Modify the Proceed To Checkout Block.
 *
 * @package Backcourt\WcCartBlock\iAPI;
 */
namespace Backcourt\WcCartBlock\iAPI;

defined( 'ABSPATH' ) || exit;

class ProceedToCheckout {

	public static function attach_hooks() {
		add_filter( 'block_type_metadata', array( __CLASS__, 'modify_metadata' ) );
		add_filter( 'render_block_woocommerce/proceed-to-checkout-block', array( __CLASS__, 'modify_block' ), 10, 3 );
	}

	public static function modify_metadata( $metadata ) {
		if ( 'woocommerce/proceed-to-checkout-block' === $metadata['name'] ) {
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
		ob_start();
		?>
		<div class="wc-block-cart__submit wp-block-woocommerce-proceed-to-checkout-block">
			<div aria-hidden="true" style="inset: 0px; opacity: 0; pointer-events: none; position: absolute; z-index: -1;"></div>
			<div class="wc-block-cart__submit-container">
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="wc-block-components-button wp-element-button wc-block-cart__submit-button contained"><span class="wc-block-components-button__text"><?php esc_html_e( 'Proceed to Checkout', 'woocommerce' ); ?></span></a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}