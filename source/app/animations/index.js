import {speed} from '../main';
var iro = angular.module('iro');
iro
	.animation('.slide-toggle', ['$rootScope', ($rootScope)=> {
		let anim = {
			addClass: function(element, className, done) {
				if(className!=='slide-toggle--visible') return;
				element.addClass(className);
				var height = element[0].offsetHeight;
				TweenMax.fromTo(element, speed, {
					opacity: 0,
					height : 0
				}, {
					height : height,
					opacity : 1,
					onUpdate : ()=> {	
						$rootScope.$broadcast('update_scroller');
					},
					onComplete : function() {
						$rootScope.$broadcast('update_scroller');
						TweenMax.set(element, {
							clearProps : 'height'
						});
						done();
					}
				}
				)
			},
			removeClass : function(element, className, done) {
				if(className!=='slide-toggle--visible') return;
				element.addClass(className);
				
				TweenMax.to(element, speed, {
					height : 0,
					opacity : 0,
					onUpdate : ()=> {	
						$rootScope.$broadcast('update_scroller');
					},
					onComplete : function() {
						TweenMax.set(element, {
							clearProps : 'height'
						});
						element.removeClass(className);
						$rootScope.$broadcast('update_scroller');
						done();
					}
				}
				)
			}
		}
		return anim;
	}])
	.animation('.checkout__cell--slider', ['$rootScope', ($rootScope)=> {
		var events = 'animationend webkitAnimationEnd MSAnimationEnd' + ' transitionend webkitTransitionEnd';
		return {
			addClass : (element, className, done)=> {
				TweenMax.to({index : 0}, .5, {
					index : 10,
					onUpdate : ()=> {
						$rootScope.$broadcast('update_scroller');
					},
					onComplete : ()=> {
						done();
					}
				});
				// element.on(events, ()=> {
				// 	$rootScope.$broadcast('update_scroller');
				// });
			},
			removeClass : (element, className, done)=> {
				TweenMax.to({index : 0}, .5, {
					index : 10,
					onUpdate : ()=> {
						$rootScope.$broadcast('update_scroller');
					},
					onComplete : ()=> {
						done();
					}
				});
			}
		}
	}])
	.animation('.product__gallery', ['$rootScope', ($rootScope)=> {
		return {
			addClass : (element, className, done)=> {
				if(className != 'product__gallery--visible') return;
				TweenMax.set(element, {
					visibility : 'hidden',
					opacity : 0,
					display : 'block',
					zIndex : 2,
					position : 'absolute'
				});
				TweenMax.to(element, .5, {
					visibility : 'visible',
					opacity : 1,
					onComplete : ()=> {
						$rootScope.$broadcast('update_scroller');
						TweenMax.set(element, {
							clearProps : 'all'
						});
						done();
					}
				});
			},
			removeClass : (element, className, done)=> {
				if(className != 'product__gallery--visible') return;
				TweenMax.set(element, {
					display : 'block',
					zIndex : 1
				});
				TweenMax.to(element, .5, {
					visibility : 'hidden',
					opacity : 0,
					onComplete : ()=> {
						$rootScope.$broadcast('update_scroller');
						TweenMax.set(element, {
							clearProps : 'all'
						});
						done();
					}
				});
			}
		}
	}]);