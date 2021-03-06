<?php 
	$video = 'video';
	if(get_sub_field('video')) :

	$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', get_sub_field('video')['url']);
	$video_id = $video.'_video_'. get_the_ID().'_'.$row;
	$is_waves = get_sub_field('video_waves');
	
?>
<div class="video video--cover video--grow-md<?php echo ($is_waves) ? ' video--waves' : ''; ?>" id="video_<?php $row; ?>" ng-style="{backgroundImage : 'url(<?php echo $file; ?>.jpg)'}" ng-class="{'video--playing' : isVideoPlaying}">
	<video data-object-fit class="video__video" muted loop poster="<?php echo $file; ?>.jpg" ng-video>
		<source src="<?php echo $file; ?>.webm" type="video/webm">
		<source src="<?php echo $file; ?>.mp4" type="video/mp4">
	</video>
	<?php if($is_waves) : 
		$video_waves_class = get_sub_field('video_waves');
	?>
	<div class="<?php echo $video_waves_class; ?>">
		<div class="video__wave"></div>
		<div class="video__wave"></div>
		<div class="video__wave"></div>
	</div>
	<?php endif; ?>
	<div class="video__content video__content--mw video__content--shrink">
		<?php if(get_sub_field('video_title')) : ?>
		<h3 class="video__title video__title--big"><?php the_sub_field('video_title'); ?></h3>
		<?php endif;
			if(get_sub_field('video_text')) :
		?>
		<div class="video__text video__text--grow">
			<?php the_sub_field('video_text'); ?>
		</div>
		<?php endif; if(get_sub_field('video_iframe')) : ?>
		<span class="video__open" ng-click="playIframe('<?php echo $video_id; ?>');">
			<i class="icon-play"></i><br/>
			<span><?php _e('Guarda il video', 'iro'); ?></span>
		</span>
		<?php endif;
		$button_class = 'video__button';
		if(get_sub_field('video_link')):
		$button_link = get_sub_field('video_link')['url'];
		$button_text = get_sub_field('video_link')['title'];
		$ga_click = '';
		foreach (get_posts(array('post_type'=>'product', 'posts_per_page'=>-1)) as $p) {
			if(get_permalink($p->ID) == $button_link) {
				$ga_click = ' ga-click';
				break;
			}
		}
	?>
	<!-- <a class="video__button video__button--dark" class="<?php echo $button_class; ?>" ui-sref="app.page({slug : '<?php echo basename($button_link); ?>'})"<?php echo $ga_click; ?>> -->
	<a class="video__button video__button--dark" class="<?php echo $button_class; ?>" href="<?php echo $button_link; ?>"<?php echo $ga_click; ?>>
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