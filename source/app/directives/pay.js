module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', ($scope, ecommerce)=> {
			$scope.isPaying = false;
			$scope.payFields = {
				terms: 1
			}
			$scope.pay = (form)=> {
				if(form.$invalid) return;
				$scope.payErrorMessage = false;
				$scope.isPaying = true;
				let url = vars.wc.pay;
				let data = {
					_wpnonce : $scope.payFields._wpnonce,
					key : $scope.payFields.key,
					payment_method : $scope.payFields.payment_method,
					order_pay : $scope.payFields.order_pay
				}
				console.log($scope.payFields);
				ecommerce
					.post(url, data)
					.then( (res) => {
						console.log(res);
						$scope.isPaying = false;
					}, (err) => {
						console.log(err);
						$scope.payErrorMessage = true;
						$scope.isPaying = false;
					});
			}
		}]
	}
}