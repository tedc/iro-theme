<?php
	acf_set_language_to_default();
	$materasso = get_field('materasso', 'options');
	acf_unset_language_to_default();
	if(is_front_page()) {
		$kind = 'home';
	} elseif(is_product($materasso)) {
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
<div class="promo">
	<?php while($promo->have_posts()) : $promo->the_post(); ?>
	<div class="promo__pre"><span><?php the_title(); ?></span></div>
	<div class="promo__open"></div>
</div>
	<?php if(is_user_logged_in() && current_user_can('manage_options') && !isset($_COOKIE['_promo_'.get_the_ID()])) : ?>
<div class="popup popup--promo" ng-class="{'popup--visible':isCountDown}">
	<div class="popup__container swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="popup__wrapper popup__wrapper--shrink swiper-wrapper">
			<div class="popup__promo popup__promo--shrink popup__promo--grid swiper-slide">
				<div class="popup__close" ng-click="isCountDown=false"><?php _e('Chiudi', 'iro'); ?> <i class="icon-chiudi"></i></div>
				<div class="popup__figure popup__figure--shrink-right-half popup__figure--cell-s5">
					<?php the_post_thumbnail('full'); ?>
				</div>
				<div class="popup__content popup__content--grow-lg popup__content--shrink-left-half popup__content--grid popup__content--cell-s7">
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
			</div>
		</div>
		<?php 
		echo '<div class="swiper-scrollbar"></div>';
	 ?>
	</div>
</div>
<?php endif; ?>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
<?php endif; ?>