<?php
	/**
	 * @since 3.0.0.
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	global $product;
	// do_action( 'woocommerce_before_add_to_cart_quantity' );

	// woocommerce_quantity_input( array(
	// 	'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
	// 	'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
	// 	'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
	// ) );

	// /**
	//  * @since 3.0.0.
	//  */
	// do_action( 'woocommerce_after_add_to_cart_quantity' );
?>
<span class="add-to-cart__wrapper add-to-cart__wrapper--grow-top" id="product-button" ng-class="{'add-to-cart__wrapper--loading' : ngCart.isUpdating}">
<span class="add-to-cart__button" ng-class="{'add-to-cart__button--loading' : ngCart.isUpdating}" ng-transclude ng-click="ngCart.addItem(id, name, price, qty, data)"></span>
</span>
<span class="add-to-cart__wrapper add-to-cart__wrapper--grow-top add-to-cart__wrapper--fixed" ng-sm="{'triggerHook' : 'onLeave','triggerElement':'#product-<?php the_ID(); ?>', 'class':'add-to-cart__wrapper--fixed-inview'}">
<span class="add-to-cart__button" go-to="#product-price-trigger"><?php _e('Aggiungi al carrello', 'iro'); ?></span>
</span>