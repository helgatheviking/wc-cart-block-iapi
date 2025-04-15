<?php
/**
 * Server rendering for the Cart Line Item Meta block.
 *
 * @package WooCommerce/blocks
 * 
 * @param array $attributes - The block attributes.
 * @param string $content - The block default content.
 * @param WP_Block $block - The block instance.
 */
?>
<td class="wc-block-cart-item__product">
    <div class="wc-block-cart-item__wrap">
        <?php echo $content; ?>
    </div>
</td>