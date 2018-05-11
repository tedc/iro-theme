<?php
	if(!isset($_COOKIE['promo_completed'])) :
	acf_set_language_to_default();
	$materasso = get_field('materasso', 'options');
	$privacy = get_field('privacy_policy', 'options');
	acf_unset_language_to_default();
	if(is_front_page()) {
		$kind = 'home';
	} elseif(is_product($materasso) && $post->ID == $materasso) {
		$kind = 'materasso';
	} else {
		$kind = 'false';
	}
	$args = array(
		'post_type' => 'promo',
		'posts_per_page' => 1,
	);
	$promo = new WP_Query($args);
	if($promo->have_posts()) : ?>
<div class="promo" ng-click="isCountDown=true" ng-class="{'promo--hidden': isCountDown}">
	<?php while($promo->have_posts()) : $promo->the_post(); ?>
	<div class="promo__pre"><?php the_title(); ?></div>
	<div class="promo__open"></div>
</div>
	<?php // if(is_user_logged_in() && current_user_can('manage_options') && !isset($_COOKIE['_promo_'.get_the_ID()])) : ?>
<div class="popup popup--promo" ng-class="{'popup--visible':isCountDown}">
	<div class="popup__container swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="popup__wrapper popup__wrapper--shrink swiper-wrapper">
			<div class="popup__promo popup__promo--shrink popup__promo--grid swiper-slide">
				<div class="popup__close" ng-click="isCountDown=false"><?php _e('Chiudi', 'iro'); ?> <i class="icon-chiudi"></i></div>
				<div class="popup__figure popup__figure--shrink-right-half popup__figure--cell-s7">
					<?php the_post_thumbnail('full'); ?>
				</div>
				<div class="popup__content popup__content--grow-lg popup__content--shrink-left-half popup__content--grid popup__content--cell-s5">
					<h3 class="popup__title popup__title--medium"><?php the_field('popup_title'); ?></h3>
					<div class="popup__countdown popup__countdown--grid" ng-countdown="<?php $date = get_field('popup_date', false, false);$date = new DateTime($date);echo $date->format('Y-m-d'); ?>T<?php echo $date->format('H:i:s'); ?>" cookie-name="_promo_<?php the_ID(); ?>" page-kind="<?php echo $kind; ?>">
						<div class="popup__time">
							<strong>{{d}}</strong><br />
							<span><?php _e('Giorni', 'iro'); ?></span>
						</div>
						<div class="popup__time">
							<strong>{{h}}</strong><br />
							<span><?php _e('Ore', 'iro'); ?></span>
						</div>
						<div class="popup__time">
							<strong>{{m}}</strong><br />
							<span><?php _e('Minuti', 'iro'); ?></span>
						</div>
						<div class="popup__time">
							<strong>{{s}}</strong><br />
							<span><?php _e('Secondi', 'iro'); ?></span>
						</div>
					</div>	
					<?php if(get_field('popup_text')): ?>
					<div class="popup__text popup__text--grow-top"><?php the_field('popup_text'); ?></div>
					<?php endif; ?>
					<?php if(get_field('popup_link')): ?>
					<div class="popup__link popup__link--grow-md-top"><a class="popup__button" hre="<?php the_field('popup_link'); ?>"><?php _e('Acquista IRO', 'iro'); ?></a></div>
					<?php endif; ?>
					<form class="popup__agile popup__agile--grow-md-top" name="agileForm" id="agile-form" action="https://dreamiro.agilecrm.com/formsubmit" method="GET">
					<div style="display: none; height: 0px; width: 0px;">
					<input type="hidden" id="_agile_form_name" name="_agile_form_name" value="Mail">
					<input type="hidden" id="_agile_domain" name="_agile_domain" value="dreamiro">
					<input type="hidden" id="_agile_api" name="_agile_api" value="3ojuin62rel99ttpgfj4ul2dkl">
					<input type="hidden" id="_agile_redirect_url" name="_agile_redirect_url" value="<?php the_field('redirect_link'); ?>?promo_id=<?php the_ID(); ?>">
					<input type="hidden" id="_agile_document_url" name="_agile_document_url" value="">
					<input type="hidden" id="_agile_confirmation_msg" name="_agile_confirmation_msg" value="">
					<input type="hidden" id="_agile_form_id_tags" name="tags" value="Sconto">
					<input type="hidden" id="_agile_form_id" name="_agile_form_id" value="5692462144159744">
					</div>
					<!-- Text input-->
					<div class="popup__agilerow popup__agilerow--mw">
					 <input maxlength="250" id="agilefield-1" name="email" ng-model="promo_email" type="email" placeholder="Inserisci la tua email" class="popup__input" required="">
					<!--recaptcha aglignment-->
					<!-- Button -->
					<input type="checkbox" class="popup__checkbox" name="multiple_checkboxes_1526030399334-0" id="multiple_checkboxes_1526030399334-0" value="Acconsento all'utilizzo dei dati inseriti secondo le finalità indicate dalla privacy policy" ng-model="multiple_checkboxes_1526030399334_0" required><label for="multiple_checkboxes_1526030399334-0"><span>Acconsento all'utilizzo dei dati inseriti secondo le finalità indicate dalla <a href="<?php echo $privacy; ?>" target="_blank">privacy policy</a></span></label>
					<input type="checkbox" class="popup__checkbox" ng-model="multiple_checkboxes_1526030359115_0" required name="multiple_checkboxes_1526030359115-0" id="multiple_checkboxes_1526030359115-0" value="Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl"><label for="multiple_checkboxes_1526030359115-0"><span>Acconsento all'utilizzo dei dati inseriti per l'invio di eventuali comunicazioni di marketing da parte di IRO Srl</span></label>
					   <button type="submit" class="popup__button" ng-disabled="agileForm.$invalid" onclick="window.dataLayer({'event':'coupon_request'})">Invia</button>
					   <br><span id="agile-error-msg"></span>
					</div>
					</form>
					<script type="text/javascript">
					(function(a){var b=a.onload,p=true;isCaptcha=false;if(p){a.onload="function"!=typeof b?function(){try{_agile_load_form_fields()}catch(a){}}:function(){b();try{_agile_load_form_fields()}catch(a){}}};var formLen=document.forms.length;for(i=0;i<formLen;i++){if(document.forms.item(i).getAttribute("id")== "agile-form"){a.document.forms.item(i).onsubmit=function(a){a.preventDefault();try{_agile_synch_form_v5(this)}catch(b){this.submit()}}}}})(window);
					</script>
				</div>
			</div>
		</div>
		<?php 
		echo '<div class="swiper-scrollbar"></div>';
	 ?>
	</div>
</div>
<?php //endif; ?>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
<?php endif; endif;?>