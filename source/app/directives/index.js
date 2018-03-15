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
				//scope.product_slider = getInstances.getInstance('product');
				//scope.product_slider.then((swiper) => {
				scope.currentProductSlide = 0;
				if(s.destroyed) return;
				let slidesTo = element[0].querySelectorAll('[data-slide-to]');
				if(slidesTo) {
					angular.forEach(element[0].querySelectorAll('[data-slide-to]'), (item, i)=> {
						let index = item.getAttribute('data-slide-to');
						angular.element(item).on('click', ()=> {
							s.slideTo(index);
						});
					});
					s.on('slideChange', ()=> {
						angular.element(element[0].querySelector('.swiper-pagination-bullet-active')).removeClass('swiper-pagination-bullet-active');
						angular.element(element[0].querySelector('[data-slide-to="'+s.activeIndex+'"]')).addClass('swiper-pagination-bullet-active');
						//scope.currentProductSlide = s.activeIndex;
					});
				}
				scope.features_slider = getInstances.getInstance('features');
				scope.features_slider.then((swiper)=> {
					swiper.on('progress', ()=> {
						angular.forEach(swiper.slides, (item, i)=> {
							let p = (i%2==0) ? swiper.progress*-1 : swiper.progress;
							p = 40 * p;
							TweenMax.to(item, .5, {y : p});
						});
					})
				});
				// VALUES SLIDER
				scope.valueIsStart = true;
				scope.valueIsEnd = false;
				scope.values_slider = getInstances.getInstance('values');
				scope.values_slider.then((swiper)=> {
					if(swiper.destroyed) return;
					let currentClass = ()=> {
						swiper.update();
						angular.element(swiper.slides[swiper.activeIndex]).addClass('swiper-slide-current')
					}
					swiper.on('init', currentClass);
					swiper.init();
					swiper.on('slideChangeTransitionStart', (evt)=> {		
						angular.element(swiper.$el.find('.swiper-slide-current')).removeClass('swiper-slide-current')
					});
					swiper.on('progress', ()=> {
						scope.valueIsEnd = swiper.isEnd;
						scope.valueIsStart = swiper.isBeginning;
					});
					swiper.on('slideChangeTransitionEnd', currentClass);
					scope.valueMove = (cond)=> {
						if(cond) {
							swiper.slideNext();
						} else {
							swiper.slidePrev();
						}
					}
				});
				//});
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
			controller : [ '$scope', "$http", '$timeout', 'getInstances', '$rootScope', '$attrs', ($scope, $http, $timeout, getInstances, $rootScope, $attrs)=>{
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
				$scope.strings = $scope.$eval($attrs.strings);
				//$scope.num = (num)=> { return 1000*parseInt(num)};
				$scope.timeAgo = (time)=> {
					var msPerMinute = 60 * 1000;
				    var msPerHour = msPerMinute * 60;
				    var msPerDay = msPerHour * 24;
				    var msPerMonth = msPerDay * 30;
				    var msPerYear = msPerDay * 365;
				    var current = new Date();
				    var elapsed = current - (parseInt(time)*1000);
				    if (elapsed < msPerMinute) {
				    	let seconds = (Math.round(elapsed/1000) > 1) ? $scope.strings.s[1] : $scope.strings.s[0];
				        return Math.round(elapsed/1000) + ' ' +seconds+ ' ' + $scope.strings.ago;   
				    }
				    else if (elapsed < msPerHour) {
				        let minutes = (Math.round(elapsed/msPerMinute) > 1) ? $scope.strings.m[1] : $scope.strings.m[0];
				        return Math.round(elapsed/msPerMinute) + ' ' +minutes+ ' ' + $scope.strings.ago;    
				    }

				    else if (elapsed < msPerDay ) {
				         let hours = (Math.round(elapsed/msPerHour) > 1) ? $scope.strings.h[1] : $scope.strings.h[0];
				        return Math.round(elapsed/msPerHour ) + ' ' +hours+ ' ' + $scope.strings.ago;    
				    }
				    else if (elapsed < msPerMonth) {
				        let days = (Math.round(elapsed/msPerDay) > 1) ? $scope.strings.d[1] : $scope.strings.d[0];
				        return Math.round(elapsed/msPerDay) + ' ' +days+ ' ' + $scope.strings.ago;    
				    }
				    else if (elapsed < msPerYear) {
				        let months = (Math.round(elapsed/msPerMonth) > 1) ? $scope.strings.mo[1] : $scope.strings.mo[0];
				        return Math.round(elapsed/msPerMonth) + ' ' +months+ ' ' + $scope.strings.ago;    
				    }
				    else {
				        let years = (Math.round(elapsed/msPerYear) > 1) ? $scope.strings.y[1] : $scope.strings.y[0];
				        return Math.round(elapsed/msPerYear ) + ' ' +years+ ' ' + $scope.strings.ago;    
				    }
				}
				$http
	            	.get(url, { ignoreLoadingBar: true })
	            	.then( (res)=> {
	            		let data = res.data.data;
		            	if(data.length < 1) return;
		            	$scope.items = data;
		            	$scope.username = $scope.items[0].user.username;
		          		$scope.userpicture = $scope.items[0].user.profile_picture;
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
	            	.get(url, { ignoreLoadingBar: true })
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
	.directive('goTo', ()=> {
		return {
			link: (scope, element, attr)=> {
				let pos = attr.goTo;
				let top = document.querySelector('.banner').offsetHeight;
				window.controller.scrollTo( (newpos)=>{
					TweenMax.to(window, 0.5, {scrollTo: {y: newpos - top}});
				});
				element.on('click', ()=> {
					window.controller.scrollTo(pos);
				});
			}
		}
	})
	.directive('ratingPercentage', ()=> {
		return {
			link : (scope, element, attr)=> {
				let value = scope.$eval(attr.ratingPercentage);
				TweenMax.set(element, {width : `${value}%`});
			}
		}
	})
	.directive('ngVideo', ['$rootScope', '$timeout', require('./video')])
	.directive('review', require('./review'))
	.directive('loginForm', require('./login'))
	.directive('ngLayers', ['getInstances', '$animate', require('./layers')])