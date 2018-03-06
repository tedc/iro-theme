
<?php wc_get_template_part('myaccount/form', 'login'); ?>
<?php wc_get_template_part('myaccount/form', 'register'); ?>
<script type="text/ng-template" id="cart-summary.html">
  <a href="#cart" class="banner__btn banner__btn--cart" ng-class="{'banner__btn--cart-filled' : ngCart.getTotalItems() > 0}">
  	<i class="icon-bag"></i>
  	<span class="banner__count" ng-class="{'banner__count--loading':ngCart.isCounting}">
  		<span class="banner__count-number" ng-bind-html="ngCart.getTotalItems()" ng-if="ngCart.getTotalItems() > 0"></span>
  	</span>
  </a>
</script>
<ngcart-cart template-url="cart-aside.html"></ngcart-cart>
<script type="text/ng-template" id="cart-aside.html">
	<?php wc_get_template_part('cart/cart', 'aside'); ?>
</script>