<?php
	$args =(get_sub_field('press')) ? array('taxonomy'=>'press', 'include'=>get_sub_field('press'), 'hide_empty'=>0) : array('taxonomy'=>'press', 'hide_empty'=>0);
	$press = get_terms($args);
	if($press):
?>
<div class="press press--aligncenter">
	<span class="press__label"><span><?php _e('Parlano di noi', 'iro'); ?></span></span>
	<div class="press__container press__container--grow-top swiper-container" scroller options="{freeMode: true, slidesPerView: 'auto'}">
		<div class="press__wrapper  swiper-wrapper">
			<?php foreach ($press as $term) {
				$press_logo = wp_get_attachment_image_src( get_field('logo_testata', 'press_'.$term->term_id)['ID'], 'full', false );
				echo '<figure class="press__item press__item--shrink swiper-slide"><img src="'.$press_logo[0].'" style="max-width:'.(($press_logo[1] / 2)/16).'em" /></figure>';
			} ?>
		</div>
	</div>
</div>
<?php endif; ?>