<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' );

 ?>
<div class="variations_form" ng-init="product.product_variations=<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>" ng-submit="addToCart()">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>
	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : 
		
	?>
		<div class="variations" cellspacing="0">
			<?php foreach ( $attributes as $attribute_name => $options ) : $radio = ($attribute_name == 'pa_color') ? true : false;
						?>
				<div class="variation variation--grow-top">
					<label class="variation__label" for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?><?php if($radio): ?><strong class="variation__chosen" ng-bind-html="removeEn(singleProductVariation.attribute_<?php echo  esc_attr( sanitize_title( $attribute_name ) ); ?>)"></strong><?php endif; ?></label>
					<div class="variation__items">
						<?php
							$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
							wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected, 'is_radio' => $radio ) );
							
						?>
					</div>
				</div>
			<?php endforeach; ?>
			<!-- <div class="variation__custom slide-toggle" ng-class="{'slide-toggle--visible': isCustomSize}">
				<h4 class="variation__title"><?php _e('Scegli le tue dimensioni', 'iro'); ?></h4>
				<div class="variation__units variation__units--grid">
					<div class="variation__unit">
						<input ng-change="validOnChange()" class="variation__input" type="text" ng-model="productCustomSize.extent" placeholder="<?php _e('Lunghezza', 'iro'); ?>"><span class="variation__per">x</span>
					</div>
					<div class="variation__unit">
						<input ng-change="validOnChange()" class="variation__input" type="text" ng-model="productCustomSize.width" placeholder="<?php _e('Larghezza', 'iro'); ?>"><span class="variation__per">x</span>
					</div>
					<div class="variation__unit">
						<input ng-change="validOnChange()" class="variation__input" type="text" ng-model="productCustomSize.height" placeholder="<?php _e('Altezza', 'iro'); ?>">
					</div>
					<div class="variation__unit"><strong class="variation__per"><?php _e('cm', 'iro'); ?></strong></div>
				</div>
			</div> -->
			<div class="variation__availability slide-toggle" ng-bind-html="attributes.availability_html" ng-class="{'slide-toggle--visible':attributes.availability_html}"></div>
		</div>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap">
			<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</div>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
