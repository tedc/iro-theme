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
					console.log(true);
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
				console.log(element[0].getBoundingClientRect().top);
					
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