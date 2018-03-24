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
$shippings = array();
$packages = WC()->shipping->get_packages();
foreach ( $packages as $i => $package ) {
	$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
	$product_names = array();
	if ( count( $packages ) > 1 ) {
		foreach ( $package['contents'] as $item_id => $values ) {
			$product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
		}
		$product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
	}
	$package_name = apply_filters( 'woocommerce_shipping_package_name', ( ( $i + 1 ) > 1 ) ? sprintf( _x( 'Shipping %d', 'shipping packages', 'woocommerce' ), ( $i + 1 ) ) : _x( 'Shipping', 'shipping package', 'woocommerce' ), $i, $package );
	$available_methods = array();
	foreach($package['rates'] as $m) {
		$checked = ($m->id == $chosen_method) ? true : false;
		ob_start();
		do_action( 'woocommerce_after_shipping_rate', $m, $i );
		$e = ob_get_clean();
		$price = ($m->cost == 0) ? __('Gratuita', 'iro') : wc_price( $m->cost );
		$array = array('id' => sanitize_title( $m->id ), 'label' => wc_cart_totals_shipping_method_label( $m ), 'value' => esc_attr( $m->id ), 'checked' => $checked, 'extras' => $e, 'price' => $price);
		array_push($available_methods, $array);
	}
	array_push($shippings, array('package' => $package,
				'available_methods'        => $package['rates'],
				'methods' => $available_methods,
				'show_package_details'     => count( $packages ) > 1,
				'package_details'          => implode( ', ', $product_names ),
				/* translators: %d: shipping package number */
				'package_name'             => apply_filters( 'woocommerce_shipping_package_name', ( ( $i + 1 ) > 1 ) ? sprintf( _x( 'Shipping %d', 'shipping packages', 'woocommerce' ), ( $i + 1 ) ) : _x( 'Shipping', 'shipping package', 'woocommerce' ), $i, $package ),
				'index'                    => $i,
				'chosen_method'            => $chosen_method,
	            'chosen_label' => wc_cart_totals_shipping_method_label( $method ),
	            'chosen_price' => strip_tags($price),
	));
}
echo '<script>var shippings='.wp_json_encode($shippings).'</script>';
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