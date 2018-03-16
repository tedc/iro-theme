<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_coupons_enabled() ) {
	return;
}
?>
<div ng-if="!ngCart.getExtras().coupons">
<?php

	$info_message = apply_filters( 'woocommerce_checkout_coupon_message', ' <a href="#" class="checkout__showcoupon" ng-if="ngCart.getExtras().coupons.length < 1">' . __( 'Hai un codice sconto?', 'iro' ) . '</a>' );
	wc_print_notice( $info_message, 'notice' );

?>

<div class="checkout__coupon checkout__coupon--grid-nowrap slide-toggle" ng-class="{'slide-toggle--visible':isCoupon}">
	<input type="hidden" ng-model="coupon_nonce" ng-init="heckoutFields.coupon_nonce='<?php echo wp_create_nonce( 'apply-coupon' ); ?>'" />
	<span class="checkout__button checkout__button--dark checkout__button--radius-right" ng-class="{'checkout__button--loading':ngCart.isDescountLoading}" ng-click="ngCart.applyCoupon(heckoutFields.coupon_code,heckoutFields. coupon_nonce)"><?php _e( 'Applica', 'iro' ); ?></span>
</div>
<div class="checkout__error" ng-if="ngCart.couponError" ng-bind-html="ngCart.couponError"></div>
</div>