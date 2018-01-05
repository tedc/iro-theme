module.exports = ($scope, ecommerce, $rootScope, $window, PreviousState)=> {
	$scope.close = ()=> {
		let pages = ($rootScope.previousState.Name == 'tab') ? -2 : -1;
		$window.history.go(pages);
	}
	$scope.user = {}
	$scope.sign = ()=> {
		let data = $scope.user;
		let ajax_url = vars.wc.login;
		ecommerce
			.post(ajax_url, data)
			.then((res)=> {
				$rootScope.isUserLoggedIn = data.loggedin;
			});
	}
	$scope.pwdMatch = (user)=> {
		$scope.registerForm.password_confirm.$setValidity('pwdmatch', user.password_confirm == user.password);
	}
}