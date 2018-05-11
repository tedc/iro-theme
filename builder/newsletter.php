<?php acf_set_language_to_default();
	$privacy = get_field('privacy_policy', 'options');
	acf_unset_language_to_default(); ?>
<div class="newsletter newsletter--aligncenter"  ng-newsletter>
	<form name="newsletter" ng-submit="subscribe(newsletter)" novalidate>
		<fieldset class="newsletter__fieldset newsletter__fieldset--mw-large">
			<legend class="newsletter__title newsletter__title--medium"><?php echo (get_sub_field('newsletter_title')) ? get_sub_field('newsletter_title') : __('Iscriviti alla newsletter di IRO', 'iro'); ?></legend>
			<div class="newsletter__row">
				<input class="newsletter__input" type="email" required type="email" ng-model="nlFields.email" placeholder="<?php _e('Il tuo indirizzo email', 'iro'); ?>">
				<input type="hidden" ng-model="nlFields._newsletter_nonce" ng-init="nlFields._newsletter_nonce='<?php echo wp_create_nonce( 'iro-newsletter' ); ?>'" />
				<button type="submit" ng-class="{'newsletter__button--loading':isSubscribing}" ng-disabled="newsletter.$invalid" class="newsletter__button"><?php _e('Invia', 'iro'); ?></button>
			</div>
			<input type="checkbox" class="newsletter__checkbox" ng-model="nlFields.privacy_input" id="privacy_input" value="true" required><label for="privacy_input"><span><?php _e('Acconsento all\'utilizzo dei dati inseriti secondo le finalità indicate dalla', 'iro'); ?> <a href="<?php echo $privacy; ?>" target="_blank">privacy policy</a></span></label>
			<input type="checkbox" class="newsletter__checkbox" ng-model="nlFields.marketing_input" required value="true" id="marketing_input"><label for="marketing_input"><span><?php _e("Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl", 'iro'); ?></span></label>
			<div class="newsletter__message" ng-clas="{'newsletter__message--error' : isNlError}" ng-if="nlMessage">
				<p ng-bind-html="nlMessage"></p>
			</div>
		</fieldset>
	</form>
</div>