var iro = angular.module('iro');
iro
	.config(['$stateProvider', '$locationProvider', '$provide', require('./state')])
	.run(["$location", "$rootScope", "$window", "$state", "$cookies", "$transitions", "webFontLoader", 'angularLoad', ($location, $rootScope, $window, $state, $cookies, $transitions, webFontLoader, angularLoad) => {
		webFontLoader(['Baloo Bhaina','Encode Sans:300,400,600,800']);
		angularLoad.loadScript('https://www.youtube.com/iframe_api')
		FastClick.attach(document.body);
		$rootScope.isAnim = 'is-anim';
		var oldUrl = $location.absUrl();
		$rootScope.isUserLoggedIn = vars.wc.logged;
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
		$transitions.onStart({}, (trans) => {
			let newUrl = trans.router.stateService.href(trans.to().name, trans.params(), {absolute : true});
			$rootScope.fromState = {
				Name : trans.to().name,
				Params : trans.params(),
				Url : newUrl
			}
			if(newUrl === oldUrl) $rootScope.isAnim = '';
			var hash = $location.hash();
			if (hash.trim() === '') {
				if (newUrl === oldUrl) {
					if (trans.params().slug) {
						$rootScope.menuItem = trans.params().slug;
					} else if (oldUrl.replace(/\/$/g, '') === vars.main.base.replace(/\/$/g, '')) {
						$rootScope.menuItem = 'root';
					}
				} else {
					delete $rootScope.menuItem;
				}
			}
			if((newUrl.split('#')[0] === oldUrl.split('#')[0]) && (trans.params().name != 'login' && trans.params().name != 'cart' && trans.params().name != 'register')) return false;
			oldUrl = newUrl;
			$rootScope.$broadcast('sceneDestroy');
			$rootScope.$broadcast('updateScenes');
		});
		$transitions.onSuccess({}, ()=> {
			$rootScope.initEcommerce();
		})
	}]);