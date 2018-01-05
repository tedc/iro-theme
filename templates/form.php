<div id="contact-form" class="contact__module contact__module--shrink-left-only" ng-form>
	<header class="contact__header contact__header--grow-md-bottom contact__header--grid">
		<h2 class="contact__subtitle">
			<?php _e('Modulo di contatto', 'iro'); ?>
		</h2>
		<span class="contact__required"><?php _e('Tutti i campi sono obbligatori', 'iro'); ?></span>
		
	</header>
	<form class="contact__form" name="contactForm" ng-submit="submit(contactForm.$valid)">
		<p>
			<label class="contact__label"><?php _e('Nome e cognome', 'iro'); ?></label>
			<input class="contact__input" type="text" required ng-model="formData.sender" name="sender" ng-attr-placeholder="{{((contactForm.sender.$invalid && contactForm.sender.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
		</p>
		<p>
			<label class="contact__label"><?php _e('Indirizzo email', 'iro'); ?></label>
			<input class="contact__input" type="email" required ng-model="formData.email" name="email" ng-attr-placeholder="{{((contactForm.email.$invalid && contactForm.email.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
		</p>
		<p>
			<label class="contact__label"><?php _e('Numero di telefono', 'iro'); ?></label>
			<input class="contact__input" type="tel" required ng-model="formData.tel" name="tel" ng-attr-placeholder="{{((contactForm.tel.$invalid && contactForm.tel.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
		</p>
		<p>
			<label class="contact__label"><?php _e('Messaggio', 'iro'); ?></label>
			<textarea class="contact__textarea" name="message" ng-model="formData.message" required ng-attr-placeholder="{{((contactForm.message.$invalid && contactForm.message.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}"></textarea>
		</p>
		<footer class="contact__footer contact__footer--grid-nowrap">
			<input type="hidden" name="_send_to" ng-model="formData._send_to" ng-init="formData._send_to='<?php the_field('indirizzo_email'); ?>'">
			<input type="hidden" name="_bcc" ng-model="formData._bcc" ng-init="formData._bcc='<?php echo preg_replace('/\s+/', '', get_field('bcc')); ?>'">
			<input type="hidden" name="_iro_nonce" ng-model="formData._iro_form_nonce" ng-init="formData._iro_form_nonce='<?php echo wp_create_nonce('iro-contact-form'); ?>'">
			<span class="contact__privacy"><?php _e('Inviando questo form acconsento al trattamento dei dati personali ai sensi del D. Lgs. 196/03.', 'catellani'); ?></span>
			<button type="submit" ng-disabled="contactForm.$invalid" class="contact__button contact__button--dark"><?php _e('Invia', 'iro'); ?></button>
		</footer>
	</form>
	<div class="contact__alert" ng-class="{'contact__alert--visible':isSubmitted}">
		<div class="contact__message contact__message--shrink"  ng-class="{'contact__message--visible':isContactSent}" ng-bind-html="alert">
		</div>
	</div>
</div>