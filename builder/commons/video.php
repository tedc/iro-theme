<div class="<?php echo $video; ?>__close <?php echo $video; ?>__close--shrink-fw"><?php _e('Chiudi', 'iro'); ?> <i class="icon-chiudi" ng-click="closeVideo(); player.pause();"></i></div>
<div class="<?php echo $video; ?>__wrapper <?php echo $video; ?>__wrapper--shrink-fw">
<div class="<?php echo $video; ?>__player" youtube-video video-id="playerId" player-vars="playerVars" player="player">
</div>
</div>
<div class="<?php echo $video; ?>__controls <?php echo $video; ?>__close--shrink-fw">
	<span class="<?php echo $video; ?>__play-pause" ng-class="{'<?php echo $video; ?>__play-pause--playing': playing}" ng-click="playPause()">
		<span class="<?php echo $video; ?>__pause"></span>
		<i class="icon-play"></i>
	</span>
	<div class="<?php echo $video; ?>__progressbar" ng-click="skipTo($event, player)">
		<span class="<?php echo $video; ?>__progress progress"></span>
	</div>
	<time class="<?php echo $video; ?>__duration" ng-bind-html="time"></time>
</div>