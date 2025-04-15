<?php
/**
 * Modify the Cart Order Summary Block.
 *
 * @package WC_Cart_Redux\Blocks
 */
namespace Backcourt\WcCartBlockRedux\CartOrderSummaryTaxes;

defined( 'ABSPATH' ) || exit;

/**
 * Hooks
 */
add_filter( 'block_type_metadata', __NAMESPACE__ . '\modify_metadata', );
add_filter( 'render_block_woocommerce/cart-order-summary-taxes-block', __NAMESPACE__ . '\modify_block', 10, 3 );

/**
 * Modify the block metadata to use context.
 *
 * @param array $metadata Metadata for registering a block type.
 * @return array
 */
function modify_metadata( $metadata ) {
	if ( 'woocommerce/cart-order-summary-taxes-block' === $metadata['name'] ) {
		$metadata['usesContext'] ??= [];
		$metadata['usesContext'][] = 'woocommerce/isiAPICart';
	}
	return $metadata;
}

/**
 * Modify the filled cart block
 *
 * @param string $content The block content.
 * @param array  $parsed_block The block being rendered.
 * @param WP_Block $instance The block instance.
 * @return string
 */
function modify_block( $content, $parsed_block, $instance ) {

	if ( ! wc()->cart instanceof \WC_Cart ) {
		return $content;
	}

	$is_iapi = boolval( $instance->context['woocommerce/isiAPICart'] ?? false );

	if ( ! $is_iapi ) {
		return $content;
	}

	$tax_totals = WC()->cart->get_tax_totals();

	if ( empty( $tax_totals ) ) {
		return $content;
	}


	ob_start();

	?>

	<div class="wp-block-woocommerce-cart-order-summary-taxes-block wc-block-components-totals-wrapper">
		<div class="wc-block-components-totals-item wc-block-components-totals-taxes">
			<?php foreach( $tax_totals as $tax ) : ?>
				<span class="wc-block-components-totals-item__label"><?php echo esc_html( $tax->label ); ?></span>
				<div class="wc-block-components-totals-item__value">
					<?php echo wp_kses_post( wc_price( $tax->amount ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php 

	return ob_get_clean();

}
