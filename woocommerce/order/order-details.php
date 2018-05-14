<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
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
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! $order = wc_get_order( $order_id ) ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
}
?>
<section class="order-details order-details--gray order-details--grid">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
	<?php
if ( $show_customer_details ) {
	echo '<div class="order-details__cell order-details__cell--customer order-details__cell--s6">';
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
	echo '</div>';
} ?>
	<div class="order-details__cell order-details__cell--order<?php echo ($show_customer_details) ? ' order-details__cell--s6' : ''; ?>">
	<div class="order-details__table">

		
		<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

				wc_get_template( 'order/order-details-item.php', array(
					'order'			     => $order,
					'item_id'		     => $item_id,
					'item'			     => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'	     => $product ? $product->get_purchase_note() : '',
					'product'	         => $product,
				) );
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>

		
			<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<div class="order-details__row">
						<strong><?php echo str_replace(':', '', $total['label']); ?></strong>
						<strong class="order-details__price"><?php echo str_replace(array(' <small', '&nbsp;<small'), '<small', $total['value']); ?></strong>
					</div>
					<?php
				}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note', 'iro' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif;
				if(get_post_meta($order->get_id(), '_free_gift_total', $order->get_id())) : 
			 ?>
			 	<p class="order-details__gift"><strong><?php _e('Prodotti omaggio', 'iro'); ?></strong>
			 <?php		
					for($i = 0; $i < get_post_meta($order->get_id(), '_free_gift_total', true); $i++) {
			 ?>
			 <br/><a href="<?php echo get_permalink(get_post_meta($order->get_id(), '_free_gift_product_id_'.$i, true)); ?>" target="_blank"><?php echo get_the_title(get_post_meta($order->get_id(), '_free_gift_product_id_'.$i, true)); ?></a> x<?php echo get_post_meta( $order->get_id(), '_free_gift_product_qty_'.$i, true); ?>

			<?php } ?>
			</p>
		<?php endif; if(get_field('corriere', $order->get_id())) : ?>
		<div class="order-details__track">
								<p><strong><?php _e('Corriere', 'iro'); ?></strong>: <?php the_field('corriere', $order->get_id()); ?></p>	
								<?php if(get_field('tracking_code', $order->get_id())) : ?>
								<p><strong><?php _e('Codice di tracciamento', 'iro'); ?></strong>: <?php the_field('tracking_code', $order->get_id()); ?>
								<?php endif; ?></p>
								<?php if(get_field('tracking_url', $order->get_id())) : ?>
								<p><strong><?php _e('Link per il tracciamento', 'iro'); ?></strong>: <a href="<?php the_field('tracking_url', $order->get_id()); ?>">Url</a></p>
								<?php endif; ?>
								<?php if(get_field('tracking_date', $order->get_id())) : ?>
								<p><strong><?php _e('Data di spedizione', 'iro'); ?></strong>: <?php the_field('tracking_date', $order->get_id()); ?></p>
								<?php endif; ?>
		</div><?php endif; ?>
		
	</div>
	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
	</div>
</section>


