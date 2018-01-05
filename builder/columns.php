<div class="section section--grid">
<?php 
$col = 0;
while(have_rows('colonne')) : the_row();
while(have_rows('content')) : the_row();
	$centered = get_sub_field('centered_columns');
	$size = ($centered) ? '_centered' : '';
	$size = get_sub_field('size' . $size);
	$sizeClass = '';
	$alignClass = get_sub_field('align') ? ' section__cell--align-'.get_sub_field('align') : '';
	$alignClass .= ($centered) ? ' section__cell--centered section__cell--aligncenter' : '';
	if(!$centered){
		if($size !== 0) {
			$sizeClass = ' section__cell--s'.$size;
		}
	} else {
		$sizeClass = ' section__cell-'.$size;
	}
	
	$paddingClass = '';
	if(get_sub_field('padding')) {
		$modifier = '';
		if(get_sub_field('padding_mod')) {
			$padding_modifier = (count(get_sub_field('padding_mod')) > 1) ? '' : implode('', get_sub_field('padding_mod'));
		}
		$paddingClass = ' section__cell'.get_sub_field('padding') . $padding_modifier;
	}
	$alignClass .= (get_sub_field('column_right')) ? ' section__cell--right section__cell--aligncenter' : '';
?>
	
	<?php 
	while(have_rows('column')) : the_row(); ?>
		<div id="col_<?php echo $col; ?>_<?php echo $row; ?>" class="section__cell section__cell--<?php echo get_row_layout(); ?> section__cell--<?php echo ($col%2==0) ? 'odd' : 'even'; echo $sizeClass . $alignClass . $paddingClass; ?>">
		<?php include( locate_template( 'builder/columns/'.get_row_layout().'.php', false, true ) ); ?>
		</div>
	<?php endwhile;
	
endwhile;
$col++; endwhile; ?>
</div>