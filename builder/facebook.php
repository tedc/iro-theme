<div class="facebook facebook--grow-lg-top facebook--grid" ng-facebook>
	<header class="facebook__cover facebook__cover--cell-s12">	
		<figure class="facebook__figure" style="background-image: url(<?php echo wp_get_attachment_image_src( get_field('fb_cover', 'options'), 'full', false )[0]; ?>)">
			<a ng-attr-href="{{fb.info.link}}" target="_blank">
				<?php echo wp_get_attachment_image( get_field('fb_cover', 'options'), 'full', false, array('data-object-fit' => true) ); ?>
			</a>
		</figure>
	</header>
	<div class="facebook__content facebook__content--grow-md facebook__content--cell-s6">
		<div class="facebook__header facebook__header--shrink">
			<h2 class="facebook__title facebook__title--medium"><?php _e('Iro su Facebook', 'iro'); ?></h2>
		</div>
		<div class="facebook__container facebook__container--shrink" scroller="facebook" options="{freeMode : true, direction: 'vertical', mousewheel : true, 'slidesPerView':'auto', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true}}">
			<div class="facebook__wrapper swiper-wrapper">
				<div class="facebook__item swiper-slide" ng-repeat="post in fb.feed" on-finish-render="updateSwiper">
					<time class="facebook__time" ng-bind-html="post.created_time | date:'dd MMMM yyyy'"></time>
					<div class="facebook__message" ng-bind-html="convertHtml(post.message)"></div>
				</div>
			</div>
			<div class="facebook__scrollbar swiper-scrollbar"></div>
		</div>
	</div>
	<footer class="facebook__footer facebook__footer--grow-md facebook__footer--cell-s6">
		<a ng-attr-href="{{fb.info.link}}" target="_blank" class="facebook__button facebook__button--dark">
			<?php _e('Seguici su Facebook', 'iro'); ?>
		</a>
	</footer>
</div>