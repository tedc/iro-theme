module.exports = ()=> {
	return {
		controller : ['$scope', 'ecommerce', '$state', '$window', ($scope, ecommerce, $state, $window)=> {
			$scope.isPaying = false;
			$scope.payFields = {
				terms: true
			}
			$scope.pay = (form)=> {
				if(form.$invalid) return;
				$scope.payErrorMessage = false;
				$scope.isPaying = true;
				let url = vars.wc.pay;
				ecommerce
					.post(url, $scope.payFields)
					.then( (res) => {
						if(res.data.error) {
							$scope.payErrorMessage = true;
							$scope.error = res.data.error;
						} else {
							let redirect = ( -1 === res.data.success.indexOf( 'https://' ) || -1 === res.data.success.indexOf( 'http://' ) ) ? res.data.success : decodeURI(res.data.success);
							let pattern = new RegExp(`${vars.main.base}`);
							if(!pattern.test(redirect)) {
								$window.location = redirect;
							} else {
								var state = redirect.replace(vars.main.base + '/'+vars.wc.orderBase+'/', '');
								state = state.split('?key=');
								var order = state[0];
								order.replace(/\//g, '');
								var key = state[1];
								//purchase.action['id'] = order;							
								$state.go('app.order', {order : order, key : key});
							}
							// $window.dataLayer.push({
							// 	'event' : 'purchase',
							// 	'ecommerce': {
							// 		'purchase': purchase
							// 	}
							// });
						}
						$scope.isPaying = false;
					}, (err) => {
						$scope.payErrorMessage = true;
						$scope.isPaying = false;
					});
			}
		}]
	}
}