module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', ($scope, ecommerce)=> {
			$scope.isPaying = false;
			$scope.checkoutFields = {
				terms: true
			}
			$scope.pay = (form)=> {
				if(form.$invalid) return;
				$scope.payErrorMessage = false;
				$scope.isPaying = true;
				let url = vars.wc.pay;
				console.log($scope.checkoutFields);
				ecommerce
					.post(url, $scope.checkoutFields)
					.then( (res) => {
						console.log(res);
						$scope.isPaying = false;
					}, (err) => {
						$scope.payErrorMessage = true;
						$scope.isPaying = false;
					});
			}
		}]
	}
}