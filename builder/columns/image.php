<?php $section__figure = 'section__figure';
if(!$mw && !$centered && !get_sub_field('full_image')) {
	$section__figure .= ($col%2==0) ? ' section__figure--shrink-fw-left' : ' section__figure--shrink-fw-right';
} ?>
<figure class="<?php echo $section__figure; ?>">
	<span class="section__image">
	<?php 
	if(is_mobile()) {
		$thumb = 'medium';
	} elseif(is_tablet()) {
		$thumb = 'large';
	} else {
		$thumb = 'full';
	}
	echo wp_get_attachment_image( get_sub_field('immagine')['ID'], $thumb, false, array('class'=>'section__thumb') ); ?>
	</span>
</figure>