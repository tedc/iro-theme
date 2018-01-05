<?php 
	$src = explode('?v=', $src);
	$src = explode('&', $src[1]);
	$src = $src[0];
	if($video_file) : 
	$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', $video_file['url']);
?>
<div class="<?php echo $video; ?>__video" ng-style="{backgroundImage : 'url(<?php echo $file; ?>.jpg)'}">
	<video<?php if(!is_handheld()): ?> loop<?php endif; ?> class="<?php echo $video; ?>__video-item" ng-video muted poster="<?php echo $file; ?>.jpg">
		<source src="<?php echo $file; ?>.mp4" type="video/mp4"/>
		<source src="<?php echo $file; ?>.webm" type="video/webm"/>
	</video>
	<?php if(is_handheld()) { ?>
	<a class="icon-play" ng-click="play()" ng-class="{playing : !isPaused && !isLoading, loading : isLoading}"></a>
	<?php } ?>
</div>
<?php endif; ?>
<div class="<?php echo $video; ?>__iframe" player-id="<?php echo $video; ?>_video_<?php echo get_the_ID(); ?>" ng-player="<?php echo $src; ?>" ng-class="{'<?php echo $video; ?>__iframe--visible': isVideo['<?php echo $video; ?>_video_<?php echo get_the_ID(); ?>']}">
	<div class="<?php echo $video; ?>__player" youtube-video video-id="playerId" player-vars="playerVars" player="player">
	</div>
</div>