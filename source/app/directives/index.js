var iro = angular.module('iro');
iro
	.directive('scroller', ['getInstances', (getInstances) => {
		var defaults = {};
		return {
			link : (scope, element, attrs) => {
				var opts = scope.$eval(attrs.options);
				var options = angular.extend({}, defaults, opts);
				var name = attrs.scroller || attrs.name || getInstances.generateName();
				// if(attrs.smooth) {
				// 	var s = Scrollbar.init(element[0], options);
				// 	Scrollbar.detachStyle();
				// } else {
				// 	var s = new Swiper(element[0], options);
				// }
				var s = new Swiper(element[0], options);
				var service = getInstances.createInstance(name, s);
				scope.$on('update_scroller', ()=> {
					s.update();
					//if(!attrs.smooth) s.updateSlides();
				})
				scope.$on('$destroy', ()=> {
					getInstances.destroyInstance(name);
				});
			}
		}
	}])
	.directive('ngSm', ['$rootScope', '$timeout', require('./sm.coffee')])
	.directive('ecommerce', require('./ecommerce'))
	.directive('wcAccount', require('./account'))
	.directive('ngPlayer', ['$rootScope', '$timeout', require('./player')])
	.directive('ngForm', require('./form'))
	.directive('moveTopImage', ()=> {
		return {
			restrict: 'A',
			scope : {
				top :'=moveTopImage'
			},
			link: (scope, element, attr)=> {
				TweenMax.set(element, {
					yPercent : scope.top
				});
			}
		}
	})
	.directive('onFinishRender', ['$timeout', ($timeout)=> {
		return {
			restrict : 'A',
			link : (scope, element, attr)=>{
				if(scope.$last === true) {
					$timeout(()=> {
						scope.$emit(attr.onFinishRender);
					})
				}
			}
		}
	}])
	.directive('ngMask', ()=>{
		return {
			scope : true,
			link : (scope, element)=>{
				element.find('img').one('load', ()=> {
					controller.update(true);
				});

				scope.moveMask = (evt, id, leave)=> {
					let body = document.body;
					let docEl = document.documentElement;
					var el = element[0].querySelector('svg');
					var rect = el.getBoundingClientRect();
					var size = {
						actual : rect,
						real : {
							width : el.querySelector('image').getAttribute('width'),
							height : el.querySelector('image').getAttribute('height')
						}
					};
					var cx = (size.real.width / 2);
					var cy = (size.real.height / 2);
						
					var wRatio = size.real.width / size.actual.width ;
					var hRatio = size.real.height / size.actual.height;
					// For scrollX
					let scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
					let scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;
					var startX = rect.left + scrollLeft;
					var startY = element[0].getBoundingClientRect().top + scrollTop;
					var moveX = evt.pageX;
					var moveY = evt.pageY;
					if(!leave) {
						TweenMax.to( `${id} .mask__circle`, 
							.5,
							{
								x : ~~((moveX - startX) * wRatio) - cx,
								y : ~~((moveY - startY) * hRatio) - cy
							}
						);
					} else {
						TweenMax.to( `${id} .mask__circle`, 
							.5,
							{
								x : 0,
								y : 0
							}
						);
					}
				}
			}
		}
	})
	.directive('ngInstagram', ()=> {
		return {
			restrict: 'A',
			controller : [ '$scope', "$http", '$timeout', 'getInstances', '$rootScope', ($scope, $http, $timeout, getInstances, $rootScope)=>{
				$scope.resize = (url)=>{
					return url.replace('150x150/', '640x640/')
				}
				let regex = new RegExp(`(${vars.main.langs})$`, "g");
	            let new_base = vars.main.base.replace(/\/$/g, '').replace(regex, '');
	            let url = `${new_base}/wp-json/api/v1/instagram`;
        		// $scope.renderSlide = (slide, index)=> {
        		// 	let html = `<li class="instagram__item instagram__item--cell-s3 swiper-slide">${slide}</li>`;
        		// 	console.log(html);
        		// 	return html;
        		// }
        		var instagram_slider = getInstances.getInstance('instagram');

          		instagram_slider.then( (swiper)=> {
            		$scope.$on('updateSwiper', ()=> {
        				swiper.update();
        			});
				} );
        		$http
	            	.get(url)
	            	.then( (res)=> {
	            		let data = res.data.data;
		            	if(data.length < 1) return;
		            	$scope.items = data;
		            	$scope.username = $scope.items[0].user.username;
		          
	            	});
			}]
		}
	})

	.directive('ngFacebook', ()=> {
		return {
			restrict: 'A',
			controller : [ '$scope', "$http", '$timeout', 'getInstances', '$rootScope', ($scope, $http, $timeout, getInstances, $rootScope)=>{
				let regex = new RegExp(`(${vars.main.langs})$`, "g");
	            let new_base = vars.main.base.replace(/\/$/g, '').replace(regex, '');
	            let url = `${new_base}/wp-json/api/v1/facebook`;
        		// $scope.renderSlide = (slide, index)=> {
        		// 	let html = `<li class="instagram__item instagram__item--cell-s3 swiper-slide">${slide}</li>`;
        		// 	console.log(html);
        		// 	return html;
        		// }
        		var fb_slider = getInstances.getInstance('facebook');

          		fb_slider.then( (swiper)=> {
            		$scope.$on('updateSwiper', ()=> {
        				swiper.update();
        				swiper.scrollbar.updateSize();
        			});
				} );
        		$http
	            	.get(url)
	            	.then( (res)=> {
	            		let data = res.data;
		            	if(data.length < 1) return;
		            	$scope.fb = data;
		            	let cut = (value, word, max, ellipsis)=> {
							if(!value) return '';
							max = parseInt(max, 10);
							if(!max) return value;
							if(value.length <= max) return value;
							value = value.substr(0, max);
							if(word) {
								let lastspace = value.lastIndexOf(' ');
								if(lastspace !== -1) {
									value = value.substr(0, lastspace);
								}
							}
							let excerpt =  `${value}${(ellipsis || '...')}`;
							return excerpt;
				        }
		            	$scope.convertHtml = (str)=> {
		            		str = cut(str, true, 240);
		            		let re = /(?![^<]*>|[^<>]*<\/)((http:|https:)\/\/[a-zA-Z0-9&#=.\/\-?_]+)/gi;
		            		let subst = '<a href="$1" target="_blank">$1</a>';
		            		let subhash = '$1<a href="https://www.facebook.com/hashtag/$3" target="_blank">$2$3</a>';
		            		str = str.replace(re, subst);
		            		let newLine = /[\n\r]/g;
		            		let hashTag = /(^|\s)(#)([a-z\d-]+)/gi;
		            		str = str.replace(newLine, '<br/>');
		            		str = str.replace(hashTag, subhash);
		            		return str;
		            	}
	            	});
			}]
		}
	})
	.directive('review', require('./review'))
	.directive('loginForm', require('./login'))