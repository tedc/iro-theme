<?php 
$section__content = 'section__content';
if(!$mw && !$centered) {
	$section__content .= ($col%2==0) ? ' section__content--shrink-fw-left' : ' section__content--shrink-fw-right';
}
if(have_rows('testi')) : ?>
<div class="<?php echo $section__content; ?> swiper-container" scroller options="{effect: 'fade', fadeEffect : {crossFade : true}, pagination : {el : '.swiper-pagination', clickable : true}, navigation: {nextEl : '.icon-arrow-right', prevEl : '.icon-arrow-left'}, autoHeight : true, loop: true}">
	<div class="section__wrapper swiper-wrapper">
		<?php while(have_rows('testi')) : the_row(); ?>
		<div class="section__item swiper-slide">
			<?php if(get_sub_field('title_label')) :
			?>
			<span class="section__label section__label--grow-bottom"><?php the_sub_field('title_label'); ?></span>
			<?php endif; ?>
			<h2 class="section__title section__title--medium"><?php the_sub_field('title'); ?></h2>
			<div class="section__text section__text--grow-top">
				<?php the_sub_field('testo'); ?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
	<div class="section__pagination section__pagination--grow-md-top">
		<i class="icon-arrow-left"></i>
		<div class="swiper-pagination"></div>
		<i class="icon-arrow-right"></i>
	</div>
</div>
<?php endif; ?>