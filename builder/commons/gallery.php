<?php
	if($images):
?>
<div class="slider swiper-container" scroller options="{'pagination' : {'el' : '.swiper-pagination', 'clickable': true}, 'navigation': {'nextEl': '.swiper-button-next','prevEl': '.swiper-button-prev'}, 'loop' : true, 'effect': 'fade', 'fadeEffect': {'crossFade':true}}">
	<div class="slider__wrapper swiper-wrapper">
		<?php foreach($images as $img) : ?>
		<figure class="slider__slide swiper-slide" lazy-load-img="<?php echo $img['url']; ?>" style="background-image: url(<?php echo wp_get_attachment_image_src( $img['ID'], 'full' )[0]; ?>)">
			<?php echo wp_get_attachment_image( $img['ID'], 'full', null, array('class' => 'slider__image', 'data-object-fit' => true) ); ?>
		</figure>
		<?php endforeach; ?>
	</div>
	<nav class="slider__nav slider__nav--shrink-fw slider__nav--grow-md slider__nav--grid">
		<span class="icon-arrow-left swiper-button-prev"></span>
		<div class="slider__pagination swiper-pagination"></div>
		<span class="icon-arrow-right swiper-button-next"></span>
	</nav>
</div>
<?php endif; ?>