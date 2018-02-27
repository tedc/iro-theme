module.exports = (getInstances)=> {
	return {
		scope : true,
		link : (scope, element, attrs)=> {
			if(attrs.ngLayers == 'slider') {
				let layers_slider = getInstances.getInstance('layers');
				layers_slider.then((swiper)=> {
					swiper.on('slideChangeTransitionEnd', ()=> {
						var currentLayers = element[0].querySelector(`[data-swiper-slide-index="${swiper.realIndex}"]`).getAttribute('data-layer-to').replace(/\s/g, '').split(',');
						for(let i = 0; i < currentLayers.length; i++) {
							angular.forEach(element[0].querySelectorAll(`[data-layer="${currentLayers[i]}"]`), (el, index)=>{
								angular.element(el).addClass('section__layer--visible');
							});
						}
						for(let i = 0; i < parseInt(currentLayers[0]); i++) {
							angular.forEach(element[0].querySelectorAll(`[data-layer="${i}"]`), (el, index)=>{
								angular.element(el).addClass('section__layer--visible');
							});
						}
						for(var c = (parseInt(currentLayers[currentLayers.length - 1]) + 1); c < 7; c++ ) {
							angular.forEach(element[0].querySelectorAll(`[data-layer="${c}"]`), (el, index)=>{
								angular.element(el).removeClass('section__layer--visible');
							});
						}
					});
					swiper.on('init', ()=> {
						var currentLayers = element[0].querySelector(`[data-swiper-slide-index="0"]`).getAttribute('data-layer-to').replace(/\s/g, '').split(',');
						for(let i = 0; i < currentLayers.length; i++) {
							angular.forEach(element[0].querySelectorAll(`[data-layer="${currentLayers[i]}"]`), (el, index)=>{
								angular.element(el).addClass('section__layer--visible');
							});
						}
					});
					swiper.init();
				});
			} else {
				var layers = element[0].querySelectorAll('[data-layer-to]');
				angular.forEach( layers, function(item, index) {
					let layers = item.getAttribute('data-layer-to').replace(/\s/g, '').split(',');
					angular.element(item).on('mouseenter', ()=> {
						if(!element.hasClass('layers--inview')) return;
						TweenMax.set(element[0].querySelectorAll('[data-layer]'), {className : '+=animate'});
						TweenMax.to([element[0].querySelectorAll('[data-layer]'),element[0].querySelectorAll('[data-line]')], .5, {
							opacity: .35
						});
						if(layers == 'cover') {
							angular.element(element[0].querySelectorAll('[data-line="cover"]')).addClass('layers__line--active');
								
							TweenMax.to('.layers__stop', .5, {
								attr : {
									offset : 1
								}
							});
							// TweenMax.to(element[0].querySelectorAll('[data-layer*="cover"]'), .5, {
							// 	opacity: 1
							// });
						} else {
							TweenMax.to('#stop_1', .5, {
								attr : {
									offset : 0.03
								}
							});
							TweenMax.to('#stop_2', .5, {
								attr : {
									offset : 0.1
								}
							});
							
							for(let i of layers) {
							// let x = 0;
							// if(i == 1) {
							// 	x = -60;
							// } else if(i == 2) {
							// 	x = 60;
							// }
								// TweenMax.to(element[0].querySelectorAll('[data-layer*="cover"]'), .5, {
								// 	opacity: 0
								// });
								angular.element(element[0].querySelectorAll(`[data-line*="${i}"]`)).addClass('layers__line--active');
								TweenMax.to(element[0].querySelectorAll(`[data-layer*="${i}"]`), .5, {
									// y: (parseInt(i) != 0) ? -40 : 0,
									// x: x,
									opacity: 1
								});
							}
						}
						// for(let i of layers) {
						// 	// let x = 0;
						// 	// if(i == 1) {
						// 	// 	x = -60;
						// 	// } else if(i == 2) {
						// 	// 	x = 60;
						// 	// }
						// 	TweenMax.set(element[0].querySelectorAll('[data-layer]'), {className : '+=animate'});
						// 	TweenMax.to(element[0].querySelectorAll(`[data-layer*="${i}"]`), .5, {
						// 		// y: (parseInt(i) != 0) ? -40 : 0,
						// 		// x: x,
						// 		opacity: 1
						// 	});
						// }
						// for(let i = (parseInt(layers[layers.length - 1]) + 1); i < 7; i++ ) {
						// 	TweenMax.to(element[0].querySelectorAll(`[data-layer="${i}"]`), .5, {
						// 		// y: -40,
						// 		// x: 0,
						// 		opacity: 0
						// 	});
						// }
						// for(let i = 0; i < parseInt(layers[0]); i++) {
						// 	TweenMax.to(element[0].querySelectorAll(`[data-layer="${i}"]`), .5, {
						// 		opacity: 0.25,
						// 		// x : 0
						// 	});
						// }
					});
					angular.element(item).on('mouseleave', ()=> {
						TweenMax.to('#stop_1', .5, {
							attr : {
								offset : 0.33
							}
						});
						TweenMax.to('#stop_2', .5, {
							attr : {
								offset : 0.5
							}
						});
						TweenMax.set(element[0].querySelectorAll('[data-layer]'), {className : '-=animate'});
						angular.element(element[0].querySelectorAll('.layers__line--active')).removeClass('layers__line--active');
						TweenMax.to([element[0].querySelectorAll('[data-layer]'),element[0].querySelectorAll('[data-line]')], .5, {
							// y: 0,
							// x: 0,
							opacity: 1
						});
						// TweenMax.to(element[0].querySelectorAll('[data-layer="cover-full"]'), .5, {
						// 	// y: 0,
						// 	// x: 0,
						// 	opacity: 0
						// });
					});
				});
			}
		}
	}
}