<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
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
 * @version     3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}
$image_id = get_post_thumbnail_id();
$image = wp_get_attachment_image_src($image_id, 'shop_thumbnail');

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
	<input type="hidden" ng-init="product.original_id=<?php echo $product->get_id(); ?>" ng-model="product.origina_id" />
	<ngcart-addtocart id="<?php echo $product->get_id(); ?>" price="<?php echo $product->get_price(); ?>" name="<?php echo $product->get_title(); ?>" quantity="<?php echo $product->get_min_purchase_quantity(); ?>" quantity-max="<?php echo $product->get_max_purchase_quantity(); ?>" template-url="addtocart.html" data="{href : '<?php echo $product->get_slug(); ?>', image : {thumb_src : '<?php echo $image[0]; ?>'}}">
		<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
	</ngcart-addtocart>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>