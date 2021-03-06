<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$fields = (!is_wc_endpoint_url( 'order-pay' )) ? 'checkoutFields' : 'payFields';
$chosen = ( $gateway->chosen ) ? ' ng-init="'.$fields.'.payment_method = \''.esc_attr( $gateway->id ) .'\'; '.$fields.'.payment_label = \''. $gateway->get_title() .'\'"' : '';
?>
<li class="payment__method payment__method--grow<?php echo (is_wc_endpoint_url( 'order-pay' )) ? '' : '-md'; ?> payment__method--grid payment__method--<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="payment__radio" ng-model="<?php echo $fields; ?>.payment_method" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"<?php echo $chosen; ?> />

	<label for="payment_method_<?php echo $gateway->id; ?>" ng-click="<?php echo $fields; ?>.payment_label = '<?php echo $gateway->get_title(); ?>'">
		<?php echo $gateway->get_title(); ?>
	</label>
	<?php 
		echo '<span class="payment__icon">'.$gateway->get_icon().'</span>';
	?>
</li>
