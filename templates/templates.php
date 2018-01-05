
<script type="text/ng-template" id="login.html">
	<?php wc_get_template_part('myaccount/form', 'login'); ?>
</script>
<script type="text/ng-template" id="register.html">
	<?php wc_get_template_part('myaccount/form', 'register'); ?>
</script>
<script type="text/ng-template" id="cart-summary.html">
  <a ui-sref="tab({name : 'cart'})" class="banner__btn banner__btn--cart">
  	<i class="icon-bag"></i>
  	<span class="banner__count" ng-class="{'banner__count--loading':ngCart.isCounting, 'banner__count--filled' : ngCart.getTotalItems() > 0}">
  		<span class="banner__count-number" ng-bind-html="ngCart.getTotalItems()" ng-if="ngCart.getTotalItems() > 0"></span>
  	</span>
  </a>
</script>
<script type="text/ng-template" id="cart.html">
	<ngcart-cart template-url="cart-aside.html"></ngcart-cart>
</script>
<script type="text/ng-template" id="cart-aside.html">
	<?php wc_get_template_part('cart/cart', 'aside'); ?>
</script>
