module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', '$timeout', ($scope, ecommerce, $timeout)=> {
			$scope.nlFields = {}
			$scope.isSubscribing = false;
			$scope.isNlError = false;
			$scope.nlTimeout = null;
			$scope.subscribe = (form)=> {
				if(form.$invalid) return;
				if($scope.isSubscribing) return;
				let data = $scope.nlFields;
				let ajax_url = vars.wc.newsletter;
				ecommerce
					.post(ajax_url, data)
					.then((res)=> {
						$scope.isSubscribing = false;
						$scope.nlMessage = res.data.message;
						$scope.isNlError = res.data.error;
						$scope.isSubscribing = true;
						$scope.nlTimeout = $timeout(()=> {
							$timeout.cancel($scope.nlTimeout);
							delete $scope.nlMessage;
						}, 5000);
					});
			}
		}]
	}
}