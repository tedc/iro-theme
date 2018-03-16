<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see      https://docs.woocommerce.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="pay" ng-pay>
<form method="post" ng-submit="pay(payForm)" name="payForm" novalidate>
	<?php var_dump($order->get_id()); ?>
	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
				<th class="product-total"><?php esc_html_e( 'Totals', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( count( $order->get_items() ) > 0 ) : ?>
				<?php foreach ( $order->get_items() as $item_id => $item ) : ?>
					<?php
					if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
						continue;
					}
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<td class="product-name">
							<?php
								echo apply_filters( 'woocommerce_order_item_name', esc_html( $item->get_name() ), $item, false ); // @codingStandardsIgnoreLine

								do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

								wc_display_item_meta( $item );

								do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
							?>
						</td>
						<td class="product-quantity"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', esc_html( $item->get_quantity() ) ) . '</strong>', $item ); ?></td><?php // @codingStandardsIgnoreLine ?>
						<td class="product-subtotal"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td><?php // @codingStandardsIgnoreLine ?>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<?php if ( $totals = $order->get_order_item_totals() ) : ?>
				<?php foreach ( $totals as $total ) : ?>
					<tr>
						<th scope="row" colspan="2"><?php echo $total['label']; ?></th><?php // @codingStandardsIgnoreLine ?>
						<td class="product-total"><?php echo $total['value']; ?></td><?php // @codingStandardsIgnoreLine ?>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tfoot>
	</table>
	<div id="payment">
		<?php if ( $order->needs_payment() ) : ?>
			<ul class="wc_payment_methods payment_methods methods">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
				}
				?>
			</ul>
		<?php endif; ?>
		<div class="form-row">
			
			<?php wc_get_template( 'checkout/terms.php' ); ?>
			<?php do_action( 'woocommerce_pay_order_before_submit' ); ?>		
			<?php do_action( 'woocommerce_pay_order_after_submit' ); ?>
		</div>
	</div>
	<input type="hidden" ng-init="checkoutFields.key=<?php //echo $order; ?>" />
	<input type="hidden" ng-init="checkoutFields.order_pay=<?php //echo $order; ?>" />
	<input type="hidden" name="_wpnonce" value="1" ng-init="checkoutFields._wpnonce='<?php echo wp_create_nonce( 'woocommerce-pay' ); ?>'" />
	<input type="hidden" name="woocommerce_pay" value="1" ng-init="checkoutFields.woocommerce_pay=1" />
	<nav class="checkout__nav checkout__nav--submit checkout__nav--grow-md checkout__nav--grid">
		<input required type="checkbox" class="checkout__checkbox" ng-model="checkoutFields.terms" id="checkout_terms" /><label for="checkout_terms" class="checkout__terms"><span><?php _e('Dichiaro di aver letto e accetto i ', 'iro'); ?><a class="checkout__terms" href="<?php $term_cond_id = apply_filters('wpml_object_id', wc_get_page_id('terms'), 'page', true, ICL_LANGUAGE_CODE ); echo get_permalink($term_cond_id); ?>" target="_blank"><?php echo lcfirst(get_the_title($term_cond_id)); ?></a></span></label>
		<button class="checkout__button" ng-class="{'checkout__button--loading':isOrdering}" ng-disabled="checkout.$invalid"><?php _e('Acquista', 'iro'); ?></button>
	</nav>
</form>
</div>