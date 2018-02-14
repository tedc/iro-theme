<?php 
	$video = 'video';
	if(get_sub_field('video')) :

	$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', get_sub_field('video')['url']);
	$video_id = $video.'_video_'. get_the_ID().'_'.$row;
?>
<div class="video video--cover video--grow-md" id="video_<?php $row; ?>" ng-style="{backgroundImage : 'url(<?php echo $file; ?>.jpg)'}">
	<video class="video__video" loop poster="<?php echo $file; ?>.jpg" ng-video>
		<source src="<?php echo $file; ?>.webm">
		<source src="<?php echo $file; ?>.mp4">
	</video>
	<div class="video__content video__content--mw video__content--shrink">
		<?php if(get_sub_field('video_title')) : ?>

		<h3 class="video__title video__title--big"><?php the_sub_field('video_title'); ?></h3>
		<?php endif;
			if(get_sub_field('video_title')) :
				the_sub_field('video_text'); endif; ?>
		<span class="video__open" ng-click="playIframe('<?php echo $video_id; ?>');">
			<i class="icon-play"></i><br/>
			<span class=""><?php _e('Guarda il video', 'iro'); ?></span>
		</span>
		<?php
		$button_class = 'video__button';
		if(get_sub_field('video_link')):
		$button_link = get_sub_field('video_link')['url'];
		$button_text = get_sub_field('video_link')['title'];
	?>
	<a class="video__button video__button--dark" class="<?php echo $button_class; ?>" ui-sref="app.page({slug : '<?php echo basename($button_link); ?>'})">
		<?php echo $button_text; ?>
	</a>
	<?php endif; ?>

	</div>
</div>
<?php if(get_sub_field('video_iframe')) :
	$src = get_sub_field('video_iframe', false, false);
	$src = explode('?v=', $src);
	$src = explode('&', $src[1]);
	$src = $src[0];
?>
<div class="<?php echo $video; ?>__iframe" player-id="<?php echo $video; ?>_video_<?php echo get_the_ID(); ?>" ng-player="<?php echo $src; ?>" ng-class="{'<?php echo $video; ?>__iframe--visible': isVideo['<?php echo $video_id; ?>']}">
	<?php include(locate_template( 'builder/commons/video.php', false, true )); ?>
</div>
<?php endif; endif; ?>