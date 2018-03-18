<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
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
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<table class="orders">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
				?>
					<th class="order__th order__th--<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column_name ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_orders->orders as $customer_order ) :
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count();
				?>
				<tr class="order--<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="order__td order__td--<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								//printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								printf( _n( '%1$s', '%1$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );
								
								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										if($key == 'invoice') {
											$base_path = str_replace(wc_get_page_permalink('myaccount') . '/', '', esc_url( $action['url'] ));
										echo '<a title="'.esc_html( $action['name'] ).'" href="' . esc_url( $action['url'] ) . '" class="icon-' . sanitize_html_class( $key ) . '"></a>';
										} elseif ($key == 'cancel') {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										} elseif($key == 'pay') {
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										} else {
											$base_path = str_replace(wc_get_page_permalink('myaccount') . '/', '', esc_url( $action['url'] ));
										echo '<a ui-sref="app.account({\'path\': \''.$base_path.'\'})" href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
										}
										
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="account__pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="account__button account__button--prev account__button-light account__button--slim" ui-sref="app.account({path : '<?php echo str_replace(wc_get_page_permalink('myaccount'), '', wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>'}" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="account__button account__button--next account__button-light account__button--slim" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>" ui-sref="app.account({path : '<?php echo str_replace(wc_get_page_permalink('myaccount'), '', wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>'}"><?php _e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--aligncenter woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<p><?php _e( 'No order has been made yet.', 'woocommerce' );
			acf_set_language_to_default();
			$main_product = get_field('main_product', 'options');
			acf_unset_language_to_default();
			$materasso = get_posts(array('post_type' => 'product', 'tax_query'=> array(array('taxonomy'=> 'prodotto_associato', 'field'=> 'term_id', 'terms' => array($main_product)))));
			$url = get_permalink($materasso[0]->ID);
		 ?></p>
		<a class="woocommerce-message__button"  href="<?php echo $url; ?>" ui-sref="app.page({slug : '<?php echo basename($url); ?>'}">
			<?php _e( 'Acquista Iro', 'iro' ) ?>
		</a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
