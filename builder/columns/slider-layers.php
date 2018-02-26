<?php 
$section__content = 'section__content section__content--layers';
if(!$mw && !$centered) {
	$section__content .= ($col%2==0) ? ' section__content--shrink-fw-left' : ' section__content--shrink-fw-right';
}
if(have_rows('testi_layers')) :
	
$strati = 'section';
$folder_base = '';
?>
<div class="section__slider" ng-layers="slider">
<?php include(locate_template( 'builder/commons/strati.php', false, true )); ?>	
<div class="<?php echo $section__content; ?>  section__content--cell-s8 swiper-container" scroller="layers" options="{effect: 'fade', fadeEffect : {crossFade : true}, init: false, navigation: {nextEl : '.icon-arrow-right', prevEl : '.icon-arrow-left'}, loop: true, slidesPerView : 'auto'}">
	<i class="icon-arrow-left"></i>
	<div class="section__wrapper swiper-wrapper">
		<?php while(have_rows('testi_layers')) : the_row(); ?>
		<div class="section__item swiper-slide" data-layer-to="<?php the_sub_field('layers'); ?>">
			<h2 class="section__title section__title--small"><?php the_sub_field('title'); ?></h2>
			<div class="section__text">
				<?php the_sub_field('testo'); ?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
	<i class="icon-arrow-right"></i>
</div>
</div>
<?php endif; ?>