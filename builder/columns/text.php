<?php 
$section__content = 'section__content';
if(!$mw && !$centered) {
	$section__content .= ($col%2==0) ? ' section__content--shrink-fw-left' : ' section__content--shrink-fw-right';
}
?>
<div class="<?php echo $section__content; ?>">
	<?php
	$contentClass = 'section__text';
	while(have_rows('title')) : the_row();
	if(get_sub_field('title_text')) : 
		if(get_sub_field('title_label')) :
	?>
	<span class="section__label section__label--grow-bottom"><?php the_sub_field('title_label'); ?></span>
	<?php endif; 
		if(have_rows('text_icons')) :

	?>
	<div class="section__symbols section__symbols--grow-bottom">
		<?php while(have_rows('text_icons')) : the_row();
		$dark = (get_sub_field('dark_icon')) ? ' section__symbol--blu' : '';
		echo '<span class="section__symbol'.$dark.'">';
			if(!get_sub_field('svg')) :
		?>
		<i class="icon-<?php the_sub_field('icona'); ?>"></i>
		<?php else :
			echo print_svg(get_sub_field('file_svg'));
		 endif;
		 echo '</span>';
		 endwhile; ?>
	</div>
	<?php endif; ?>
	<h2 class="section__title section__title--<?php the_sub_field('title_size'); echo get_sub_field('titolo_bianco') ? ' section__title'.get_sub_field('titolo_bianco') : ''; ?>"><?php the_sub_field('title_text'); ?></h2>
	<?php 
		$contentClass .= (get_sub_field('title_size') == 'medium') ? ' section__text--grow-top' : ' section__text--grow-md-top';
		endif; 
	endwhile;
		if(get_sub_field('text')) : 
		echo '<div class="'.$contentClass.'">';
		the_sub_field('text');
		echo '</div>';
		if(get_sub_field('col_link')) {
			echo '<div class="section__link section__link--grow-top"><a href="'.get_sub_field('col_link').'">'.get_sub_field('link_text').'</a></div>';
		}
		endif;
	?>
</div>