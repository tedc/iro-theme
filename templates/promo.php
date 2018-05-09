<?php
	acf_set_language_to_default();
	$materasso = get_field('materasso', 'options');
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
				<div class="popup__figure popup__figure--shrink-right-half popup__figure--cell-s5">
					<?php the_post_thumbnail('full'); ?>
				</div>
				<div class="popup__content popup__content--grow-md-bottom popup__content--grow-lg-top popup__content--shrink-left-half popup__content--grid popup__content--cell-s7">
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
				</div>
				<form class="popup__agile popup__agile--grow-md" id="agile-form" action="https://dreamiro.agilecrm.com/formsubmit" method="GET">
					<div style="display: none; height: 0px; width: 0px;">
					<input type="hidden" id="_agile_form_name" name="_agile_form_name" value="Mail">
					<input type="hidden" id="_agile_domain" name="_agile_domain" value="dreamiro">
					<input type="hidden" id="_agile_api" name="_agile_api" value="3ojuin62rel99ttpgfj4ul2dkl">
					<input type="hidden" id="_agile_redirect_url" name="_agile_redirect_url" value="https://www.dreamiro.it/materasso">
					<input type="hidden" id="_agile_document_url" name="_agile_document_url" value="">
					<input type="hidden" id="_agile_confirmation_msg" name="_agile_confirmation_msg" value="">
					<input type="hidden" id="_agile_form_id_tags" name="tags" value="Sconto">
					<input type="hidden" id="_agile_form_id" name="_agile_form_id" value="5692462144159744">
					</div>
					<!-- Text input-->
					<div class="popup__agilerow popup__agilerow--mw">
					 <label class="popup__label" for="agilefield-1">Inserisci mail<span class="agile-span-asterisk"> *</span></label>
					 <input maxlength="250" id="agilefield-1" name="email" type="email" placeholder="Inserisci la tua email" class="popup__input" required="">
					<!--recaptcha aglignment-->
					<!-- Button -->
					   <button type="submit" class="popup__button">Invia</button>
					   <br><span id="agile-error-msg"></span>
					</div>
					</form>
					<script type="text/javascript">
					(function(a){var b=a.onload,p=true;isCaptcha=false;if(p){a.onload="function"!=typeof b?function(){try{_agile_load_form_fields()}catch(a){}}:function(){b();try{_agile_load_form_fields()}catch(a){}}};var formLen=document.forms.length;for(i=0;i<formLen;i++){if(document.forms.item(i).getAttribute("id")== "agile-form"){a.document.forms.item(i).onsubmit=function(a){a.preventDefault();try{_agile_synch_form_v5(this)}catch(b){this.submit()}}}}})(window);
					</script>
			</div>
		</div>
		<?php 
		echo '<div class="swiper-scrollbar"></div>';
	 ?>
	</div>
</div>
<?php //endif; ?>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
<?php endif; ?>