<?php 
	acf_set_language_to_default();
	$img = wp_get_attachment_image_src( get_field('instagram_image', 'options'), 'full', false );
	$img_src = $img[0];
	$img_mb = get_field('instagram_image_mb', 'options') ? ((get_field('instagram_image_mb', 'options') * 100) / $img[1])*-1 : 0;
	acf_unset_language_to_default();
?>

<div class="instagram" ng-instagram strings="{s : ['<?php _e('secondo', 'iro'); ?>', '<?php _e('secondi', 'iro'); ?>'], m : ['<?php _e('minuto', 'iro'); ?>', '<?php _e('minuti', 'iro'); ?>'], h :['<?php _e('ora', 'iro'); ?>', '<?php _e('ore', 'iro'); ?>'], ago: '<?php _e('fa', 'iro'); ?>'}">
	<header class="instagram__header instagram__header--shrink-fw-left instagram__header--grow" ng-if="items.length > 0">
		<h3 class="instagram__title">@{{username}}</h3><a class="instagram__button instagram__button--slim" ng-attr-href="https://instagram.com/{{username}}" target="_blank"><span><?php _e('Seguici su instagram', 'iro'); ?></span></a>
		<!-- <figure class="instagram__figure"<?php if($img_mb > 0) : ?> style="margin-bottom: <?php echo $img_mb; ?>%"<?php endif; ?>>
			<img src="<?php echo $img_src; ?>" class="instagram__image" alt="<?php _e('Segui Iro su Instagram', 'iro'); ?>" />
		</figure> -->
	</header>
	<div class="instagram__container" scroller="instagram" options="{freeMode : true, slidesPerView :'auto'}"  ng-if="items.length > 0">
		<ul class="instagram__wrapper swiper-wrapper">
			<li class="instagram__item instagram__item--cell-s3 swiper-slide" ng-repeat="i in items" on-finish-render="updateSwiper">
				<a ng-href="{{i.link}}" ng-attr-target="_blank">
					<img ng-src="{{i.images.standard_resolution.url}}" />
					<div class="instagram__content">
						<img ng-src="{{userpicture}}" />
						<span class="instagram__time" ng-bind-html="timeAgo(i.created_time)"></span>
					</div>
				</a>
			</li>
		</ul>
	</div>
</div>