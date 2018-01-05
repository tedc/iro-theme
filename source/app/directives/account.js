module.exports = ()=> {
	return {
		controller : ['$scope', '$rootScope', 'ecommerce', '$state', '$window', ($scope, $rootScope, ecommerce, $state, $window)=> {
			$scope.isLoggingOut = false;
			$scope.logout = (url)=> {
				$scope.isLoggingOut = true;
				ecommerce
					.get(url)
					.then( (res)=> {
						$window.location = vars.main.base;
					} );
			}
			$scope.accountFields = {
				customer : {},
				isSaving : []
			};
			$scope.save = (valid, endpoint, kind)=> {
				if(!valid) return;
				$scope.accountFields.isSaving[kind] = true;
				let url = vars.wc[endpoint];
				let data = $scope.accountFields.customer[kind];
				data = angular.extend(data, {}, {address_kind : kind});
				ecommerce
					.post(url, data)
					.then( (res)=> {
						if(res.data.success) {
							$scope.saveMessage = res.success;
							$scope.messageClass = 'success';
							$state.go('app.account', {path : res.data.redirect}, {reload : true});
						} else {
							$scope.saveMessage = res.error;
							$scope.messageClass = 'error';
						}
						$scope.accountFields.isSaving[kind] = false;
					});
			}
		}]
	}
}