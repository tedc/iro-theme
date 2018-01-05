<div class="place-order">
	<noscript>
		<?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
		<br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>" />
	</noscript>

	<?php wc_get_template( 'checkout/terms.php' ); ?>

	<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

	<?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

	<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

	<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
</div>