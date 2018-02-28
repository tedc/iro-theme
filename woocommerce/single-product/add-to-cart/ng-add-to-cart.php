<?php
	/**
	 * @since 3.0.0.
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	global $product;
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input( array(
		'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
		'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
		'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
	) );

	/**
	 * @since 3.0.0.
	 */
	do_action( 'woocommerce_after_add_to_cart_quantity' );
?>
<span class="add-to-cart__wrapper add-to-cart__wrapper--grow-top" ng-class="{'add-to-cart__wrapper--loading' : ngCart.isUpdating}" ng-sm="[{'triggerHook' : 'onLeave', 'triggerElement':'#product-trigger', 'offset': 80, 'class':'add-to-cart__wrapper--fixed'}, {'triggerHook' : 'onLeave','triggerElement':'#product-trigger', offset: 140, 'class':'add-to-cart__wrapper--fixed-inview'}, , {'triggerHook' : 'onLeave','triggerElement':'.footer', offset: 140, 'class':'add-to-cart__wrapper--fixed-inview-hidden'}]">
<span class="add-to-cart__button" ng-class="{'add-to-cart__button--loading' : ngCart.isUpdating}" ng-transclude ng-click="ngCart.addItem(id, name, price, qty, data)"></span>
</span>