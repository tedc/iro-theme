<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
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

if ( !($max_value && $min_value === $max_value) ) {
	?>
	<div class="quantity quantity--grow-top">
		<label class="quantity__label" for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></label>
		<div class="quantity__wrapper">
			<span class="quantity__control quantity__control--minus" ng-click="qty = (qty - 1 > 0) ? (qty - 1) : 1">
				<i class="icon-arrow-down"></i>
			</span>
			<input type="number" id="<?php echo esc_attr( $input_id ); ?>" class="quantity__number qty text" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" size="4" pattern="<?php echo esc_attr( $pattern ); ?>" inputmode="<?php echo esc_attr( $inputmode ); ?>" ng-model="qty" ng-init="qty = <?php echo esc_attr( $min_value ); ?>" />
			<span class="quantity__control quantity__control--plus"<?php if($max_value > 0) : ?> ng-click="qty = ((qty + 1) < quantityMax) ? qty + 1 : quantityMax "<?php else : ?> ng-click="qty = qty + 1"<?php endif; ?>>
				<i class="icon-arrow-up"></i>
			</span>
		</div>
	</div>
	<?php
}
