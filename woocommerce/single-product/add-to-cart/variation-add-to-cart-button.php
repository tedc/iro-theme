<?php
/**
 * Single variation cart button
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
$defautls = iro_get_default_attributes($product);
$variation_id = iro_find_matching_product_variation($product, $defautls);
?>
<input type="hidden" ng-init="product.original_id=<?php echo $product->get_id(); ?>" ng-model="product.origina_id" />
<ngcart-addtocart id="{{(product.product_id) ? product.product_id : <?php echo ($variation_id > 0) ? $variation_id : $product->get_id(); ?>}}" price="<?php echo $product->get_price(); ?>" name="<?php echo $product->get_title(); ?>" quantity="<?php echo $product->get_min_purchase_quantity(); ?>"<?php if($product->get_max_purchase_quantity() > 0) : ?> quantity-max="<?php echo $product->get_max_purchase_quantity(); ?>"<?php endif; ?> template-url="addtocart.html" data="{href : '<?php echo $product->get_slug(); ?>'}">
	<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
</ngcart-addtocart>
<?php
$plus = wp_get_post_terms($product->get_id(), 'product_plus', array('oderby'=> 'term_order'));
if($plus) :
foreach ($plus as $p) :
	if(get_field('popup_kind', $p) && get_field('popup_kind', $p) == 'form') {
		echo '<span class="add-to-cart__open" ng-click="isSizeForm=true">'.__('Non trovi la tua misura? Richiedila qui', 'iro').'</span>';
	}
endforeach;
endif;
?>
