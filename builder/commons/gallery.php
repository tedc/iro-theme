<?php
	if($images):
?>
<div class="slider swiper-container" scroller ng-init='slider = <?php echo htmlspecialchars( wp_json_encode( $images ) ) ?>' options="{'pagination' : {'el' : '.swiper-pagination', 'clickable': true}, 'navigation': {'nextEl': '.swiper-button-next','prevEl': '.swiper-button-prev'}, 'loop' : true, 'effect': 'fade', 'fadeEffect': {'crossFade':true}}">
	<div class="slider__wrapper swiper-wrapper">
		<?php foreach($images as $img) : ?>
		<figure class="slider__slide swiper-slide" lazy-load-img="<?php echo $img['url']; ?>">
			<?php echo wp_get_attachment_image( $img['ID'], 'fulla', null, array('class' => 'slider__image', 'data-object-fit') ); ?>
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