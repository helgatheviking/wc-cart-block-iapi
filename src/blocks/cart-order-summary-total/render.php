<?php
/**
 * Server rendering for the Cart Order Summary Total block.
 *
 * @package WooCommerce/blocks
 *
 * @param array $attributes - The block attributes.
 * @param string $content - The block default content.
 * @param WP_Block $block - The block instance.
 */

if ( ! wc()->cart instanceof \WC_Cart ) {
	return '';
}
?>

<div class="wc-block-components-totals-wrapper">
	<div class="wc-block-components-totals-item wc-block-components-totals-footer-item">
		<span class="wc-block-components-totals-item__label"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
		<div class="wc-block-components-totals-item__value">
			<?php \wc_cart_totals_order_total_html(); ?>
		</div>
		<div class="wc-block-components-totals-item__description"></div>
	</div>
</div>