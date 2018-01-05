module.exports = ($rootScope, $timeout)=> {
	return {
		restrict : 'AE',
		link : (scope, element, attrs)=> {
			$rootScope.playerReady = {};
			scope.playerId = attrs.ngPlayer
			$rootScope.isVideo = {}
			scope.playerVars =  {
				controls : 0,
				showinfo : 0,
				rel : 0,
				modestbranding : 1
			}
			scope.$on('youtube.player.ready', (event, player)=> {
				if(player.getVideoData().video_id == scope.playerId) {
					$timeout(()=>{
						$rootScope.playerReady[attrs.playerId] = true;
					});
				}
			});
			scope.playPause = ()=> {
				if(scope.playing) {
					scope.player.pause();
				} else {
					scope.player.play();
				}	
			}
		}
	}
}