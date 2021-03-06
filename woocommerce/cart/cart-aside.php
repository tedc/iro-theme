<aside class="cart-aside" id="cart" ng-class="{'cart-aside--counting': ngCart.isCounting || ngCart.isUpdating}">
	<div class="cart-aside__container">
		<header class="cart-aside__header cart-aside__header--shrink">
			<h3 class="cart-aside__title cart-aside__title--small"><?php _e('Carrello', 'iro'); ?></h3>
			<span class="cart-aside__close" ng-click="ngCart.close()"><?php _e('Chiudi', 'iro'); ?><i class="icon-chiudi"></i></span>
		</header>
		<div class="cart-aside__wrapper" scroller options="{'freeMode':true, 'direction':'vertical','mousewheel':true,'slidesPerView':'auto', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true}}" ng-if="ngCart.getTotalItems() == 0">
			<div class="swiper-wrapper swiper-wrapper--vertical">
				<div class="cart-aside__item swiper-slide cart-aside__item--grow cart-aside__item--aligncenter">
					<?php _e('Il tuo carrello è vuoto', 'iro'); ?>
				</div>
			</div>
			<span class="swiper-scrollbar"></span>
		</div>
		<div class="cart-aside__wrapper" scroller options="{'freeMode':true, 'direction':'vertical','mousewheel':true,'slidesPerView':'auto', 'scrollbar':{'el':'.swiper-scrollbar', 'draggable':true}}" ng-if="ngCart.getTotalItems() > 0">
			<div class="swiper-wrapper swiper-wrapper--vertical">
				<div class="cart-aside__item swiper-slide cart-aside__item--shrink cart-aside__item--grow" ng-repeat="item in ngCart.getCart().items track by $index" on-finish-render="update_scroller">
					<figure class="cart-aside__figure" ng-if="item.getData().image.thumb_src">
						<img ng-src="{{item.getData().image.thumb_src}}" class="cart-aside__image"/>
					</figure>
					<div class="cart-aside__top cart-aside__top--grow-top">
						<div class="cart-aside__info">
							<!-- <a ui-sref="app.page({slug : item.getData().href})" ng-bind-html="item.getName()"></a> -->
							<a ng-href="{{item.getData().href}}" ng-bind-html="item.getName()"></a>
							<div class="cart-aside__desc" ng-bind-html="ngCart.getDesc(item)" ng-if="ngCart.getDesc(item)"></div>
							<div class="cart-aside__desc" ng-bind-html="item.getData().custom_size" ng-if="item.getData().custom_size"></div>
						</div>
						<a class="cart-aside__remove" ng-attr-href="{{item.getData().remove_item_url}}" ng-click="$event.preventDefault(); ngCart.delete(item.getData().item_key, $index, item.getId())">
							<?php _e('Rimuovi', 'iro'); ?> <i class="icon-chiudi"></i>
						</a>
					</div>
					<div class="cart-aside__bottom cart-aside__bottom--grow-top">
						<div class="cart-aside__qty">
							<span class="cart-aside__minus" ng-click="ngCart.changeQty(item, true, -1)">-</span>
							<span class="cart-aside__value">{{ item.getQuantity() | number }}</span>
							<span class="cart-aside__plus" ng-click="ngCart.changeQty(item, true, +1)">+</span>
						</div>
						<span class="cart-aside__price">{{'€ ' + item.getPrice()}}</span>
					</div>
				</div>
				<div class="cart-aside__subtotal swiper-slide cart-aside__subtotal--grow cart-aside__subtotal--shrink">
					<span class="cart-aside__label"><?php _e('Subtotale', 'iro'); ?></span>
					<span class="cart-aside__price" ng-bind-html="'€ ' + (ngCart.getSubTotal())"></span>
				</div>
				<div class="cart-aside__variation swiper-slide cart-aside__variation--grow">
					<div class="cart-aside__coupons" ng-if="!ngCart.getExtras().coupons || ngCart.getExtras().coupons.length > 0">
					<?php 
			$info_message = apply_filters( 'woocommerce_checkout_coupon_message', ' <a href="#" class="cart-aside__showcoupon cart-aside__showcoupon--shrink">' . __( 'Hai un codice sconto?', 'iro' ) . '</a>' );
			wc_print_notice( $info_message, 'notice' );
		?>
					<div class="cart-aside__coupon cart-aside__coupon--shrink cart-aside__coupon--grid-nowrap slide-toggle" ng-class="{'slide-toggle--visible':isCoupon}">
						<input type="text" name="coupon_code" class="cart-aside__input" ng-model="coupon_code" placeholder="<?php esc_attr_e( 'Inserire codice sconto', 'iro' ); ?>" id="coupon_code" value="" />
						<input type="hidden" ng-model="coupon_nonce" ng-init="coupon_nonce='<?php echo wp_create_nonce( 'apply-coupon' ); ?>'" />
						<span class="cart-aside__button cart-aside__button--dark cart-aside__button--radius-right" ng-class="{'cart-aside__button--loading':ngCart.isDescountLoading}" ng-click="ngCart.applyCoupon(coupon_code, coupon_nonce)"><?php _e( 'Applica', 'iro' ); ?></span>
					</div>
				</div>
					<div class="cart-aside__error cart-aside__error--shrink" ng-if="ngCart.couponError"><p ng-bind-html="ngCart.couponError"></p></div>
					<div class="cart-aside__coupons cart-aside__coupons--shrink" ng-if="ngCart.getExtras().coupons" ng-repeat="coupon in ngCart.getExtras().coupons track by $index" on-finish-render="update_scroller">
						<div class="cart-aside__coupon">
							<div class="cart-aside__top">
								<div class="cart-aside__info">
									<strong>{{coupon.label}}</strong>
									<div class="cart-aside__desc" ng-bind-html="ngCart.getCouponAumount(coupon)"></div>
									<div class="cart-aside__gift" ng-if="coupon.free_gifts" bind-html-compile=coupon.free_gifts></div>
								</div>
								<a class="cart-aside__remove" ng-attr-href="{{item.getData().remove_item_url}}" ng-click="$event.preventDefault(); ngCart.deleteCoupon(coupon.remove, $index)">
									<?php _e('Rimuovi', 'iro'); ?>
									<i class="icon-chiudi"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="cart-aside__total swiper-slide cart-aside__total--grow cart-aside__total--shrink">
					<span class="cart-aside__label"><?php _e('Totale', 'iro'); ?></span>
					<span class="cart-aside__price" ng-bind-html="'€ ' + ((ngCart.getDiscountTotal(ngCart.totalCost()) - ngCart.getShipping()))"></span>
				</div>
			</div>
			<span class="swiper-scrollbar"></span>
		</div>

	<span ng-if="ngCart.getTotalItems() == 0" class="cart-aside__button cart-aside__button--noradius" ng-click="ngCart.close()"><?php _e('Continua lo shopping', 'iro'); ?></span>
	<!-- <a ng-if="!ngCart.logged" href="#login" class="cart-aside__button cart-aside__button--noradius"><?php _e('Entra per proseguire', 'iro'); ?>
	</a> -->
	<!-- <a ng-if="ngCart.getTotalItems() > 0" href="<?php echo wc_get_page_permalink('checkout'); ?>" ui-sref="app.page({slug : '<?php echo basename(wc_get_page_permalink('checkout')); ?>'})" class="cart-aside__button cart-aside__button--noradius"><?php _e('Vai al checkout', 'iro'); ?>
	</a> -->
	<a ng-if="ngCart.getTotalItems() > 0" href="<?php echo wc_get_page_permalink('checkout'); ?>" class="cart-aside__button cart-aside__button--noradius"><?php _e('Vai al checkout', 'iro'); ?>
	</a>
	</div>
</aside>