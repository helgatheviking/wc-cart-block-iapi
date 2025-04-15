/**
 * WordPress dependencies
 */
import { useBlockProps, useInnerBlocksProps } from "@wordpress/block-editor";

const LINEITEMPRODUCTTEMPLATE = [
	['woocommerce/cart-line-item-name-block', {}],
	['woocommerce/cart-line-item-price-block', {}],
	['woocommerce/cart-line-item-quantity-block', {}],
	['woocommerce/cart-line-item-meta-block', {}],
	//['woocommerce/cart-line-item-remove-link-block', {}]
];

const Edit = ( { attributes, setAttributes, isSelected } ) => {
	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		template: LINEITEMPRODUCTTEMPLATE,
		templateLock: "all", // Lock the template so users can't add/remove blocks
	});

	return <div className="wc-block-cart-item__product" {...innerBlocksProps}></div>;
};

export default Edit;
