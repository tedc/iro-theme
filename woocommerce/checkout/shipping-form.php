<div class="checkout__slide checkout__slide--grow-md swiper-slide">

<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

	<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
		<?php wc_get_template_part('cart/cart', 'shipping' );

		//wc_cart_totals_shipping_html() ?>
		<input type="hidden" name="security" ng-model="checkoutFields.shipping_security" ng-init="checkoutFields.shipping_security='<?php echo wp_create_nonce('update-shipping-method'); ?>'" />
	<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

<?php endif; ?>
</div>
	