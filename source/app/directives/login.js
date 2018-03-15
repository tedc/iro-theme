module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', '$rootScope', '$window', '$location', '$state', ($scope, ecommerce, $rootScope, $window, $location, $state)=> {
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
						if($rootScope.isUserLoggedIn) {
							$scope.error = true;
							$scope.errorMessage = res.data.message;
							$state.go('app.page', {slug : vars.wc.accountBase});
						} else {
							$scope.error = false;
							$scope.close();
						}
					});
			}
			$scope.pwdMatch = (user)=> {
				$scope.registerForm.password_confirm.$setValidity('pwdmatch', user.password_confirm == user.password);
			}
		}]
	}
}