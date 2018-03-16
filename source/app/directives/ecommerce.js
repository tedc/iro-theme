module.exports = () => {
	return {
		controller : ['$scope', '$rootScope', 'ngCart', '$element', 'ecommerce', '$state', 'getInstances', '$timeout', '$window', '$filter', '$location', '$sce', ($scope, $rootScope, ngCart, $element, ecommerce, $state, getInstances, $timeout, $window, $filter, $location, $sce)=> {
			// CART
			$rootScope.isCartChanged = false;
			let errorAlert = $element[0].querySelector('ul.woocommerce-error');
			if(errorAlert) {
				errorAlert = angular.element(errorAlert);
				TweenMax.to(errorAlert, .5, {
					opacity : 0,
					delay : 5,
					onComplete : ()=> {
						errorAlert.remove();
					}
				});
			}

			$rootScope.initEcommerce = ()=>{
				//EMPTY ON LOAD
				if(ngCart.isEmpty()){ 
					ecommerce.empty();
					ngCart.setExtras({});
					$rootScope.$broadcast('ngCart:change');
				} else {
					let url = vars.wc.cart;
					ecommerce
						.get(url)
						.then((res)=> {
							let data = res.data;
							if(data.cart_empty == true) {
								ngCart.empty();
							} else {
								for(let i = 0; i < data.products.length; i++) {
									let pId = (data.products[i].variation_id > 0) ? data.products[i].variation_id : data.products[i].product_id;
									pId = pId.toString();
									let item = ngCart.getItemById(pId);
									if(!ngCart.getItemById(pId)) {
										ngCart.removeItem(i);
									} else {
										let quantity = data.products[i].quantity;
										let price = data.products[i].line_subtotal / data.products[i].quantity;
										let item_data = item.getData();
										if(item_data.attributes != data.products[i].variation_details.attributes) {
											item_data.attributes = data.products[i].variation_details.attributes;
										}
										if(item_data.dimensions_html != data.products[i].variation_details.dimensions_html) {
											item_data.dimensions_html = data.products[i].variation_details.dimensions_html;
										}
										item.setData(item_data);
										if(price != item.getPrice()){
											item.setPrice(price);
										}
										if(quantity != item.getQuantity()) {
											item.setQuantity(quantity);
										}
										$rootScope.isCartChanged = true;
										$rootScope.$broadcast('ngCart:change');
									}
								}
							}
						})
				}

				$scope.$on('ngCart:change', ()=> {
					if(ngCart.isEmpty()) {
						ngCart.setExtras({});
					}
				});
				
				// ADD AND UPDATE
				$scope.singleProductVariation = {}
				// console.log($rootScope.sizeSelected);
				$scope.attributes = [];
				$scope.product = {};
				ngCart.isUpdating = false;
				$scope.variationPrices = [];
				$scope.getVariation = () => {
					$scope.attributes = ecommerce.findMatchingVariations($scope.product.product_variations, $scope.singleProductVariation).shift();
					//$scope.product.price = `€ ${$scope.attributes.display_price}`;
					$scope.product.price = $filter('currency')($scope.attributes.display_price, '€ ', 2*($scope.attributes.display_price % 1 !== 0));
					$scope.product.product_id = $scope.attributes.variation_id;
					$window.dataLayer.push({
					  'event': 'actionField',
					  'ecommerce': {
					  	'actionField': {'list': 'Variation'},    // 'detail' actions have an optional list property.
					    'detail': {                                // 'add' actionFieldObject measures.
					      'products': [{                        //  adding a product to a shopping cart.
					        'name': $scope.attributes.title + ' - ' + $scope.attributes.attributes.attribute_pa_color + ' ' +$scope.attributes.attributes.attribute_pa_misure,
					        'id': ($scope.attributes.sku) ? $scope.attributes.sku : $scope.attributes.variation_id,
					        'price': $scope.attributes.display_price,
					        'brand': 'Iro',
					        'variant': $scope.attributes.attributes.attribute_pa_color + ' ' +$scope.attributes.attributes.attribute_pa_misure,
					       }]
					    }
					  }
					});
				}
				$scope.variationPrice = (i)=> {
					return $scope.variationPrices[i][$scope.singleProductVariation.attribute_pa_color];
				}
				$scope.variationValue = (n, i)=> {
					return n + '<em>' + $scope.variationPrice(i) + '</em>';
				}
				$scope.inLineHtml = (string)=> {
					return $sce.trustAsHtml(string);
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
						item_data = angular.extend({}, item_data, {variation : data.variation});
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
					} else {
						if($scope.product.original_id !== data.product_id) {
							data = angular.extend({}, data, {product_id : $scope.product.original_id});
						}
					}
					ecommerce
						.post(url, data)
						.then( (res)=> {
							let item_data = angular.extend({}, item.getData(), {remove_item_url : res.data.remove_item_url, item_key: res.data.item_key, qty : item.getQuantity()});
							item.setData(item_data);
							$window.dataLayer.push({
							  'event': 'addToCart',
							  'ecommerce': {
							    'currencyCode': 'EUR',
							    'add': {                                // 'add' actionFieldObject measures.
							      'products': [{                        //  adding a product to a shopping cart.
							        'name': item.getName() + ' - ' +item.getData().attributes.attribute_pa_color + ' ' +item.getData().attributes.attribute_pa_misure,
							        'id': (item_data.sku) ? item_data.sku : item.getId(),
							        'price': item.getPrice(),
							        'brand': 'Iro',
							        'variant': item.getData().attributes.attribute_pa_color + ' ' +item.getData().attributes.attribute_pa_misure,
							        'quantity': item.getQuantity()
							       }]
							    }
							  }
							});
							$rootScope.$broadcast('ngCart:change');
							ngCart.isUpdating = false;
							$location.hash('cart');
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
							$location.hash('cart');
							ngCart.isUpdating = false;	
							$window.dataLayer.push({
							  'event': 'addToCart',
							  'ecommerce': {
							    'currencyCode': 'EUR',
							    'add': {                                // 'add' actionFieldObject measures.
							      'products': [{                        //  adding a product to a shopping cart.
							        'name': item.getName(),
							        'id': item.getId(),
							        'price': item.getPrice(),
							        'brand': 'Iro',
							        'variant': item.getData().attributes.attribute_pa_color + ' ' +item.getData().attributes.attribute_pa_misure,
							        'quantity': item.getQuantity()
							       }]
							    }
							  }
							});
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
					console.log('updated');
					ngCart.isCounting = true;
					$timeout(()=> {
						ngCart.isCounting = false;
					}, 1500);
					ngCart.changeQty(item, false);
				});
	
				ngCart.getDesc = (item)=> {
					var item_data = item.getData();
					var desc = '';
					if(item_data.attributes) {
						if(item_data.attributes.attribute_pa_misure) {
							//desc += `${item_data.attributes.attribute_pa_misure.replace(/\-/g, ' ')} ${item_data.dimensions_html.replace(/\s+/g, '')}<br/>`;
							desc += `${item_data.attributes.attribute_pa_misure.replace(/\-/g, ' ')}<br/>`;
						}
					}
					if(item_data.attributes && item_data.attributes.attribute_pa_color) {
						desc += `${item_data.attributes.attribute_pa_color}`;
					}
					return desc;
				}
	
				// DELETE ITEM
	
				ngCart.delete = (item_key, item, id)=> {
					ngCart.isCounting = true;
					let url = vars.wc.remove + '&item_key=' + item_key;
					ecommerce
						.get(url)
						.then( (res)=>{
							let layer_item = ngCart.getItemById(id);
							$window.dataLayer.push({
							  'event': 'removeFromCart',
							  'ecommerce': {
							    'remove': {                                // 'add' actionFieldObject measures.
							      'products': [{                        //  adding a product to a shopping cart.
							        'name': layer_item.getName(),
							        'id': layer_item.getId(),
							        'price': layer_item.getPrice(),
							        'brand': 'Iro',
							        'variant': layer_item.getData().attributes.attribute_pa_color + ' ' +layer_item.getData().attributes.attribute_pa_misure,
							        'quantity': layer_item.getQuantity()
							       }]
							    }
							  }
							});
							ngCart.removeItem(item);
							ngCart.isCounting = true;
						});
				}
	
				//LOGIN AND REGISTER AND POPUP
	
				ngCart.close = ()=> {
					if( $window.history && $window.history.pushState ) {
						history.pushState('', document.title, $location.path());
					} else {
						$location.hash('');
					}
				}

				ngCart.logged = $rootScope.isUserLoggedIn;
				$rootScope.$watch('isUserLoggedIn', (newValue, oldValue)=> {
					if(newValue != oldValue) {
						ngCart.logged = $rootScope.isUserLoggedIn;
					}
				})
	
				$scope.account = ($event)=> {
					if($rootScope.isUserLoggedIn) {
						$event.preventDefault();
						$state.go('app.page', {slug : vars.wc.accountBase});
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
					countries : JSON.parse(vars.wc.country_select_params.countries),
					billing_state_value : false,
					billing_country_value : false,
					shipping_state_value : false,
					shipping_country_value : false,
				}
				$scope.checkoutObj = {
					current : 0
				};
				// ngCart.extra = ()=> {
				// 	return store.
				// }
	
				// COUPONS
				ngCart.applyCoupon = (coupon, nonce, isCheckout)=> {
					if(ngCart.couponError) delete ngCart.couponError;
					if(ngCart.isDescountLoading) return;
					ngCart.isDescountLoading = true;
					ecommerce
						.post(vars.wc.coupons, {coupon_code : coupon, security : nonce})
						.then( (res) => {
							ngCart.isDescountLoading = false;
							if(res.data.error) {
								ngCart.couponError = res.data.error;
								return;
							}
							// var extras = ngCart.getExtras();
							// extras.shippings = res.data;
							// ngCart.setExtras(extras);
							// ngCart.setShipping(res.data.total);
							// $rootScope.$broadcast('ngCart:change');
							// $scope.shippings = ngCart.getExtras().shippings.packages;
							var discounts = [];
							var extras = ngCart.getExtras();
							// for(coupon of res.data) {
							// 	discounts.push({
							// 		type : coupon.type,
							// 		price : coupon.price
							// 	})
							// }
							//extras = angular.extend({}, extras, {discounts : discounts, coupons : res.data});
							extras = angular.extend({}, extras, {coupons : res.data});
							if(isCheckout) $scope.updateShipping(false);
							ngCart.setExtras(extras);
							$rootScope.$broadcast('ngCart:change');
						})
				}

				ngCart.getDiscountTotal = (total)=> {
					let coupons = ngCart.getExtras().coupons;
					if(coupons && coupons.length > 0){
						for(let discount of coupons) {
							if(discount.type == 'percent') {
								total = total * discount.price;
							} else {
								total -= discount.price;
							}
						}
					}
					return total;
				}
	
				ngCart.getCouponAumount = (coupon)=> {
					if(coupon.price <= 0) return;
					if(coupon.type == 'percent') {
						return `-${coupon.price * 100}%`;
					} else if(coupon.type == 'fixed_product') {
						return `-€ ${coupon.price} <span>(€ ${coupon.amount} ${vars.wc.fixed_product_coupon})</span>`;
					} else  {
						return `-€ ${coupon.price}`;
					}
				}
	
				ngCart.deleteCoupon = (remove, idx)=> {
					if(ngCart.isCounting) return;
					ngCart.isCounting = true;
					var extras = ngCart.getExtras();
					if(!extras) {
						ngCart.isCounting = false;
						return;
					}
					ecommerce
						.get(remove)
						.then( ()=> {
							let coupons = extras.coupons;
							coupons.splice(idx, 1);
							extras.coupons = coupons;
							if(extras.coupons.length == 0) {
								delete extras.coupons;
							}
							ngCart.setExtras(extras);
							ngCart.isCounting = false;
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
				$scope.isCheckoutUpdating = false;
				$scope.updateShipping = (fn)=> {
					$scope.isCheckoutUpdating = true;
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
								$timeout(fn);
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
					let coupon = '';
					angular.forEach( ngCart.getExtras().coupons, (element, index)=> {
						coupon = element.code;
					});
					let products = [];
					angular.forEach(ngCart.getCart().items, (item, i)=> {
						obj = {
							'name' : item.getName(),
							'id' : item.getData().sku ? item.getData().sku : item.getId(),
							'price' : item.getPrice(),
							'brand' : 'Iro',
							'variant': item.getData().attributes.attribute_pa_color + ' ' +item.getData().attributes.attribute_pa_misure,
						    'quantity': item.getQuantity()
						}
						products.push(obj);
					});
					let purchase = {
						action : {
							'affiliation' : 'Online Store',
							'revenue' : ngCart.getDiscountTotal(ngCart.totalCost()),
							'shipping' : ngCart.getShipping(),
							'tax' : ngCart.getTax(),
							'coupon' : coupon
						},
						products : products
					};
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
									order.replace(/\//g, '');
									console.log(order);
									var key = state[1];
									purchase.action['id'] = order;
									$window.dataLayer.push({
										'event' : 'purchase',
										'ecommerce': {
											'purchase': purchase
										}
									});
									$state.go('app.order', {order : order, key : key});
								}
							} else if( 'failure' === result.result ){
								$scope.error = result.message;
								$state.go('app.page', {slug : vars.wc.checkoutPage}, {reload : true});
							} else {
								$scope.error = result.message;
								$state.go('app.page', {slug : vars.wc.checkoutPage}, {reload : true});
							}
							$scope.isOrdering = false;
						});
					//console.log(form);
				}
				// ngCart.$cart = angular.extend({}, ngCart.$cart, {coupons : []});
				// console.log(ngCart.$cart);
				$scope.isConfirm = false;
				$scope.checkout_slider = getInstances.getInstance('checkout');
				$scope.checkout_slider.then((swiper) => {
					if(swiper.destroyed) return;
					$scope.slideTo = (cond, max, idx)=> {
						if(!cond) return;
						$scope.updateShipping(()=> {
							if($scope.checkoutObj.current + 1 > max && typeof idx == 'undefined') {
								$scope.isConfirm = true;
								$scope.isCheckoutUpdating = false;
								return;
							}
							$scope.isConfirm = false;
							if(typeof idx !== 'undefined') {
								swiper.slideTo(idx);	
							} else {
								swiper.slideNext();
							}
							$scope.isCheckoutUpdating = false;
						});	
					}
					$scope.next = (cond, max)=> {
						$scope.slideTo(cond, max);
						if(!cond) return;
					}
					var getCurrentIndex = ()=> {
						$scope.checkoutObj.current = swiper.realIndex;
						if($scope.checkoutObj.current > 0) {
							let option;
							if($scope.checkoutObj.current - 1 == 0) {
								option = 'account';
							} else if($scope.checkoutObj.current - 1 == 1) {
								option = `spedizione: ${$scope.checkoutFields.shipping_method}`
							} else if($scope.checkoutObj.current - 1 == 2) {
								option = `purchase: ${$scope.checkoutFields.payment_method}`
							}
							let products = [];
							angular.forEach(ngCart.getCart().items, (item, i)=> {
								obj = {
									'name' : item.getName(),
									'id' : item.getData().sku ? item.getData().sku : item.getId(),
									'price' : item.getPrice(),
									'brand' : 'Iro',
									'variant': item.getData().attributes.attribute_pa_color + ' ' +item.getData().attributes.attribute_pa_misure,
								    'quantity': item.getQuantity()
								}
								products.push(obj);
								console.log(products);
							});
							$window.dataLayer.push({
							    'event': 'checkout',
							    'ecommerce': {
							      'checkout': {
							        'actionField': {'step': $scope.checkoutObj.current - 1, 'option': option},
							        'products': products
							     }
							   }
							});
						}
					}
					swiper.on('slideChange', getCurrentIndex);
					swiper.on('init', getCurrentIndex);
				});

				$scope.checkShippingCoupon = (v, c)=> {
					let cond = true;
					// if(/(free_shipping)/.test(c) && v != c) {
					// 	cond = false;
					// 	console.log(v != c, /(free_shipping)/.test(c));
					// }
					return cond;
				}

				$scope.cancelOrder = (link)=> {
					ecommerce
						.get(link)
						.then(()=> {
							$state.go('app.page', {slug : vars.wc.accountBase});
						});
				}
				$scope.passwordFields = {}
				$scope.resetPwdMatch = (form, user)=> {
					form.password_2.$setValidity('pwdmatch', user.password_2 == user.password_1);
				}
				$scope.lostPassword = (form, reset)=> {
					if(form.$invalid) return;
					$scope.passwordRecovering = true;
					let url = (reset) ? vars.wc.reset : vars.wc.password;
					let data = $scope.passwordFields;
					if(reset) {
						if($scope.resetPasswordMessage) delete $scope.resetPasswordMessage;
					} else {
						if($scope.passwordMessage) delete $scope.passwordMessage;
					}
					// ecommerce
					// 	.post(url, data)
					// 	.then( (res)=> {
					// 		var result = res.data;
					// 		console.log(result);
					// 		$scope.passwordRecovering = false;
					// 	});
					ecommerce.post(url, data).then( (res)=> {
						var result = res.data;
						if(result.error) {
							if(reset) {
								$scope.resetPasswordMessage = result.error;
								$scope.isResetPasswordError = true;
							} else {
								$scope.passwordMessage = result.error;
								$scope.isPasswordError = true;
							}
						} else {
							if(reset){
								$scope.resetPasswordMessage = vars.wc.resetPasswordMessage;
								$scope.isResetPasswordError = false;
							} else {
								$scope.passwordMessage = vars.wc.passwordMessage;
								$scope.isPasswordError = false;
							}
						}
						$scope.passwordRecovering = false;
					});
				}
			}
			$rootScope.initEcommerce();
		}]
	}
}