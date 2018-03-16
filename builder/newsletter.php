<div class="newsletter newsletter--aligncenter"  ng-newsletter>
	<form name="newsletter" ng-submit="subscribe(newsletter)" novalidate>
		<fieldset class="newsletter__fieldset newsletter__fieldset--mw-large">
			<legend class="newsletter__title newsletter__title--medium"><?php echo (get_sub_field('newsletter_title')) ? get_sub_field('newsletter_title') : __('Iscriviti alla newsletter di Iro', 'iro'); ?></legend>
			<div class="newsletter__row">
				<input class="newsletter__input" type="email" required type="email" ng-model="nlFields.email" placeholder="<?php _e('Il tuo indirizzo email (campo obbligatorio)', 'iro'); ?>">
				<input type="hidden" ng-model="nlFields._newsletter_nonce" ng-init="nlFields._newsletter_nonce='<?php echo wp_create_nonce( 'iro-newsletter' ); ?>'" />
				<button type="submit" ng-disabled="newsletter.$invalid" nclass="newsletter__button"><?php _e('Invia', 'iro'); ?></button>
			</div>
			<div class="newlsetter__message" ng-clas="{'newlsetter__message' : isNlError}" ng-if="nlMessage">
				<p ng-bind-html="nlMessage"></p>
			</div>
		</fieldset>
	</form>
</div>