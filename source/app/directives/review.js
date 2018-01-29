module.exports = ()=> {
	return {
		controller : ['$scope', '$http', ($scope, $http)=> {
			$scope.reviewFields = {}
			$scope.userFields = {}
			$scope.isReview = false;
			$scope.isError = false;
			$scope.sendReview = (valid, nonce, userId)=> {
				if(!valid) return;
				let user_data = []
				let account = `${vars.main.base}/wp-json/acf/v3/users/${userId}`;
				let review = `${vars.main.base}/wp-json/wp/v2/recensioni`;
				let config = {
					headers : {
						'X-WP-Nonce' : nonce
					}
				}
				let review_data = $scope.reviewFields;
				review_data.rating = [review_data.rating];
				review_data.prodotto_associato = [review_data.prodotto_associato];
				if($scope.reviewFields.id) {
					review = `${review}/${$scope.reviewFields.id}`;
					delete review_data.id;
				}
				let params = ($scope.reviewFields.id) ? {} : {params : {prodotto_associato : $scope.reviewFields.prodotto_associato, author : userId}}
				
				// REVIEW CREATE OR UPDATE

				if($scope.reviewFields.id) {
					review = `${review}/${$scope.reviewFields.id}`;
					delete review_data.id;
					$http
						.post(review, review_data, config)
						.then((res)=> {
							$scope.isReview = true;
							if(res.status >= 400) {
								$scope.isError = true;
							}
						});
				} else {
					$http
						.get(review, params)
						.then((res)=> {
							if(res.data.length > 0) {
								let id = res.data[0].id;
								review = `${review}/${id}`;
							}
							$http
								.post(review, review_data, config)
								.then((res)=> {
									$scope.isReview = true;
									if(res.status >= 400) {
										$scope.isError = true;
									}
								});
						});
				}

				// USER UPDATE


				user_data = $scope.userFields;
				$http
					.put(account, user_data, config);
			}
		}]
	}
}