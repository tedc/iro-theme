module.exports = ()=> {
	return {
		scope: true,
		controller : ['$scope', 'ecommerce', '$rootScope', '$window', '$location', ($scope, ecommerce, $rootScope, $window, $location)=> {
			$scope.close = ()=> {
				if( $window.history && $window.history.pushState ) {
					history.pushState('', document.title, $location.path());
				} else {
					$location.hash('');
				}
			}
			$rootScope.isLogging = false;
			$scope.user = {}
			$scope.sign = (kind)=> {
				if($rootScope.isLogging) return;
				$rootScope.isLogging = true;
				let data = $scope.user;
				let ajax_url = vars.wc[kind];
				$scope.error = false;
				ecommerce
					.post(ajax_url, data)
					.then((res)=> {
						$rootScope.isUserLoggedIn = res.data.loggedin;
						$rootScope.isLogging = false;
						if(res.data.loggedin) {
							$scope.error = false;
							$window.location = vars.main.base + '/' + vars.wc.accountBase;
							if(kind == 'register' && $window.fbq) {
								$window.fbq('track', 'CompleteRegistration');
							}
							//$state.go('app.page', {slug : vars.wc.accountBase});
						} else {
							$scope.error = true;
							$scope.errorMessage = res.data.message;
						}
					});
			}
			$scope.pwdMatch = (user)=> {
				$scope.registerForm.password_confirm.$setValidity('pwdmatch', user.password_confirm == user.password);
			}
		}]
	}
}