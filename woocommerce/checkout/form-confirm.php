<div  id="confirm" class="checkout__confirm checkout__confirm--grid slide-toggle" ng-class="{'slide-toggle--visible' : isConfirm}">
	<header class="checkout__cell checkout__cell--grow-md<?php if(is_user_logged_in()) { echo '-bottom'; } ?> checkout__cell--s12">
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
	<input type="hidden" ng-model="checkoutFields.wpnonce" ng-init="checkoutFields.wpnonce='<?php echo wp_create_nonce( 'woocommerce-process_checkout' ); ?>'" />
</div>
<nav class="checkout__nav checkout__nav--submit checkout__nav--grow-md checkout__nav--grid" ng-if="isConfirm">
	<div class="checkout__accept">
	<input required type="checkbox" class="checkout__checkbox" ng-model="checkoutFields.terms" id="checkout_terms" /><label for="checkout_terms" class="checkout__terms"><span><?php _e('Dichiaro di aver letto e accetto i ', 'iro'); ?><a class="checkout__terms" href="<?php $term_cond_id = apply_filters('wpml_object_id', wc_get_page_id('terms'), 'page', true, ICL_LANGUAGE_CODE ); echo get_permalink($term_cond_id); ?>" target="_blank"><?php echo lcfirst(get_the_title($term_cond_id)); ?></a></span></label>
	<input type="checkbox" class="checkout__checkbox" ng-model="checkoutFields.privacy_input" id="checkout_privacy_input" value="true" required><label for="checkout_privacy_input" class="checkout__terms"><span><?php _e('Acconsento all\'utilizzo dei dati inseriti secondo le finalità indicate dalla', 'iro'); ?> <a href="<?php echo $privacy; ?>" target="_blank">privacy policy</a></span></label>
			<input type="checkbox" class="checkout__checkbox" ng-model="checkoutFields.marketing_input" id="checkout_marketing_input" required value="true"><label for="checkout_marketing_input" class="checkout__terms"><span><?php _e("Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro'); ?></span></label>
	</div>
	<button class="checkout__button" ng-class="{'checkout__button--loading':isOrdering}" ng-disabled="checkout.$invalid"><?php _e('Acquista', 'iro'); ?></button>
</nav>