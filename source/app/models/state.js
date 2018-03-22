const tpl = '<main class="main" bind-html-compile="content"></main>'
module.exports = function ($locationProvider, $provide) {
	cfpLoadingBarProvider.includeSpinner = false;
	$provide.decorator('$locale', ['$delegate', function ($delegate) {
		$delegate.NUMBER_FORMATS.GROUP_SEP = '.';
        $delegate.NUMBER_FORMATS.DECIMAL_SEP = ',';
        return $delegate;
    }]);

	// $locationProvider.html5Mode({
	// 	enabled : true,
	// 	rewriteLinks : false,
	// 	requireBase : false
	// });
	// $stateProvider
	// 	.state('app', {
	// 			abstract : true,
	// 			url : `/{lang:(?:${vars.lang.langs})}`,			
	// 			template : '<ui-view class="view"></ui-view>',
	// 			params : {
	// 				lang : {
	// 					squash : true,
	// 					value : vars.lang.default
	// 				}
	// 			}
	// 		}
	// 	)
	// 	.state('app.root', {
	// 		url : '/',
	// 		template : tpl,
	// 		resolve : {
	// 			data : ['getData', function(getData) {
	// 				return getData();
	// 			}]
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
	// 	.state('app.page', {
	// 		url : '/{slug:(?!tab$)[a-zA-Z0-9\-]*}?productId&attribute_pa_color&attribute_pa_misure',
	// 		template : tpl,
	// 		resolve : {
	// 			data : ['getData', '$stateParams', function(getData, $stateParams) {
	// 				let base_url = ($stateParams.productId) ? `${$stateParams.slug}?productId=${$stateParams.productId}`: $stateParams.slug;
	// 				if($stateParams.attribute_pa_color) {
	// 					let and = ($stateParams.productId) ? '&' : '?';
	// 					base_url += and + 'attribute_pa_color=' + $stateParams.attribute_pa_color;
	// 				}
	// 				if($stateParams.attribute_pa_misure) {
	// 					let and = ($stateParams.attribute_pa_color || $stateParams.productId) ? '&' : '?';
	// 					base_url += and + 'attribute_pa_misure=' + $stateParams.attribute_pa_misure;
	// 				}
	// 				return getData(base_url);
	// 			}],
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
	// 	.state('app.account', {
	// 		url : `/${vars.wc.accountBase}/:path?show-reset-form`,
	// 		template : tpl,
	// 		params: {
	// 			path: {
	// 				type : 'string',
	// 				raw: true
	// 			}
	// 		},
	// 		resolve : {
	// 			data : ['getData', '$stateParams', function(getData, $stateParams) {
	// 				let url = `${vars.wc.accountBase}/${$stateParams.path}`
	// 				console.log($stateParams);
	// 				return getData(url);
	// 			}]
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
	// 	.state('app.order', {
	// 		url : `/${vars.wc.orderBase}/:order?key`,
	// 		template : tpl,
	// 		params: {
	// 			order: {
	// 				value : null,
	// 				squash : true,
	// 				type : 'string',
	// 				raw: true
	// 			}
	// 		},
	// 		resolve : {
	// 			data : ['getData', '$stateParams', 'ngCart', function(getData, $stateParams, ngCart) {
	// 				ngCart.empty();
	// 				let url = `${vars.wc.orderBase}/${$stateParams.order}?key=${$stateParams.key}`
	// 				console.log(url);
	// 				return getData(url);
	// 			}]
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
	// 	.state('app.reviews', {
	// 		url : `/${vars.wc.reviews}/:path?review_product&rating`,
	// 		template : tpl,
	// 		params: {
	// 			path: {
	// 				value : null,
	// 				squash : true,
	// 				type : 'string',
	// 				raw: true
	// 			}
	// 		},
	// 		resolve : {
	// 			data : ['getData',  '$stateParams', '$rootScope', (getData, $stateParams, $rootScope) => {
	// 				let url = ($stateParams.path) ? `${vars.wc.reviews}/${$stateParams.path}` : `${vars.wc.reviews}`;
	// 				if($stateParams.review_product) {
	// 					url += '?review_product=' + $stateParams.review_product;
	// 				}
	// 				let and = ($stateParams.review_product) ? '&' : '?';
	// 				if($stateParams.rating) {
	// 					url += and + 'rating=' + $stateParams.rating;
	// 				}
	// 				if(url == vars.wc.reviews) {
	// 					$rootScope.menuItem = vars.wc.reviews;
	// 				}
	// 				return getData(url);
	// 			}]
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', 'ngCart', require('./html')]
	// 	})
	// 	.state('app.blog', {
	// 		url : `/${vars.main.blog}/:path`,
	// 		template : tpl,
	// 		params: {
	// 			path: {
	// 				value : null,
	// 				squash : true,
	// 				type : 'string',
	// 				raw: true
	// 			}
	// 		},
	// 		resolve : {
	// 			data : ['getData', '$stateParams', '$rootScope', function(getData, $stateParams, $rootScope) {
	// 				let base_url = ($stateParams.path) ? `${vars.main.blog}/${$stateParams.path}` : `${vars.main.blog}`;
	// 				if(base_url == vars.main.blog) {
	// 					$rootScope.menuItem = vars.main.blog;
	// 				}
	// 				return getData(base_url);
	// 			}],
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
	// 	.state('app.category', {
	// 		url : `/${vars.main.category}/:name/:path`,
	// 		template : tpl,
	// 		params: {
	// 			path: {
	// 				value : null,
	// 				squash : true,
	// 				type : 'string',
	// 				raw: true
	// 			}
	// 		},
	// 		resolve : {
	// 			data : ['getData', '$stateParams', '$rootScope', function(getData, $stateParams, $rootScope) {
	// 				let base_url = ($stateParams.path) ? `${vars.main.category}/${$stateParams.name}/${$stateParams.path}` : `${vars.main.category}/${$stateParams.name}`;
	// 				$rootScope.menuItem = vars.main.blog;
	// 				return getData(base_url);
	// 			}],
	// 		},
	// 		controller : ['$rootScope', '$scope', 'data', require('./html')]
	// 	})
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