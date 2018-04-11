<?php
	$strati = 'layers';
	$folder_base = '/big/';
	$count_layers= count(get_sub_field('layers_pointers')); 
?>
<div class="layers layers--shrink-fw <?php echo !get_sub_field('layers_content_full') ? ' layers--grid': ''; ?>" ng-layers ng-sm="{triggerHook : 1, class: 'layers--inview', duration : '110vh', offset : 80}" data-count="<?php echo $count_layers; ?>">
	<?php if((get_sub_field('layers_title') || get_sub_field('layers_title')) && !get_sub_field('layers_content_full')) : ?>
	<header class="layers__header layers__header--grow-md layers__header--cell-s3">
		<?php if(get_sub_field('layers_title')) :  ?>
		<h2 class="layers__title layers__title--medium">
			<?php the_sub_field('layers_title'); ?>
		</h2>
		<?php endif; if(get_sub_field('layers_text')) :  ?>
		<div class="layers__content<?php echo (get_sub_field('layers_title')) ? ' layers__content--grow-top' : ''; ?>">
			<div class="layers__text"><?php the_sub_field('layers_text'); ?></div>
		</div>
		<?php endif; ?>
	</header>
	<?php endif;
		if(get_sub_field('layers_title') && get_sub_field('layers_content_full')) :
	?>
	<header class="layers__header layers__header--aligncenter layers__header--grow-md layers__header--cell-mw">
		<?php if(get_sub_field('layers_title')) :  ?>
		<h2 class="layers__title layers__title--medium">
			<?php the_sub_field('layers_title'); ?>
		</h2>
		<?php endif; if(get_sub_field('layers_title') && !get_sub_field('layers_content_above')) : ?>
		<div class="layers__content<?php echo (get_sub_field('layers_title')) ? ' layers__content--grow-top' : ''; ?>">
			<div class="layers__text"><?php the_sub_field('layers_text'); ?></div>
		</div>
		<?php endif; ?>
	</header>
	<?php
		endif;
	 ?>
	<div class="layers__wrapper layers__wrapper--grow-md layers__wrapper--cell-s<?php if(!get_sub_field('layers_content_full')) { echo (get_sub_field('layers_title') || get_sub_field('layers_title')) ? 9 : 12; } ?>">
		<figure class="layers__images">
			<img src="<?php echo get_stylesheet_directory_uri() .'/assets/images/strati/big/shadow.jpg';  ?>" />
			<img src="<?php echo get_stylesheet_directory_uri() .'/assets/images/strati/big/filler.png'; ?>" />
			<?php include(locate_template( 'builder/commons/strati.php', false, true )); ?>
			<svg viewBox="0 0 2200 592">
				<defs>
					<linearGradient id="layers_gradient">
				      <stop offset="0" stop-color="white" stop-opacity="1" />
				      <stop class="layers__stop" id="stop_1" offset="0.33" stop-color="white" stop-opacity="1" />
				      <stop class="layers__stop" id="stop_2" offset="0.5" stop-color="white" stop-opacity="0" />
				      <stop offset="1" stop-color="white" stop-opacity="0" />
				    </linearGradient>
				    <mask id="layers_mask">
				      <rect x="0" y="0" width="2200" height="592" fill="url(#layers_gradient)"  />
				    </mask>
				</defs>
				<image x="0" y="0" width="2200" height="592" xlink:href="<?php echo get_stylesheet_directory_uri() .'/assets/images/strati/big/cover.png'; ?>" mask="url(#layers_mask)"></image>
			</svg>
			<div class="layers__lines">
			<?php $l=0;while(have_rows('layers_pointers')) : the_row();
			?>
			<div class="layers__line layers__line--<?php echo ($l%2==0) ? 'odd':'even'; ?>" data-layer-to="<?php the_sub_field('pointer_layer'); ?>"></div>
			<?php $l++; endwhile; ?>
		</div>
		</figure>
		<?php if(have_rows('layers_pointers')) : ?>
		<div class="layers__pointers layers__pointers--grow-md-top">
			<?php $l=0;while(have_rows('layers_pointers')) : the_row();
			?>
			<div class="layers__pointer layers__pointer--<?php echo ($l%2==0) ? 'odd':'even'; ?>">
				<div class="layers__content<?php echo ($l==0)? ' layer-active' : ''; ?>" data-layer-to="<?php the_sub_field('pointer_layer'); ?>">
					<?php 
							$plus = ($l + 1 > $count_layers - 1) ? 0 : $l + 1;
							$minus = ($l - 1 < 0) ? $count_layers - 1 : $l - 1; 
						?>
					<i class="icon-arrow-left" ng-click="$event.stopPropagation(); move(<?php echo $minus; ?>);"></i>
					<?php if($l%2==0) : ?>
					<div class="layers__text">
					<?php endif; ?>
					<?php if(get_sub_field('pointer_icon')){ echo print_svg(get_sub_field('pointer_icon'));} ?>
				
					<h3 class="layers__title layers__title--nav">
						
						<?php the_sub_field('pointer_title'); ?>
					</h3>
					<?php if($l%2!=0) : ?>
						<div class="layers__text">
						<?php endif; ?>
						<?php the_sub_field('pointer_text'); ?>
					</div>
					<i class="icon-arrow-right" ng-click="$event.stopPropagation(); move(<?php echo $plus; ?>);"></i>
				</div>
			</div>
			<?php $l++;endwhile; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php if(get_sub_field('layers_title') && get_sub_field('layers_content_full') && get_sub_field('layers_content_above')) : ?>
	<div class="layers__content layers__content--aligncenter layers__content--cell-mw layers__content--grow-md-bottom">
		<?php the_sub_field('layers_text'); ?>
	</div>
	<?php endif; ?>
</div>