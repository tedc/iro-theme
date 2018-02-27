<?php
	while(have_rows('rows')) : the_row();
		if(get_sub_field('is_divider')) :
			get_template_part( 'builder/divider' );
		elseif (get_sub_field('blog')) :
			get_template_part( 'builder/blog' );
		else :
		$padding = get_sub_field('padding');
		$padding_pos = get_sub_field('padding_mod');
		$p = '';
		$mw = get_sub_field('mw');
		$fw = ($mw) ? ' row--shrink-fw' : '';
		if($padding) {
			$modifier = '';
			if($padding_pos) {
				$modifier = (count($padding_pos) > 1) ? '' : implode('', $padding_pos);
			}
			$p = ' row'.$padding . $modifier;
		}
		$background = get_sub_field('background');
		$bgClass = '';
		$bg = '';
		$pattern_style = '';
		$patternClass = '';
		if(get_sub_field('custom_background')) {
			if($background['mobile_hide_background_image']) {
				$bgClass .= ' row--hide-bg';
			}
			if($background['mobile_dark_backgrond']) {
				$bgClass .= ' row--handheld-dark';
			}
			foreach ($background as $key => $value) {
				if($key == 'background_position') {
					$bg .= ($value) ? str_replace('_', '-', $key) . ':' . $value . ';' : '';
				}
				if($key == 'background_color') {
					$bgClass .= ($value) ? ' row'.$value : '';
					$bgClass .= ($background['inverted_gradient'] && preg_match('/gradient/', $background['background_color'])) ? ' row'.$value.'-inverted' : '';
				}
				if($key == 'background_size') {
					if($value != 1) {
						$bgClass .= ($value) ? ' row'.$value : '';
					}
				}
				if($key == 'background_size_custom') {
					if(!preg_match('/gradient/', $background['background_color'])) {
						if($background['background_size'] == 1) {
							$bg .= ($value) ? str_replace('_', '-', str_replace('_custom', '', $key)) . ':' . $value . ';' : '';
						}
					} else {
						if($background['background_size'] == 1) {
							$pattern_style .= ($value) ? str_replace('_', '-', str_replace('_custom', '', $key)) . ':' . $value . ';' : '';
						}
					}
				}
				if($key == 'background_repeat' || $key == 'background_image') {
					if(!preg_match('/gradient/', $background['background_color'])) {
						if($key == 'background_image') {
							$bg .= ($value) ? str_replace('_', '-', $key).':url(' . $value . ');': '';
						} else {
							$bg .= ($value) ? str_replace('_', '-', $key) . ':' . $value . ';' : '';
						}
					} else {
						if($key == 'background_image') {
							$pattern_style .= ($value) ? str_replace('_', '-', $key).':url(' . $value . ');': '';
						} else {
							$pattern_style .= ($value) ? str_replace('_', '-', $key) . ':' . $value . ';' : '';
							if(!$value) {
								if($background['background_size'] && $background['background_size'] != 1){
									$patternClass .= ' row__pattern'.$background['background_size'];
								} elseif($background['background_size'] && $background['background_size'] == 1) {
									$pattern_style .= 'background-size:'.$background['background_size_custom'].';';
								} else {
									$patternClass .= ' row__pattern--contain';
								}
							}
						}						
					}
				}
			}
		}
		$pattern = (!empty($pattern_style)) ? '<div class="row__pattern'.$patternClass.'" style="'.$pattern_style.'"></div>' : '';	
		$bgStyle = (!empty($bg)) ? ' style="'.$bg.'"' : '';
		$abs_image = get_sub_field('absolute_image');
		$relativeRow = ($abs_image['file'] && get_sub_field('floating_image') || $background['ondine'] || $background['clouds'] || $background['flowers']) ? ' row--relative' : '';
		$multiplyClass = ($abs_image['multiply']) ? ' row__figure--multiply' : '';
		$top = ($abs_image['file'] && get_sub_field('floating_image') && $abs_image['top'] != 0) ? ' move-top-image="'.$abs_image['top'].'"' : '';
		$multiplyClass .= ($abs_image['file'] && get_sub_field('floating_image') && $abs_image['z_index_0']) ? ' row__figure--lowlev' : '';
		$abs_image = ($abs_image['file'] && get_sub_field('floating_image')) ? '<figure class="row__figure row__figure'.$abs_image['posizione'].$multiplyClass.'"'.$top.' style="width:'.$abs_image['width'].'%;"><img src="'.$abs_image['file'].'" class="row__image"/></figure>' : '';
	
	?>
	<section class="row<?php echo $p . $fw . $bgClass . $relativeRow; echo (get_sub_field('white_text') ? ' row--inverted' : ''); ?>"<?php echo $bgStyle; ?><?php scrollmagic('triggerHook: 0.5, class : "row--inview"'); ?>>
		<?php
		 if($background['ondine']) : 
			if($background['ondine'] != 2) : ?>
		<div class="row__wave row__wave--top"></div>
		<?php endif; endif; 
		var_dump($background['clouds']);
		if($background['clouds']) : 
			if($background['clouds'] != 2) : ?>
		<div class="row__clouds row__clouds--top"></div>
		<?php endif; endif;
			if($background['flowers'] && $background['flowers']['top_flowers']) : 
				echo '<div class="row__flowers '.$background['flowers']['top_flowers'].'"></div>';
			endif;
		 echo $pattern;
		 	if($background['flowers'] && $background['flowers']['center_flowers']) : 
				echo '<div class="row__flowers '.$background['flowers']['center_flowers'].'"></div>';
			endif;
			if($background['flowers'] && $background['flowers']['bottom_flowers']) : 
				echo '<div class="row__flowers '.$background['flowers']['bottom_flowers'].'"></div>';
			endif;
		  ?>
	<?php
		$row = 0;
		while(have_rows('layout')) :
			the_row();
			$name = get_row_layout();
			include(locate_template( 'builder/' .$name .'.php', false, true ));
		endwhile;
		$row++; 
		if($background['ondine']) :
			if($background['ondine'] != 1) : 
			echo ($background['splash']) ? '<div class="row__splash"></div>' : '';
		?>
		<div class="row__wave row__wave--bottom"></div>
		<?php endif; endif;  
		if($background['clouds']) : 
			if($background['clouds'] != 1) : ?>
		<div class="row__clouds row__clouds--bottom"></div>
		<?php endif; endif; echo $abs_image; ?>
	</section>
<?php
	endif;
	endwhile;
?>