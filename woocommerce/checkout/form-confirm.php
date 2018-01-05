<div id="confirm" class="checkout__slide checkout__slide--grow-md swiper-slide">
	<div class="checkout__confirm checkout__confirm--grid">
		<header class="checkout__cell checkout__cell--grow-md-bottom checkout__cell--s12">
			<h2 class="checkout__subtitle"><?php _e('Conferma ordine', 'iro'); ?></h2>
		</header><!-- /header -->
		<div class="checkout__cell checkout__cell--shrink-right-only checkout__cell--s6">
			<div class="checkout__info checkout__info--grow-md-bottom" ng-if="checkShippingAddress">
				<p>
					<strong><?php _e('Indirizzo di spedizione', 'iro'); ?><br/></strong>
					{{checkoutFields.customer.shipping_first_name}} {{checkoutFields.customer.shipping_last_name}}<br/>
					{{checkoutFields.customer.shipping_address_1}}<br/>
					{{checkoutFields.customer.shipping_postcode}} {{checkoutFields.customer.shipping_city}} ({{checkoutFields.customer.shipping_state}})
				</p>
			</div>
			<div class="checkout__info checkout__info--grow-md-bottom">
				<p>
					<strong ng-if="checkShippingAddress"><?php _e('Indirizzo di fatturazione', 'iro'); ?><br/></strong>
					<strong ng-if="!checkShippingAddress"><?php _e('Indirizzo di spedizione e fatturazione', 'iro'); ?><br/></strong>
					{{checkoutFields.customer.billing_first_name}} {{checkoutFields.customer.billing_last_name}}<br/>
					{{checkoutFields.customer.billing_address_1}}<br/>
					{{checkoutFields.customer.billing_postcode}} {{checkoutFields.customer.billing_city}} ({{checkoutFields.customer.billing_state}})
				</p>
			</div>
			<div class="checkout__info">
				<p>
					<strong><?php _e('Pagamento', 'iro'); ?></strong><br/>
					{{checkoutFields.payment_label}}
				</p>
			</div>
		</div>
		<div class="checkout__cell checkout__cell--shrink-left-only checkout__cell--s6">
			<ngcart-cart template-url="order-review.html"></ngcart-cart>
		</div>
	</div>
</div>
<input type="hidden" ng-model="checkoutFields.wpnonce" ng-init="checkoutFields.wpnonce='<?php echo wp_create_nonce( 'woocommerce-process_checkout' ); ?>'" />