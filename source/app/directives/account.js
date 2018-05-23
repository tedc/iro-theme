module.exports = ()=> {
	return {
		controller : ['$scope', '$rootScope', 'ecommerce', '$window', ($scope, $rootScope, ecommerce, $window)=> {
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

			$scope.account = {
				isSaving: false
			}

			$scope.updateAccount = (valid)=> {
				if(!valid) return;
				$scope.account.isSaving= true;
				let data = $scope.account;
				ecommerce
					.post(data._wp_http_referer, data)
					.then((res)=> {
						$window.location = data._wp_http_referer;
					});
			}
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
							$window.location = res.data.redirect;
							//$state.go('app.account', {path : res.data.redirect}, {reload : true});
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