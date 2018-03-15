var iro = angular.module('iro');
iro
	.config(['$stateProvider', '$locationProvider', '$provide', 'cfpLoadingBarProvider', require('./state')])
	.run(["$location", "$rootScope", "$window", "$state", "$cookies", "$transitions", "webFontLoader", 'angularLoad',  '$animate', ($location, $rootScope, $window, $state, $cookies, $transitions, webFontLoader, angularLoad, $animate) => {
		webFontLoader(['Baloo Bhaina','Encode Sans:300,400,600,800']);
		$window.scrollTo(0, 0);
		angularLoad.loadScript('https://www.youtube.com/iframe_api')
		FastClick.attach(document.body);
		$rootScope.isAnim = false;
		var oldUrl = $location.absUrl();
		$rootScope.isMenu = false;
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
			$rootScope.isMenu = false;
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
					} else if(trans.to().name == 'app.blog' || trans.to().name == 'app.category' ) {
						$rootScope.menuItem = vars.main.blog;
					} else if(trans.to().name == 'app.reviews') {
						$rootScope.menuItem = vars.wc.reviews;
					} else if (oldUrl.replace(/\/$/g, '') === vars.main.base.replace(/\/$/g, '')) {
						$rootScope.menuItem = 'root';
					}
				} else {
					delete $rootScope.menuItem;
				}
			}
			if((newUrl.split('#')[0] === oldUrl.split('#')[0])) return false;
			console.log(true);
			oldUrl = newUrl;
			$rootScope.isAnim = true;
			$rootScope.$broadcast('sceneDestroy');
			$rootScope.$broadcast('updateScenes');
		});
		// $transitions.onSuccess({}, ()=> {
		// 	console.log(true);
		// 	//$rootScope.initEcommerce();
		// });	
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
				if(popup_visible) {
					$animate.removeClass(popup_visible, 'popup--visible');
				}
				if(cart_visible) {
					$animate.removeClass(cart_visible, 'cart-aside--visible');
				}
			}
		});
	}]);