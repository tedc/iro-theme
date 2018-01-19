<?php $section__figure = 'section__figure';
$is_not_full = false;
if(!$mw && !$centered && !get_sub_field('full_image')) {
	$section__figure .= ($col%2==0) ? ' section__figure--shrink-fw-left' : ' section__figure--shrink-fw-right';
	$is_not_full = true;
} ?>
<figure class="<?php echo $section__figure; ?>">
	<?php 
	if(is_mobile()) {
		$thumb = 'medium';
	} elseif(is_tablet()) {
		$thumb = 'large';
	} else {
		$thumb = 'full';
	}
	$w = wp_get_attachment_image_src( get_sub_field('immagine')['ID'], 'full' )[1];
	$w = (get_sub_field('col_image_mw')) ? $w * 0.5 : $w;
	$mv = (get_sub_field('move_top_image')) ? 'top:' .(get_sub_field('move_top_image')/16) . 'em' : null;
	?>
	<span class="section__image" style="max-width: <?php echo ($w/15); ?>em">
	<?php echo wp_get_attachment_image( get_sub_field('immagine')['ID'], $thumb, false, array('class'=>'section__thumb', 'style' => $mv) ); ?>
	</span>
</figure>