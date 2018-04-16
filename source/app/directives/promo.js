module.exports = ($timeout)=> {
	return {
		link: (scope, element, attrs)=> {
			let fn = ()=> {
				let h = element[0].querySelector('.promo__wrapper').offsetHeight;
				let p = (h/16) + (80/16);
				TweenMax.set('.banner', {
					top : `${(h/16)}em`
				})
				TweenMax.set('body', {
					paddingTop : `${p}em`
				});
			}
			fn();
			angular.element(window).on('resize', fn);
		}
	}
}