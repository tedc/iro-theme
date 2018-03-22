var iro = angular.module('iro');
iro
	.factory('getData', ['$http', '$q', function($http, $q) {
		return function(slug) {
			var slug = (slug) ? slug : '';
			var url = `${vars.main.base}/${slug}`
			var deferred = $q.defer();
			$http
				.get(url)
				.then(function(res) {
					var data = {};
					data.langs = [];
					data.content = res.data.split(/(<main class="main">|<\/main>)/g)[2]
					data.title = res.data.split(/(<title>|<\/title>)/g)[2];
					data.bodyClass = res.data.match(/(<body(.*)class=\"[^"]*\"[^>]*>)/ig);
					data.bodyClass = data.bodyClass[0].split(/(class=\"|\")/g)[2];
					let langs = res.data.match(/(<link(.*)hreflang[^>]*>)/ig);
					if(langs && langs.length > 0) {
						langs = langs.join().replace('/\\/g', '').replace('/,/g', '').split('>').filter(x => x);
						for(let lang of langs) {
							let l = lang.match(/(hreflang=\"[^"]*\")/g)[0];
							let u = lang.match(/(href=\"[^"]*\")/g)[0];
							l = l.split(/(hreflang=\"|\")/g)[2];
							l = l.substring(0, 2);
							u = u.split(/(href=\"|\")/g)[2];
							//console.log(l, u);
						}
					}
							
					deferred.resolve(data);
				});
			return deferred.promise;
		}
	}])
	.factory('getInstances', ['$q', ($q) => {
		var deferreds = {};
		var instances = {};
		var id = 0;
		var services = {
			generateName : ()=> {
				id++;
				return Date.now().toString(32) + '$' + id;
			},
			getInstance : (name)=> {
				let deferred = deferreds[name] = deferreds[name] || $q.defer();
				if(instances.hasOwnProperty(name)) {
					deferred.resolve(instances[name]);
				}
				return deferred.promise;
			},
			createInstance : (name, obj) => {
				if(instances.hasOwnProperty(name)) {
					return instances[name];
				}
				let instance = instances[name] = obj;
				if (deferreds.hasOwnProperty(name)) {
					deferreds[name].resolve(instance);
				}
				return instance;
			},
			destroyInstance : (name) => {
				let instance = instances[name];
				if(instance) {
					instance.destroy();
					delete instances[name];
					delete deferreds[name];
				}
			},
			updateInstance : (name) => {
				let instance = instances[name];
				if(instance) {
					instance.update();
				}
			}
		};
		return services;
	}])
	.factory('ecommerce', [ '$http', '$httpParamSerializerJQLike', '$q',( $http, $httpParamSerializerJQLike, $q)=> {
		let obj = {
			findMatchingVariations : ( variations, attributes ) => {
				var matching = [];
				for ( var i = 0; i < variations.length; i++ ) {
					var variation = variations[i];

					if ( obj.isMatch( variation.attributes, attributes ) ) {
						matching.push( variation );
					}
				}
				return matching;
			},
			isMatch : ( variation_attributes, attributes ) => {
				var match = true;
				for ( var attr_name in variation_attributes ) {
					if ( variation_attributes.hasOwnProperty( attr_name ) ) {
						var val1 = variation_attributes[ attr_name ];
						var val2 = attributes[ attr_name ];
						if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
							match = false;
						}
					}
				}
				return match;
			},
			get : (url) => {
				var deferred = $q.defer();
				$http
					//.get(url, { ignoreLoadingBar: true })
					.get(url, { ignoreLoadingBar: true })
					.then((res) => {deferred.resolve(res)})
				return deferred.promise;
			},
			post : (url, data) => {
				var deferred = $q.defer();
				let config = {
					//ignoreLoadingBar: true,
					headers: {
	      				'Content-Type': 'application/x-www-form-urlencoded'
	    			}
				};
				$http
					.post(url, $httpParamSerializerJQLike(data), config)
					.then((res) => {
						deferred.resolve(res)
					})
					.catch((err)=> {
						deferred.reject(err);
					});
				return deferred.promise;
			},
			empty : ()=> {
				//$http.post(vars.wc.empty, { ignoreLoadingBar: true });
				$http.post(vars.wc.empty);
			},
		}
		return obj;
	}])
	.service('webFontLoader', ['angularLoad', '$window', (angularLoad, $window)=> {
		return (families)=> {
			$window.WebFontConfig = { google: {families : families}};
			angularLoad
				.loadScript('https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js');
		}
	}])