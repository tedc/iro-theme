<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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

<?php
/*TP implementation*/
$products = $order->get_items();
$current_user = wp_get_current_user();
$TPtpi['chiaveMerchant'] = 'bGNaM014SDZrYitJMWVKSm1JNUdxV0J3Mjg5N3cwa2Q1';
if($current_user->user_email != ""){
	$TPtpi['email'] = $current_user->user_email;
}elseif($order->get_billing_email()!=""){
	$TPtpi['email'] = $order->get_billing_email();
}else{
	$TPtpi['email'] = $order->billing_email;
}
$TPtpi['orderid'] = $order->get_order_number();
$TPtpi['amount'] = number_format ( $order->get_total() , 2, '.','');
//Printing Script
//echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\"></script>";
echo "<script type=\"text/javascript\" src=\"https://tracking.trovaprezzi.it/javascripts/tracking.min.js\"></script>";
echo "<script type=\"text/javascript\">";
echo "window._tt = window._tt || [];";
echo "window._tt.push({ event: \"setAccount\", id: '". $TPtpi['chiaveMerchant'] ."' });";
echo "window._tt.push({ event: \"setOrderId\", order_id: '" . $TPtpi['orderid'] . "' });";
echo "window._tt.push({ event: \"setEmail\", email: '" . $TPtpi['email'] . "' });";
foreach( $order->get_items() as $item_id => $item ){
	$mixed = wc_get_order_item_meta( $item_id, '_product_id', true );
	$prodID = $mixed[0];
	$prodName = $products[$item_id]['name'];
	$prodName = str_replace("'", "", $prodName);
	echo "window._tt.push({ event: \"addItem\", sku: '" . $prodID . "', product_name: '" . $prodName . "' });";
}
echo "window._tt.push({ event: \"setAmount\", amount: '" . $TPtpi['amount'] . "' });";
echo "window._tt.push({ event: \"orderSubmit\"});";
echo "</script>";
foreach( $order->get_items() as $item_id => $item ){
	$product = $order->get_product_from_item( $item );
	var_dump($product->get_sku());
}
/*TP implementation END*/
?>

<div class="woocommerce-order woocommerce-order--shrink woocommerce-order--mw-large">

	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

			<ul class="overviews">

				<li class="overview overview--order order">
					<span><?php _e( 'Ordine', 'iro' ); ?></span>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>

				<li class="overview overview--date date">
					<span><?php _e( 'Data', 'iro' ); ?></span>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="overview overview--email email">
						<span><?php _e( 'Email', 'iro' ); ?></span>
						<strong><?php echo $order->get_billing_email(); ?></strong>
					</li>
				<?php endif; ?>

				<li class="overview overview--total total">
					<span><?php _e( 'Totale', 'iro' ); ?></span>
					<strong><?php echo $order->get_formatted_order_total(); ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="overview overview--payment-method method">
						<span><?php _e( 'Metodo di pagamento', 'iro' ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>
		<div class="woocommerce-instructions">
		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
		</div>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom woocommerce-notice woocommerce-notice--aligncenter woocommerce-notice--grow-md-bottom--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

	<?php endif; ?>

</div>
