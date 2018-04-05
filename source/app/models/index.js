var iro = angular.module('iro');
iro
	.config(['$provide', '$locationProvider', ($provide, $locationProvider)=> {
		$provide.decorator('$locale', ['$delegate', function ($delegate) {
			$delegate.NUMBER_FORMATS.GROUP_SEP = '.';
	        $delegate.NUMBER_FORMATS.DECIMAL_SEP = ',';
	        return $delegate;
	    }]);
	    $locationProvider.html5Mode({
			enabled : true,
			rewriteLinks : false,
			requireBase : false
		});
	}])
	.run(["$location", "$rootScope", "$window", "$cookies", 'angularLoad',  '$animate', '$timeout', 'ecommerce', ($location, $rootScope, $window, $cookies, angularLoad, $animate, $timeout, ecommerce) => {
		//webFontLoader(['Baloo Bhaina','Encode Sans:300,400,600,800']);
		angularLoad.loadScript('https://www.youtube.com/iframe_api')
		FastClick.attach(document.body);
		//$rootScope.isAnim = false;
		//var oldUrl = $location.absUrl();
		$rootScope.isMenu = false;
		$rootScope.isUserLoggedIn = vars.wc.logged;
		if(vars.main.download) {
			let download_url = `${vars.main.base}/download/${vars.main.download}`;
			ecommerce
				.post(download_url, {})
				.then((res)=> {
					$window.location = `${vars.main.base}/download/${vars.main.download}`;
					$timeout(function() {
						$window.location = vars.main.base;
					}, 1000);
				});
		}
		// var langCookie = $cookies.get('lang');
		// if(!langCookie) {
		// 	let currentDate = new Date();
		// 	currentDate.setDate(currentDate.getDate() + 1);
		// 	$cookies.put('lang', 1, {'expires' : currentDate});
		// 	let redirect = vars.lang.redirect;
		// 	if(redirect.current != redirect.lang) {
		// 		let url = redirect.url;
		// 		$window.location.href = url;
		// 	}
		// }
		//objectFitPolyfill();
		let refreshSliders = ()=> {
			if(window.cssLoaded) return;
			$timeout(()=> {
				//$window.scrollTo(0, 0);
				$timeout(()=> {$rootScope.$broadcast('update_scroller')}, 250);
				refreshSliders();
			}, 10);
		}
		refreshSliders();
		$rootScope.$on('$locationChangeSuccess', ()=> {
			let popup_visible = angular.element(document.querySelector('.popup--visible'));
			let cart_visible = angular.element(document.querySelector('.cart-aside--visible'));
			let hash = $location.hash();
			if(hash) {
				let class_to_add = (hash == 'cart') ? 'cart-aside--visible' : 'popup--visible';
				if(hash != 'login' && hash != 'register' && hash != 'cart') return;
				if((hash == 'register' || hash == 'login') && $rootScope.isUserLoggedIn) {
					history.pushState('', document.title, $location.path());
				}
				let popup = angular.element(document.getElementById(hash));
				if(popup_visible) {
					$animate.removeClass(popup_visible, 'popup--visible');
				}
				if(cart_visible) {
					$animate.removeClass(cart_visible, 'cart-aside--visible');
				}
				$animate
					.addClass(popup, class_to_add)
					.then(()=> {
						$rootScope.$broadcast('update_scroller');
					});
			} else {
				let popup_visible = angular.element(document.querySelector('.popup--visible'));
				console.log(popup_visible);
				if(popup_visible) {
					$animate.removeClass(popup_visible, 'popup--visible');
				}
				if(cart_visible) {
					$animate.removeClass(cart_visible, 'cart-aside--visible');
				}
			}
		});
	}]);