<form class="newsletter newsletter--aligncenter">
	<fieldset>
		<legend class="newsletter__title newsletter__title--medium"><?php (get_sub_field('newsletter_title')) ? get_sub_field('newsletter_title') : __('Iscriviti alla newsletter di Iro', 'iro'); ?></legend>
		<input class="newsletter__input" type="email" ng-model="newsletter_email" placeholder="<?php _e('Il tuo indirizzo email', 'iro'); ?>">
		<button type="submit" class="newsletter__button"><?php _e('Invia', 'iro'); ?></button>
	</fieldset>
</form>