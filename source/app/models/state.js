const tpl = '<main class="main" bind-html-compile="content"></main>'
module.exports = function ($stateProvider, $locationProvider, $provide, cfpLoadingBarProvider) {
	cfpLoadingBarProvider.includeSpinner = false;
	$provide.decorator('$locale', ['$delegate', function ($delegate) {
		$delegate.NUMBER_FORMATS.GROUP_SEP = '.';
        $delegate.NUMBER_FORMATS.DECIMAL_SEP = ',';
        return $delegate;
    }]);

	$locationProvider.html5Mode({
		enabled : true,
		rewriteLinks : false,
		requireBase : false
	});
	$stateProvider
		.state('app', {
				abstract : true,
				url : `/{lang:(?:${vars.lang.langs})}`,			
				template : '<ui-view class="view" autoscroll="true"></ui-view>',
				params : {
					lang : {
						squash : true,
						value : vars.lang.default
					}
				}
			}
		)
		.state('app.root', {
			url : '/',
			template : tpl,
			resolve : {
				data : ['getData', function(getData) {
					return getData();
				}]
			},
			controller : ['$rootScope', '$scope', 'data', require('./html')]
		})
		.state('app.page', {
			url : '/{slug:(?!tab$)[a-zA-Z0-9\-]*}?productId',
			template : tpl,
			resolve : {
				data : ['getData', '$stateParams', function(getData, $stateParams) {
					let base_url = ($stateParams.productId) ? `${$stateParams.slug}?productId=${$stateParams.productId}`: $stateParams.slug;
					return getData(base_url);
				}],
			},
			controller : ['$rootScope', '$scope', 'data', require('./html')]
		})
		.state('app.account', {
			url : `/${vars.wc.accountBase}/:path`,
			template : tpl,
			params: {
				path: {
					type : 'string',
					raw: true
				}
			},
			resolve : {
				data : ['getData', '$stateParams', function(getData, $stateParams) {
					let url = `${vars.wc.accountBase}/${$stateParams.path}`
					return getData(url);
				}]
			},
			controller : ['$rootScope', '$scope', 'data', require('./html')]
		})
		.state('app.order', {
			url : `/${vars.wc.orderBase}/:order?key`,
			template : tpl,
			resolve : {
				data : ['getData', '$stateParams', 'ngCart', function(getData, $stateParams, ngCart) {
					ngCart.empty();
					let url = `${vars.wc.orderBase}/${$stateParams.order}?key=$stateParams.key`
					return getData(url);
				}]
			},
			controller : ['$rootScope', '$scope', 'data', require('./html')]
		})
		.state('app.reviews', {
			url : `/${vars.wc.reviews}?review_product&rating`,
			template : tpl,
			resolve : {
				data : ['getData',  '$stateParams', (getData, $stateParams) => {
					let url = `${vars.wc.reviews}/`;
					if($stateParams.review_product) {
						url += '?review_product=' + $stateParams.review_product;
					}
					let and = ($stateParams.review_product) ? '&' : '?';
					if($stateParams.rating) {
						url += and + 'rating=' + $stateParams.rating;
					}
					console.log(url);
					return getData(url);
				}]
			},
			controller : ['$rootScope', '$scope', 'data', 'ngCart', require('./html')]
		})
		// .state('app.tab', {
		// 	url : '?name',	
		// 	resolve : {
		// 		PreviousState: ["$state", '$rootScope', ($state, $rootScope)=> {
		// 			$rootScope.previousState = {
		// 				Name: $state.current.name,
		// 				Params: $state.params,
		// 				URL: $state.href($state.current.name, $state.params)
		// 			}
		// 		}]
		// 	},
		// 	views : {
		// 		'tab@': {
		// 			//template : 'prova',
		// 			templateUrl : (toParams) => `${toParams.name}.html`,	
		// 			controller : ['$scope', 'ecommerce', '$rootScope', '$window', 'PreviousState', '$state', require('./login')]
		// 		}
		// 	}
		// })
}