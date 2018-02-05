module.exports = ($rootScope, $timeout)=> {
	return {
		restrict : 'AE',
		scope: true,
		link : (scope, element, attrs)=> {
			$rootScope.playerReady = {};
			scope.playerId = attrs.ngPlayer
			$rootScope.isVideo = {}
			scope.playing = false;
			var progressTimeout = null;
			scope.playerVars =  {
				controls : 0,
				showinfo : 0,
				rel : 0,
				modestbranding : 1
			}
			let onProgress = (player)=>{
				TweenMax.to( element[0].querySelector('.progress'), .5, {width: `${timeToPercentage(player)}%`})
				scope.time = secondsToTime(player.getCurrentTime());
				if(!scope.playing) return;
				progressTimeout = $timeout(()=>{
					onProgress(player)
				}, 0);
			}
			let timeToPercentage = (player)=> {
				let total = player.getDuration();
				let current = player.getCurrentTime();
				let value = ( current / total ) * 100;
				return value;
			}
			let secondsToTime = (time)=>{
				let sec_num = parseInt(time, 10);
				let hours   = Math.floor(sec_num / 3600);
				let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
				let seconds = sec_num - (hours * 3600) - (minutes * 60);
				if(hours < 10) hours   = `0${hours}`;;
				if(minutes < 10) minutes = `0${minutes}`;
				if(seconds < 10) seconds = `0${seconds}`;
				return `${hours}:${minutes}:${seconds}`;
			}
			scope.skipTo = ($event, player)=>{
				let progress = element[0].querySelector('.progress');
				let max = player.getDuration();
				let w = $event.target.offsetWidth;
				let x = $event.offsetX;
				let point = x / w;
				let val = Math.round ( max * point );
				player.seekTo(val);
				if(!scope.playing) player.playVideo();
				TweenMax.to(progress, .5,{width : "#{(point * 100)}%"})
			}
			scope.$on( 'youtube.player.ended', (event, player)=>{
				if(progressTimeout !== null) $timeout.cancel( progressTimeout );
				progressTimeout = null;
				scope.playing = false;
				TweenMax.to(element[0].querySelector('.progress'), .5, {width: "100%"});
				scope.time = "00:00:00";
			});
			scope.$on('youtube.player.ready', (event, player)=> {
				if(player.getVideoData().video_id == scope.playerId) {
					$timeout(()=>{
						$rootScope.playerReady[attrs.playerId] = true;
					});
				}
				
			});
			scope.$on('youtube.player.playing', (event, player)=> {
				scope.playing = true;
				onProgress(player);
			});
			scope.$on('youtube.player.paused', (event, player)=> {
				scope.playing = false;
				if(progressTimeout !== null) $timeout.cancel( progressTimeout );
			});
			$rootScope.playIframe = (id)=> {
				$rootScope.isVideo[id] = true;
				scope.player.playVideo();
			}
			scope.playPause = ()=> {
				if(scope.playing) {
					scope.player.pauseVideo();
				} else {
					scope.player.playVideo();
				}	
			}
			scope.closeVideo = ()=> {		
				$rootScope.isVideo = {};
				scope.player.stopVideo();
				TweenMax.to(element[0].querySelector('.progress'), .5, {width: "0%"});
			}
		}
	}
}