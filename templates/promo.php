<?php
	$args = array(
		'post_type' => 'promo',
		'posts_per_page' => 1,
	);
	$promo = new WP_Query($args);
	if($promo->have_posts()) : ?>
<div class="promo" ng-promo>
	<div class="promo__wrapper promo__wrapper--grid-nowrap">
	<?php while($promo->have_posts()) : $promo->the_post(); ?>
	<div class="promo__pre promo__pre--shrink-fw-left"><span><?php the_title(); ?></span></div>
	<div class="promo__content promo__content--shrink-fw-right">
		<?php 
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			$content = strip_tags($content, '<span>');
			echo '<span>'.$content.'</span>';
		 ?>
	</div>
	<?php if(is_user_logged_in() && current_user_can('manage_options')) : ?>
<div class="popup popup--promo" ng-class="{'popup--visible':isCountDown}">
	<div class="popup__container swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto', mousewheel: true, direction:'vertical', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true} }">
		<div class="popup__wrapper swiper-wrapper">
			<div class="popup__promo popup__promo--grid swiper-slide">
				<div class="popup__close" ng-click="isCountDown=false"><?php _e('Chiudi', 'iro'); ?> <i class="icon-chiudi"></i></div>
				<div class="popup__figure popup__figure--cell-s5">
					<?php the_post_thumbnail('full'); ?>
				</div>
				<div class="popup__content popup__content--shrink popup__content--grid popup__content--cell-s7">
					<h3 class="popup__title popup__title--medium"><?php the_field('popup_title'); ?></h3>
					<div class="popup__countdown popup__countdown--grid" ng-countdown="<?php $date = get_field('popup_date', false, false);$date = new DateTime($date);echo $date->format('Y-m-d'); ?>T<?php echo $date->format('H:i:s'); ?>">
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
					<div class="popup__link popup__link--grow-top"><a class="popup__button" hre="<?php the_field('popup_link'); ?>"><?php _e('Acquista IRO', 'iro'); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
</div>
</div>
<?php endif; ?>