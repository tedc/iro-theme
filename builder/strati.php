<?php
	$strati = 'layers';
	$folder_base = '';
?>
<div class="layers layers--shrink-fw <?php echo !get_sub_field('layers_content_full') ? ' layers--grid': ''; ?>" ng-layers ng-sm="{triggerHook : 1, class: 'layers--inview', duration : '110vh', offset : 80}">
	<?php if((get_sub_field('layers_title') || get_sub_field('layers_title')) && !get_sub_field('layers_content_full')) : ?>
	<header class="layers__header layers__header--grow-md layers__header--cell-s3">
		<?php if(get_sub_field('layers_title')) :  ?>
		<h2 class="layers__title layers__title--medium">
			<?php the_sub_field('layers_title'); ?>
		</h2>
		<?php endif; if(get_sub_field('layers_text')) :  ?>
		<div class="layers__content<?php echo (get_sub_field('layers_title')) ? ' layers__content--grow-top' : ''; ?>">
			<?php the_sub_field('layers_text'); ?>
		</div>
		<?php endif; ?>
	</header>
	<?php endif;
		if(get_sub_field('layers_title') && get_sub_field('layers_content_full')) :
	?>
	<header class="layers__header layers__header--grow-md layers__header--cell-mw">
		<?php if(get_sub_field('layers_title')) :  ?>
		<h2 class="layers__title layers__title--medium">
			<?php the_sub_field('layers_title'); ?>
		</h2>
		<?php endif; if(get_sub_field('layers_title') && get_sub_field('layers_content_above')) : ?>
		<div class="layers__content<?php echo (get_sub_field('layers_title')) ? ' layers__content--grow-top' : ''; ?>">
			<?php the_sub_field('layers_text'); ?>
		</div>
		<?php endif; ?>
	</header>
	<?php
		endif;
	 ?>
	<div class="layers__wrapper layers__wrapper--grow-md layers__wrapper--cell-s<?php if(!get_sub_field('layers_content_full')) { echo (get_sub_field('layers_title') || get_sub_field('layers_title')) ? 9 : 12; } ?>">
		<?php include(locate_template( 'builder/commons/strati.php', false, true )); ?>
		<?php if(have_rows('layers_pointers')) : ?>
		<div class="layers__pointers layers__pointers--grow-md-top">
			<?php while(have_rows('layers_pointers')) : the_row(); 
			?>
			<div class="layers__pointer" data-layer-to="<?php the_sub_field('pointer_layer'); ?>">
				<div class="layers__line" data-line="<?php the_sub_field('pointer_line'); ?>"></div>
				<?php if(get_sub_field('pointer_icon')){ echo print_svg(get_sub_field('pointer_icon'));} ?>
				<h3 class="layers__title"><?php the_sub_field('pointer_title'); ?></h3>
				<?php the_sub_field('pointer_text'); ?>
			</div>
			<?php endwhile; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php if(get_sub_field('layers_title') && get_sub_field('layers_content_full') && !get_sub_field('layers_content_above')) : ?>
	<div class="layers__content layers__content--cell-mw layers__content--grow-md-bottom">
		<?php the_sub_field('layers_text'); ?>
	</div>
	<?php endif; ?>
</div>