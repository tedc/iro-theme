<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="shipping" ng-repeat="shipping in shippings track by $index">
	<h2 class="shipping__subtitle" ng-bind-html="shipping.package_name"></h2>
	<div class="shipping__packages shipping__packages--grow-md" ng-attr-data-title="{{shipping.package_name}}">
		<ul id="shipping_method" class="shipping__method" ng-init="checkoutFields.shipping_method[shipping.index]=shipping.chosen_method">
			<li class="shipping__header">
				<span><?php _e('Modalità', 'iro'); ?></span>
				<span><?php _e('Prezzo', 'iro'); ?></span>
			</li>
			<li class="shipping__row" ng-repeat="s in shipping.methods" on-finish-render="update_scroller">
				<div class="shipping__wrapper shipping__wrapper--grow-md">
					<input type="radio" ng-model="checkoutFields.shipping_method[shipping.index]" name="shipping_method[{{shipping.index}}]" id="shipping_method_{{shipping.index}}_{{s.id}}" ng-value="s.value" class="shipping__radio">
					<label for="shipping_method_{{shipping.index}}_{{s.id}}" ng-bind-html="s.label"></label>
					<span class="shipping__price" ng-bind-html="s.price"></span>
				</div>
				<div class="shipping__compile" bind-html-compile="s.extras" ng-if="s.extras"></div>		
			</li>
		</ul>
	</div>
</div>
