module.exports = (getInstances, screenSize)=> {
	return {
		scope : true,
		link : (scope, element, attrs)=> {
			screenSize.rules = {
				min : "screen and (min-width: #{(850/16)}em)"
			}
			if(attrs.ngLayers == 'slider') {
				let layers_slider = getInstances.getInstance('layers');
				layers_slider.then((swiper)=> {
					swiper.on('slideChangeTransitionEnd', ()=> {
						let index = element[0].querySelector(`[data-swiper-slide-index="${swiper.realIndex}"]`) ? `[data-swiper-slide-index="${swiper.realIndex}"]` : '.swiper-slide-active';
						var currentLayers = element[0].querySelector(index).getAttribute('data-layer-to').replace(/\s/g, '').split(',');
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
						let index = element[0].querySelector(`[data-swiper-slide-index="0"]`) ? '[data-swiper-slide-index="0"]' : '.swiper-slide-active';
						var currentLayers = element[0].querySelector(index).getAttribute('data-layer-to').replace(/\s/g, '').split(',');
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
						if(!screenSize.is('min')) return;
						//if(!element.hasClass('layers--inview')) return;
						TweenMax.set(element[0].querySelectorAll('[data-layer]'), {className : '+=animate'});
						TweenMax.to([element[0].querySelectorAll('[data-layer]'),element[0].querySelectorAll('[data-layer-to]')], .5, {
							opacity: .35
						});
						if(layers == 'cover') {
							angular.element(element[0].querySelectorAll('[data-layer-to="cover"]')).addClass('layer-active');			
							TweenMax.to('.layers__stop', .5, {
								attr : {
									offset : 1
								}
							});
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
								angular.element(element[0].querySelectorAll(`[data-layer-to*="${i}"]`)).addClass('layer-active');
								TweenMax.to(element[0].querySelectorAll(`[data-layer*="${i}"]`), .5, {
									opacity: 1
								});
							}
						}
					});
					angular.element(item).on('mouseleave', ()=> {
						if(!screenSize.is('min')) return;
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
						angular.element(element[0].querySelectorAll('.layer-active')).removeClass('layer-active');
						TweenMax.to([element[0].querySelectorAll('[data-layer]'),element[0].querySelectorAll('[data-layer-to]')], .5, {
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