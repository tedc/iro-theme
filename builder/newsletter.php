<form class="newsletter newsletter--aligncenter" name="newsletter" ng-submit="subscribe(newsletter)">
	<fieldset class="newsletter__fieldset newsletter__fieldset--mw-large">
		<legend class="newsletter__title newsletter__title--medium"><?php echo (get_sub_field('newsletter_title')) ? get_sub_field('newsletter_title') : __('Iscriviti alla newsletter di Iro', 'iro'); ?></legend>
		<div class="newsletter__row">
			<input class="newsletter__input" required type="email" ng-model="nlFields.email" placeholder="<?php _e('Il tuo indirizzo email', 'iro'); ?>">
			<button type="submit" class="newsletter__button"><?php _e('Invia', 'iro'); ?></button>
		</div>
	</fieldset>
</form>