module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', ($scope, ecommerce)=> {
			$scope.isPaying = false;
			$scope.checkoutFields = {}
			$scope.pay = (form)=> {
				if(form.$invalid) return;
				$scope.payErrorMessage = false;
				$scope.isPaying = true;
				let url = vars.wc.pay;
				ecommerce
					.post(url, $scope.checkoutFields)
					.then( (res) => {
						console.log(res);
						$scope.isPaying = false;
					})
					.catch( (err)=> {
						console.log(err);
						$scope.payErrorMessage = true;
						$scope.isPaying = false;
					} );
			}
		}]
	}
}