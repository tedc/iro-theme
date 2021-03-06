<div class="order-review__container">
	<header class="order-review__header">
		<h4 class="order-review__subtitle"><?php _e('Riepilogo ordine', 'iro'); ?></h4>
	</header>
	<div class="order-review__row" ng-repeat="item in ngCart.getCart().items track by $index">
		<span>
			<!-- <a class="order-review__name" ng-href="{{item.getData().href}}}" ui-sref="app.page({slug : item.getData().href})" ng-bind-html="item.getName()"></a> -->
			<a class="order-review__name" ng-href="{{item.getData().href}}" ng-bind-html="item.getName()"></a>
			<span class="order-review__qty"">x{{item.getQuantity()}}</span>
		</span>
		<strong class="order-review__price" ng-bind-html="'€ ' + (item.getPrice())"></strong>
		<div class="order-review__desc" ng-bind-html="ngCart.getDesc(item)" ng-if="ngCart.getDesc(item)"></div>
		<div class="order-review__desc" ng-bind-html="item.getData().custom_size" ng-if="item.getData().custom_size"></div>
	</div>
	<div class="order-review__row order-review__row--meta" ng-if="ngCart.getExtras().shippings.packages">
		<strong><?php _e('Spese di spedizione', 'iro'); ?></strong>
		<div class="order-review__shipping" ng-repeat="shipping in ngCart.getExtras().shippings.packages track by $index" >
			<span class="order-review__name" ng-bind-html="shipping.chosen_label"></span> 
			<strong class="order-review__price" ng-bind-html="(shipping.chosen_price)"></strong>
			<div class="order-review__extras" ng-if="shipping.extras.length > 0" ng-repeat="extra in shipping.extras">
				<span class="order-review__name">
					<strong ng-bind-html="extra.extra_title"></strong><br />
					<span ng-repeat="c in extra.choices.choices" ng-bind-html="c"></span>
				</span> 
				<strong class="order-review__price" ng-bind-html="'€ ' + (extra.extra_price)"></strong>	
			</div>
		</div>
	</div>
	<div class="order-review__row order-review__row--meta" ng-if="ngCart.getExtras().coupons.length > 0">
		<strong><?php _e('Codici sconto', 'iro'); ?></strong>
		<div class="order-review__coupon" ng-repeat="coupon in ngCart.getExtras().coupons track by $index">
			<strong class="order-review__name">{{coupon.label}}</strong>
			<a class="order-review__remove" ng-attr-href="{{item.getData().remove_item_url}}" ng-click="$event.preventDefault(); ngCart.deleteCoupon(coupon.remove, $index)">
				<i class="icon-chiudi"></i>
			</a>
			<div class="order-review__desc" ng-bind-html="ngCart.getCouponAumount(coupon)"></div>
			<div class="order-review__gift" ng-if="coupon.free_gifts" bind-html-compile=coupon.free_gifts></div>
		</div>
	</div>
	<div class="order-review__total">
		<strong class="order-review__total-label"><?php _e('Totale', 'iro'); ?></strong>
		<span class="order-review__total-price" ng-bind-html="'€ ' + ((ngCart.getDiscountTotal(ngCart.totalCost()) - ngCart.getShipping()))"></span>
	</div>
</div>