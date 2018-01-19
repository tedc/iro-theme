module.exports = () => {
	return {
		controller : ['$scope', '$rootScope', 'ngCart', '$element', 'ecommerce', '$state', 'getInstances', '$timeout', '$window', '$filter', '$compile', ($scope, $rootScope, ngCart, $element, ecommerce, $state, getInstances, $timeout, $window, $filter, $compile)=> {
			// CART
			$rootScope.initEcommerce = ()=>{
				// EMPTY ON LOAD
				if(ngCart.isEmpty()) ecommerce.empty();
				
				// ADD AND UPDATE
				$scope.singleProductVariation = {}
				$scope.attributes = [];
				$scope.product = {};
				ngCart.isUpdating = false;
				$scope.getVariation = () => {
					$scope.attributes = ecommerce.findMatchingVariations($scope.product.product_variations, $scope.singleProductVariation).shift();
					//$scope.product.price = `€ ${$scope.attributes.display_price}`;
					$scope.product.price = $filter('currency')($scope.attributes.display_price, '€ ', 2*($scope.attributes.display_price % 1 !== 0));
					$scope.product.product_id = $scope.attributes.variation_id;
				}
				var addToCart = (item)=> {
					ngCart.isUpdating = true;
					var data = {
						product_id : item.getId(),
						quantity : item.getQuantity()
					};
					if($scope.product.product_variations) {
						$scope.attributes = ecommerce.findMatchingVariations($scope.product.product_variations, $scope.singleProductVariation).shift();
						data = angular.extend({}, data, {variation_id : $scope.attributes.variation_id, variation : $scope.singleProductVariation});	
						var url = vars.wc.variation_add;
						let item_data = angular.extend({}, item.getData(), $scope.attributes);
						let price = $scope.attributes.display_price;
						item.setData(item_data);	
						item.setPrice(price);
					} else {
						var url = vars.wc.add;
					}
					if($scope.product.product_id) {
						if($scope.product.original_id !== $scope.product.product_id) {
							data = angular.extend({}, data, {product_id : $scope.product.original_id});
						}
					}
					ecommerce
						.post(url, data)
						.then( (res)=> {
							let item_data = angular.extend({}, item.getData(), {remove_item_url : res.data.remove_item_url, item_key: res.data.item_key, qty : item.getQuantity()});
							item.setData(item_data);
							$rootScope.$broadcast('ngCart:change');
							$state.go('tab', {name : 'cart'});
							ngCart.isUpdating = false;
						});
				}
				ngCart.changeQty = (item, inCart, plus)=> {
					if(ngCart.isUpdating) return;
					ngCart.isUpdating = true;
					let url = vars.wc.qty;
					var item_data = item.getData();
					var item_key = item_data.item_key;
					if(inCart) {
						item.setQuantity(plus, true);
					}
					var quantity = item.getQuantity();
					let data = {
						item_key : item_key,
						quantity : quantity
					}
					ecommerce
						.post(url, data)
						.then((res)=> {
							$rootScope.$broadcast('ngCart:change');
							$state.go('tab', {name : 'cart'});
							ngCart.isUpdating = false;	
						});
				}
				
				$scope.$on('ngCart:itemRemoved', (evt, item)=> {
					ngCart.isCounting = true;
					$timeout(()=> {
						ngCart.isCounting = false;
					}, 1500);
				});
				$scope.$on('ngCart:itemAdded', (evt, item)=> {
					ngCart.isCounting = true;
					$timeout(()=> {
						ngCart.isCounting = false;
					}, 1500);
					addToCart(item);
				});
				$scope.$on('ngCart:itemUpdated', (evt, item)=> {
					ngCart.isCounting = true;
					$timeout(()=> {
						ngCart.isCounting = false;
					}, 1500);
					ngCart.changeQty(item, false);
				});
	
				ngCart.getDesc = (item)=> {
					var item_data = item.getData();
					var desc = '';
					if(item_data.attributes.attribute_pa_misure) {
						desc += `${item_data.attributes.attribute_pa_misure.replace(/\-/g, ' ')} ${item_data.dimensions_html.replace(/\s+/g, '')}<br/>`;
					}
					if(item_data.attributes.attribute_pa_color) {
						desc += `${item_data.attributes.attribute_pa_color}`;
					}
					return desc;
				}
	
				// DELETE ITEM
	
				ngCart.delete = (url, item)=> {
					ecommerce
						.get(url)
						.then( ()=>{
							ngCart.removeItem(item);
						});
				}
	
				//LOGIN AND REGISTER AND POPUP
	
				ngCart.close = ()=> {
					let pages = ($rootScope.previousState.Name == 'tab') ? -2 : -1;
					$window.history.go(pages);
					//$window.history.back();
				}
	
				$scope.account = ($event)=> {
					$event.preventDefault();
					if($rootScope.isUserLoggedIn) {
						$state.go('app.page', {slug : vars.wc.accountBase});
					} else {
						$state.go('tab', {name : 'login'});
					}
				}
	
				// SHIPPING, COUPONS & CHECKOUT
				$scope.checkShippingAddress = false;
				$scope.shipping_method = [];
				$scope.post_data = {
					extra_shipping_option : []
				}
				$scope.checkoutFields = {
					terms : true,
					shipping_method : $scope.shipping_method,
					post_data : $scope.post_data,
					countries : JSON.parse(vars.wc.country_select_params.countries)
				}
				$scope.checkoutObj = {
					current : 0
				};
				// ngCart.extra = ()=> {
				// 	return store.
				// }
	
				// COUPONS
				ngCart.applyCoupon = (coupon, nonce)=> {
					ecommerce
						.post(vars.wc.coupons, {coupon_code : coupon, security : nonce})
						.then( (res) => {
							var discount = 0;
							var extras = ngCart.getExtras();
							for(coupon of res.data) {
								discount -= coupon.price;
							}
							extras = angular.extend({}, extras, {discount : discount, coupons : res.data});
							ngCart.setExtras(extras);
							$rootScope.$broadcast('ngCart:change');
						})
				}
	
				ngCart.getCouponAumount = (coupon)=> {
					if(coupon.price <= 0) return;
					if(coupon.type == 'percent') {
						return `-${coupon.amount}%`;
					} else {
						return `-€ ${coupon.amount}`;
					}
				}
	
				ngCart.deleteCoupon = (remove, idx)=> {
					var extras = ngCart.getExtras();
					if(!extras) return;
					var coupons = extras.coupons;
					extras.discount += coupons[idx].price;
					coupons.splice(idx, 1);
					extras.coupons = coupons;
					ecommerce
						.removeItem(remove)
						.then( ()=> {
							ngCart.setExtras(extras);
							$rootScope.$broadcast('ngCart:change');
						});
				}
				$scope.getCountries = (model)=> {
					var selected = $scope.checkoutFields.countries[model];
					if(angular.isArray(selected)) {
						if(selected.length <= 0) {
							return false;
						} 
					}
					return selected;
				}
	
				$scope.updateShipping = (fn)=> {
					var model_prefix = ($scope.checkShippingAddress) ? 'shipping_' : 'billing_',
						calc_country = ($scope.checkoutFields.customer[model_prefix + 'country']) ? $scope.checkoutFields.customer[model_prefix + 'country'] : '',
						calc_city= ($scope.checkoutFields.customer[model_prefix + 'city']) ? $scope.checkoutFields.customer[model_prefix + 'city'] : '',
						calc_state = ($scope.checkoutFields.customer[model_prefix + 'state']) ? $scope.checkoutFields.customer[model_prefix + 'state'] : '',
						calc_postcode = ($scope.checkoutFields.customer[model_prefix + 'postcode']) ? $scope.checkoutFields.customer[model_prefix + 'postcode'] : '',
						shipping_data = {
							post_data : $scope.checkoutFields.post_data,
							shipping_method : $scope.checkoutFields.shipping_method,
							security : $scope.checkoutFields.shipping_security,
							calc_security : $scope.checkoutFields.shipping_calc_security,
							calc_shipping : $scope.checkoutFields.calc_shipping,
							calc_shipping_country : calc_country,
							calc_shipping_city : calc_city,
							calc_shipping_state : calc_state,
							calc_shipping_postcode : calc_postcode
						};
						
					ecommerce
						.post(vars.wc.shippings, shipping_data)
						.then( (res)=> {
							var extras = ngCart.getExtras();
							extras.shippings = res.data;
							ngCart.setExtras(extras);
							ngCart.setShipping(res.data.total);
							$rootScope.$broadcast('ngCart:change');
							$scope.shippings = ngCart.getExtras().shippings.packages;
							if(fn) {
								fn();
							}
						});
				}
	
				$scope.sendCheckout = (form)=> {
					//console.log($scope.checkoutFields);
					$scope.isOrdering = true;
					var data = {
						posted : {post_data : $scope.checkoutFields.post_data},			
						shipping_method : $scope.checkoutFields.shipping_method,
						payment_method : $scope.checkoutFields.payment_method,
						ship_to_different_address : $scope.checkShippingAddress ? 1 : 0,
						_wpnonce : $scope.checkoutFields.wpnonce,
						terms : $scope.checkoutFields.terms
					}
					data = angular.extend({}, data, $scope.checkoutFields.customer);
					ecommerce
						.post(vars.wc.checkout, data)
						.then( (res)=> {
							var result = res.data;
							if(result.result === 'success') {
								let redirect = ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) ? result.redirect : decodeURI(result.redirect);
								let pattern = new RegExp(`${vars.main.base}`);
								if(!pattern.test(redirect)) {
									$window.location = redirect;
								} else {
									var state = redirect.replace(vars.main.base + '/'+vars.wc.orderBase+'/', '');
									state = state.split('?key=');
									var order = state[0];
									var key = state[1];
									$state.go('app.order', {order : order, key : key});
								}
							} else if( 'failure' === result.result ){
								$scope.error = result.message;
								$state.go('app.page', {slug : checkoutPage}, {reload : true});
							} else {
								$scope.error = result.message;
								$state.go('app.page', {slug : checkoutPage}, {reload : true});
							}
							$scope.isOrdering = false;
						});
					//console.log(form);
				}
				// ngCart.$cart = angular.extend({}, ngCart.$cart, {coupons : []});
				// console.log(ngCart.$cart);
				$scope.checkout_slider = getInstances.getInstance('checkout');
				$scope.checkout_slider.then((swiper) => {
					if(swiper.destroyed) return;
					$scope.slideTo = (cond, index)=> {
						if(!cond) return;
						swiper.slideTo(index);
					}
					$scope.next = (cond)=> {
						if(!cond) return;
						$scope.updateShipping(()=> {
							swiper.slideNext();
						})
						
					}
					var getCurrentIndex = ()=> {
						$scope.checkoutObj.current = swiper.realIndex;
					}
					swiper.on('slideChange', getCurrentIndex);
					swiper.on('init', getCurrentIndex);
				});
				$scope.product_slider = getInstances.getInstance('product');
				$scope.product_slider.then((swiper) => {
					if(swiper.destroyed) return;
					$scope.productSlideTo = (index)=> {
						swiper.slideTo(index);
					}
					swiper.on('slideChange', ()=> {
						$scope.currentProductSlide = swiper.activeIndex;
					})	
				});

				$scope.values_slider = getInstances.getInstance('values');
				$scope.values_slider.then((swiper)=> {
					if(swiper.destroyed) return;
					let currentClass = ()=> {
						angular.element(swiper.slides[swiper.activeIndex]).addClass('swiper-slide-current')
					}
					swiper.on('init', currentClass);
					swiper.init();
					swiper.on('slideChangeTransitionStart', (evt)=> {
						angular.element(swiper.$el.find('.swiper-slide-current')).removeClass('swiper-slide-current')
					});
					swiper.on('slideChangeTransitionEnd', currentClass);
				})
			}
			$rootScope.initEcommerce();
		}]
	}
}