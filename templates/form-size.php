<div class="popup popup--size" ng-class="{'popup--visible':isSizeForm}">
	<div class="popup__container swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="popup__wrapper popup__wrapper--shrink swiper-wrapper">
			<div id="size-form" class="popup__module swiper-slide" ng-form form-kind="size">
				<header class="popup__header popup__header--grow-md">
					<div class="popup__row popup__row--close">
						<div class="popup__image"></div>
						<div class="popup__close" ng-click="close()"><?php _e('Chiudi', 'iro'); ?><i class="icon-chiudi"></i></div>
					</div>
					<h2 class="popup__title popup__title--medium">
						<?php _e('Scegli le tue misure di IRO', 'iro'); ?>
					</h2>
					<span class="popup__required">*<?php _e('Campi sono obbligatori', 'iro'); ?></span>	
				</header>
				<form class="popup__form popup__form--grid" name="sizeForm" ng-submit="submit(sizeForm.$valid)">
					<div class="popup__row popup__row--cell-s6">
						<label class="popup__label"><?php _e('Nome*', 'iro'); ?></label>
						<input class="popup__input" type="text" required ng-model="formData.first_name" name="first_name" ng-attr-placeholder="{{((sizeForm.first_name.$invalid && sizeForm.first_name.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
					</div>
					<div class="popup__row popup__row--cell-s6">
						<label class="popup__label"><?php _e('Cognome*', 'iro'); ?></label>
						<input class="popup__input" type="text" required ng-model="formData.last_name" name="last_name" ng-attr-placeholder="{{((sizeForm.last_name.$invalid && sizeForm.last_name.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
					</div>
					<div class="popup__row popup__row--cell-s6">
						<label class="popup__label"><?php _e('Indirizzo email*', 'iro'); ?></label>
						<input class="popup__input" type="email" required ng-model="formData.email" name="email" ng-attr-placeholder="{{((sizeForm.email.$invalid && sizeForm.email.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
					</div>
					<div class="popup__row popup__row--cell-s6">
						<label class="popup__label"><?php _e('Numero di telefono*', 'iro'); ?></label>
						<input class="popup__input" type="tel" required ng-model="formData.tel" name="tel" ng-attr-placeholder="{{((sizeForm.tel.$invalid && sizeForm.tel.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}" />
					</div>
					<div class="popup__row popup__row--cell-s12">
						<label class="popup__label"><?php _e('Scegli le tue dimensioni', 'iro'); ?></label>
						<div class="popup__units popup__units--grid">
							<div class="popup__unit">
								<input class="popup__input" type="text" ng-model="formData.extent" required placeholder="<?php _e('Lunghezza (campo obbligatorio)', 'iro'); ?>"><span class="popup__per">x</span>
							</div>
							<div class="popup__unit">
								<input class="popup__input" type="text" ng-model="formData.width" required placeholder="<?php _e('Larghezza (campo obbligatorio)', 'iro'); ?>"><span class="popup__per">x</span>
							</div>
							<div class="popup__unit">
								<input class="popup__input" type="text" ng-model="formData.height" placeholder="<?php _e('Altezza', 'iro'); ?>">
							</div>
							<strong class="popup__per"><?php _e('cm', 'iro'); ?></strong>
						</div>
					</div>
					<div class="popup__row popup__row--cell-s12">
						<label class="popup__label"><?php _e('Note', 'iro'); ?></label>
						<textarea class="popup__textarea" name="message" ng-model="formData.message" ng-attr-placeholder="{{((sizeForm.message.$invalid && sizeForm.message.$touched) ? '<?php _e('Campo obbligatorio', 'iro'); ?>' : '')}}"></textarea>
					</div>
					<div style="visibility: hidden;height: 0;overflow: hidden;">
							<input type="text" name="website" id="website" ng-model="formData.website" />
							<input type="text" name="address" id="address" ng-model="formData.address" />
					</div>
					<footer class="popup__footer popup__footer--cell-s12">
						<input type="hidden" name="_send_to" ng-model="formData._send_to" ng-init="formData._send_to='<?php the_field('indirizzo_email'); ?>'">
						<input type="hidden" name="_bcc" ng-model="formData._bcc" ng-init="formData._bcc='<?php echo preg_replace('/\s+/', '', get_field('bcc')); ?>'">
						<span class="popup__privacy"><?php _e('Inviando questo form acconsento al trattamento dei dati personali ai sensi del D. Lgs. 196/03.', 'catellani'); ?></span><br/>
						<button type="submit" ng-disabled="sizeForm.$invalid" class="popup__button popup__button--dark"><?php _e('Invia', 'iro'); ?></button>
					</footer>
				</form>
				<div class="popup__alert" ng-class="{'popup__alert--visible':isSubmitted}">
					<div class="popup__message popup__message--shrink"  ng-class="{'popup__message--visible':isContactSent}" ng-bind-html="alert">
					</div>
				</div>
			</div>
			<?php 
		echo '<div class="swiper-scrollbar"></div>';
	 ?>
		</div>
		
	</div>
</div>